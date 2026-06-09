# Cloud Tele Service — Appointment Booking API

**Laravel 9 REST API** for a telecommunications/appointment booking service. Handles calendar-based booking with Stripe payments, Google Calendar synchronization (full + incremental sync), and a Blade admin panel. Background job processing via Laravel Horizon queues.

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=flat&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=flat&logo=laravel)
![Stripe](https://img.shields.io/badge/Stripe-Payments-008CDD?style=flat&logo=stripe)
![Google Calendar](https://img.shields.io/badge/Google_Calendar_API-4285F4?style=flat&logo=googlecalendar)
![Laravel Horizon](https://img.shields.io/badge/Laravel_Horizon-Queue-FF2D20?style=flat&logo=laravel)
![Redis](https://img.shields.io/badge/Redis-Queue-DC382D?style=flat&logo=redis)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql)

## Features

**Appointment Booking** — Client submits name, email, services, and Stripe token. Backend charges the booking fee, creates the appointment, and dispatches a job to add it to Google Calendar with a Google Meet link.

**Google Calendar Sync** — Full sync, incremental sync via sync tokens, free/busy checking, event CRUD (`GoogleCalendarService`). Processed async via `events_sync` queue.

**Working Time Slots** — Configurable 1-hour slots (09:00–16:00); exposed via `GET /api/event/slots`.

**Flight Booking** — Book a flight request (origin/destination/class) with Stripe charge at configurable `FLIGHT_FEE`.

**Contact Form** — `POST /api/contact` stores inquiries.

**Admin Panel** (Blade) — Dashboard with FullCalendar view, event CRUD + status management (confirmed/pending/cancelled), document upload, flights list, contacts list.

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/google/calendar/{calendar}/events` | Create appointment (charge + Google Calendar) |
| `POST` | `/api/event/slots` | Get available time slots |
| `POST` | `/api/flight` | Book a flight (Stripe charge) |
| `POST` | `/api/contact` | Submit contact form |
| `GET/PUT/DELETE` | `/api/google/calendar/{calendar}/events/{event}` | Event CRUD |

## Database Schema

Tables: `users`, `calendars`, `events`, `event_attendees`, `flights`, `payments`, `contacts`, `documents`

- `events` — UUID PK, calendar ref, title, attendees, status, Stripe payment info
- `flights` — UUID PK, origin, destination, class, Stripe charge, status
- `payments` — Stripe charge records linked to events/flights

## Architecture

```
API routes:
  POST /api/google/calendar/{calendar}/events
    → GoogleCalendarController
      → Stripe::charge()
      → Event::create()
      → dispatch(AddToGoogleCalendarJob)  →  Google Calendar API

Admin routes:
  /dashboard  /events  /flights  /contact-us

Queue: events_sync (Redis + Horizon)
  → GoogleCalendarService (full/incremental sync, free/busy)
```

> Uses `lorisleiva/laravel-actions` — all business logic in single-responsibility Action classes. Google service account key at `storage/app/google/calendar-credentials.json`.

## Getting Started

```bash
composer install
cp .env.example .env && php artisan key:generate
# Set DB_*, GOOGLE_CALENDAR_ID, STRIPE_SECRET, BOOKING_FEE, FLIGHT_FEE, APP_SPA_URL
php artisan migrate --seed
# Place Google service account JSON at storage/app/google/calendar-credentials.json
php artisan horizon && php artisan serve
```

## License
MIT
