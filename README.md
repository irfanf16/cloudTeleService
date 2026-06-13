# CloudTeleService — Telehealth Appointment Scheduling Platform

> **Laravel 9** REST API for managing telehealth appointments via **Google Calendar** as the source of truth. Implements full/incremental sync, async Google Meet link generation, Stripe payment on booking, and a single-action architecture via `lorisleiva/laravel-actions`. Backed by Redis/Horizon for async queue processing.

![Laravel](https://img.shields.io/badge/Laravel-9-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0-777BB4?style=flat-square&logo=php&logoColor=white)
![Google Calendar](https://img.shields.io/badge/Google_Calendar_API-4285F4?style=flat-square&logo=google-calendar&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-10.x-635BFF?style=flat-square&logo=stripe&logoColor=white)
![Horizon](https://img.shields.io/badge/Laravel_Horizon-Redis-FF2D20?style=flat-square)

---

## Table of Contents
- [Architecture](#architecture)
- [Tech Stack](#tech-stack)
- [Route Structure](#route-structure)
- [Database Schema](#database-schema)
- [Google Calendar Integration](#google-calendar-integration)
- [Booking & Payment Flow](#booking--payment-flow)
- [Async Queue Jobs](#async-queue-jobs)
- [Availability Slot Logic](#availability-slot-logic)
- [Single-Action Pattern](#single-action-pattern)
- [Getting Started](#getting-started)

---

## Architecture

```
Client (SPA / mobile)
    |
    v
REST API (this repo — Laravel 9)
    |
    +--- Stripe Charge (synchronous, before any DB write)
    |
    +--- Write to local DB (events, attendees, payments)
    |
    +--- Dispatch async queue jobs (Laravel Horizon + Redis)
              |
              v
         Google Calendar API
         - Create event with Google Meet link
         - Send attendee email notifications
         - Store sync_token for incremental sync
```

**Google Calendar is the authoritative calendar store.** The local `events` table mirrors calendar data, linked by `ref_id` (Google Calendar event ID). Incremental sync keeps local DB current with external calendar changes.

---

## Tech Stack

| Package | Version | Purpose |
|---|---|---|
| `laravel/framework` | ^9.19 | Core framework |
| `google/apiclient` | ^2.0 | Google Calendar API (OAuth2 service account) |
| `stripe/stripe-php` | ^10.12 | Payment processing |
| `lorisleiva/laravel-actions` | ^2.5 | Single-action architecture (controller + job + command in one class) |
| `laravel/horizon` | ^5.14 | Queue monitoring and management |
| `predis/predis` | ^2.1 | Redis driver (queue backend) |
| `laravel/passport` | ^11.8 | OAuth2 |
| `laravel/sanctum` | ^3.0 | API token auth |
| `yajra/laravel-datatables-oracle` | ~9.0 | Admin panel server-side tables |
| `guzzlehttp/guzzle` | ^7.2 | HTTP client |
| `doctrine/dbal` | ^3.6 | Database abstraction |

**Queue:** Redis via Predis. **Queue name:** `events_sync`. **Monitor:** Laravel Horizon.

---

## Route Structure

**REST API (`/api/*`):**

| Method | Endpoint | Action Class | Description |
|---|---|---|---|
| `GET` | `/google/calendar/{calendar}/events` | `GetAllEventsAction` | List all calendar events |
| `POST` | `/google/calendar/{calendar}/events` | `CreateEventAction` | Book appointment (Stripe charge + queue dispatch) |
| `GET` | `/google/calendar/{calendar}/events/{event}` | `GetEventAction` | Get single event detail |
| `PUT` | `/google/calendar/{calendar}/events/{event}` | `UpdateEventAction` | Reschedule appointment |
| `DELETE` | `/google/calendar/{calendar}/events/{event}` | `DeleteEventAction` | Cancel appointment |
| `POST` | `/api/event/slots` | `GetAllEventsSlotAction` | Available time slots for a given date |
| `POST` | `/api/contact` | `ContactAction` | Contact form submission |
| `POST` | `/api/flight` | `CreateFlightAction` | Flight booking request (Stripe charge) |
| `GET` | `/api/google/calendar/sync` | Closure | Trigger incremental sync manually |
| `GET` | `/api/get/stripe/token` | `GetStripeTokenAction` | Test token generation |

**Admin Web Panel (Blade, session auth):**
`/admin/dashboard` — FullCalendar.js event view, `/admin/events` (list + detail + status update + document upload), `/admin/flights` (flight request list), `/admin/contacts`.

---

## Database Schema

All domain models use UUID primary keys (`HasUuids` trait).

| Table | Key Columns |
|---|---|
| `calendars` | uuid PK, `ref_id` (Google Calendar ID), timezone, `sync_token` (incremental sync cursor) |
| `events` | uuid PK, `ref_id` (Google Calendar event ID), calendar_id FK, description, summary, services, `hangoutLink`, start, end, timezone, `status` (enum: confirmed/tentative/cancelled/pending) |
| `event_attendees` | uuid PK, fname, lname, phone, email |
| `event_event_attendee` | pivot — event_id, event_attendee_id |
| `flights` | uuid PK, name, email, phone, location, destination, class, terms |
| `documents` | uuid PK, event_id FK, created_by, type, description, doc_link |
| `payments` | uuid PK, event_id FK (nullable), flight_id FK (nullable), amount, stripeToken |
| `contacts` | id, name, email, phone, subject, message, terms |
| `users` | Standard Laravel users table |

---

## Google Calendar Integration

**Service class:** `GoogleCalendarService` — wraps `google/apiclient`.

**Authentication:** OAuth2 service account with domain-wide delegation. Credentials stored at `storage/app/google/calendar-credentials.json`. The service impersonates the calendar owner's Google account.

**Sync Strategy:**
```
First run:  initalFullSync()
   -> Lists ALL events from Google Calendar
   -> Stores sync_token in calendars.sync_token

Subsequent runs: incrementalSync(syncToken)
   -> Fetches only changes since last sync
   -> Updates local events DB (created/updated/cancelled)
   -> Stores new sync_token

Triggered by: GET /api/google/calendar/sync
```

**Create Event Flow:**
```
POST /google/calendar/{calendar}/events
    |
    v
Google Calendar API: events.insert()
  - conferenceData: { createRequest: { hangoutsMeet } }  <- auto-generates Google Meet link
  - sendUpdates: 'all'  <- attendee email notifications sent by Google
  - Reschedule link embedded in event description: {APP_SPA_URL}/appointment/{eventUuid}
    |
    v
Response includes: hangoutLink (Google Meet URL), Google event ID (stored as ref_id)
```

**Methods:**
`initalFullSync()`, `incrementalSync(syncToken)`, `freeBusy()`, `createEvent()`, `updateEvent()`, `deleteEvent()`, `getEvent()`.

---

## Booking & Payment Flow

```
1. Client calls POST /google/calendar/{calendar}/events

2. Stripe Charge (synchronous — must succeed before any DB write):
   Stripe::create('Charge', [
     'amount' => config('calendar.booking_fee') * 100,
     'currency' => 'usd',
     'source' => $request->stripeToken,
   ])

3. DB writes in a single transaction:
   a. Create Event record (local mirror)
   b. Create EventAttendee record
   c. Attach attendee to event (pivot)
   d. Create Payment record

4. Dispatch async job: AddEventsToGoogleCalendarJob (on events_sync queue)
   -> GoogleCalendarService::createEvent() -> Google Calendar API
   -> Updates local event.ref_id with Google event ID
   -> Updates local event.hangoutLink with Google Meet URL

5. Return event UUID to client
   (client polls /api/appointment/{uuid} until hangoutLink is populated)
```

**Flight booking** (`POST /api/flight`) follows the same pattern: Stripe charge → `flights` DB record → `payments` record.

---

## Async Queue Jobs

All dispatched to the `events_sync` queue, monitored by Laravel Horizon:

| Job | Trigger | Action |
|---|---|---|
| `AddEventsToGoogleCalendarJob` | New booking confirmed | Creates event in Google Calendar, stores `ref_id` + `hangoutLink` |
| `UpdateEventFromGoogleCalendarJob` | Rescheduled appointment | Updates Google Calendar event time, sends attendee update email |
| `DeleteEventFromGoogleCalendarJob` | Cancelled appointment | Deletes event from Google Calendar, notifies attendees |
| `SyncEventsJob` | Manual sync trigger or scheduled | Runs `incrementalSync()`, updates local DB from Google changes |

---

## Availability Slot Logic

`GetAllEventsSlotAction` — calculates free booking slots for a given date:

```
1. Read config('calendar.working_slots')  <- fixed hours e.g. 09:00-16:00
2. For each slot time:
   a. Check if events table has a row with matching start time (slot taken)
   b. If today's date, filter out slots in the past (timezone-aware)
3. Return only available (unclaimed) slot times
```

**Config** (`config/calendar.php`): `calendar_id`, `booking_fee`, `flight_fee`, `working_slots[]`, `event_interval` (60 min), `admin_email`, `notify_to`, `statuses[]`.

---

## Single-Action Pattern

Uses `lorisleiva/laravel-actions ^2.5` — each action class can be invoked as:
- A **controller** (via `asController()`)
- A **job** (via `asJob()`)
- An **artisan command** (via `asCommand()`)
- A **listener** (via `asListener()`)

```php
class CreateEventAction
{
    use AsAction;

    public function rules(): array { /* validation */ }

    public function handle(Calendar $calendar, array $data): Event
    {
        // Core business logic
    }

    public function asController(Calendar $calendar, Request $request): JsonResponse
    {
        // HTTP-specific: Stripe charge, DB transaction, queue dispatch
    }
}
```

This eliminates traditional controllers — each action is a self-contained, testable unit of business logic.

---

## Getting Started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Place Google service account credentials:
# storage/app/google/calendar-credentials.json

php artisan horizon          # Queue worker
php artisan serve
```

**Required environment variables:**
```env
GOOGLE_CALENDAR_CREDENTIAL_PATH=storage/app/google/calendar-credentials.json
GOOGLE_CALENDAR_SUBJECT=admin@yourdomain.com  # Impersonated account
APP_SPA_URL=https://your-frontend.com

STRIPE_KEY=
STRIPE_SECRET=

REDIS_HOST=
QUEUE_CONNECTION=redis
```
