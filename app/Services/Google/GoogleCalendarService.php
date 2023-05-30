<?php
namespace App\Services\Google;

use Google\Service\Calendar\Event as CalendarEvent;
use Google\Service\Calendar\EventExtendedProperties;
use Google\Service\Calendar\Events as CalendarEvents;

class GoogleCalendarService
{
    /**
     * calendar
     *
     * @var string
     */
    protected $calendar_id;
    protected $service;

    /**
     * __construct
     *
     * @param  string $calendarId
     * @return void
     */
    public function __construct(string $calendarId)
    {
        $this->calendar_id = $calendarId;
        $client = new \Google\Client ();
        $client->setApplicationName(config('calendar.application_name'));
        $client->setAuthConfig(config('calendar.credentials.key_file'));
        $client->setScopes(config('calendar.scopes'));
        $client->setSubject(config('calendar.admin.email'));
        $this->service = new \Google\Service\Calendar ($client);
    }

    public function getCalendar(): \Google\Service\Calendar\Calendar
    {
        $calendar = $this->service->calendars->get($this->calendar_id);
        return $calendar;
    }

    /**
     * Initial full sync
     * https://developers.google.com/calendar/api/guides/sync#initial_full_sync
     *
     * @return CalendarEvents
     */
    public function initalFullSync(): CalendarEvents
    {
        return $this->service->events->listEvents($this->calendar_id, [
            'maxResults' => 100,
            'showDeleted' => 'true',
            'singleEvents' => true,
        ]);
    }

    /**
     * Incremental sync
     * https://developers.google.com/calendar/api/guides/sync#incremental_sync
     *
     * @param  string           $syncToken
     * @return CalendarEvents
     */
    public function incrementalSync(string $syncToken): CalendarEvents
    {
        return $this->service->events->listEvents($this->calendar_id, [
            'maxResults' => 100,
            'singleEvents' => true,
            'syncToken' => $syncToken,
        ]);
    }

    public function freeBusy(
        string $timeMin,
        string $timeMax
    ) {
        $request = new \Google\Service\Calendar\FreeBusyRequest ([
            'timeMin' => $timeMin,
            'timeMax' => $timeMax,
            'timeZone' => config('app.timezone'),
            'items' => [
                ['id' => $this->calendar_id],
            ],
        ]);
        return $this->service->freebusy->query($request);
    }

    /**
     * generate google calendar's Event id
     * rules
     * characters allowed in the ID are those used in base32hex encoding, i.e. lowercase letters a-v and digits 0-9
     * see section 3.1.2 in RFC2938 the length of the ID must be between 5 and 1024 characters
     *
     * @return void
     */
    private function generateEventId()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4();
        // Ensure the UUID is in base32hex encoding
        $uuid = str_replace(['-', '_'], ['', ''], $uuid->toString());

        // Ensure the UUID length is between 5 and 1024 characters
        $uuid = substr($uuid, 0, 1024);

        // Remove any characters that are not allowed in the ID
        $uuid = preg_replace('/[^a-v0-9]/', '', $uuid);

        // Ensure the UUID length is between 5 and 1024 characters
        $uuid = substr($uuid, 0, 1024);

        return $uuid;
    }

    /**
     * creates event in google calendar
     *
     * @param  string      $eventUuid
     * @param  string|null $summary
     * @param  string      $description
     * @param  string      $start
     * @param  string      $end
     * @param  string      $fname
     * @param  string      $lname
     * @param  string      $email
     * @param  string      $phone
     * @return array
     */
    public function createEvent(
        string $eventUuid,
        string | null $summary,
        string $description,
        string $start,
        string $end,
        string $fname,
        string $lname,
        string $email,
        string $phone
    ): CalendarEvent{
        $cancellationLink = "<a href=" . config('app.spa_url') . "/appointment/$eventUuid>Reschedule</a>";
        $eventBody = new CalendarEvent([
            'summary' => $summary,
            'description' => $description . "\n" . $cancellationLink,
            'start' => array(
                'dateTime' => $start,
                'timeZone' => config('app.timezone'),
            ),
            'end' => array(
                'dateTime' => $end,
                'timeZone' => config('app.timezone'),
            ),
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet',
                    ],
                ],
            ],
            'attendees' => [[
                'email' => $email,
                'displayName' => $fname . ' ' . $lname,
            ], [
                'email' => config('calendar.notify_to.email'),
                'displayName' => config('calendar.notify_to.displayName'),
                // 'responseStatus' => 'accepted',
            ]],
            // 'extendedProperties' => [
            //     'private' => [
            //         'fname' => $fname,
            //         'lname' => $lname,
            //         'phone' => $phone,
            //     ],
            // ],
        ]);

        // Insert the event into the user's calendar
        $event = $this->service->events->insert($this->calendar_id, $eventBody, [
            'conferenceDataVersion' => 1,
            'sendUpdates' => 'all',
        ]);

        return $event;
    }

    /**
     * get events from google calendar
     *
     * @return void
     */
    public function getEvents()
    {
        $events = $this->service->events->listEvents($this->calendar_id, [
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ]);
        return $events;
    }

    /**
     * get events from google calendar
     *
     * @param  string $eventId
     * @return void
     */
    public function getEvent(string $eventId)
    {
        $events = $this->service->events->get($this->calendar_id, $eventId);
        return $events;
    }

    /**
     * delete event from google calendar
     *
     * @param  string $eventId
     * @return void
     */
    public function deleteEvent(string $eventId)
    {
        return $this->service->events->delete($this->calendar_id, $eventId, [
            'sendUpdates' => 'all',
        ]);
    }

    /**
     * update event in google calendar
     *
     * @param  string $eventId
     * @param  string $start
     * @param  string $end
     * @return void
     */
    public function updateEvent(
        string $eventId,
        string $start,
        string $end
    ) {
        $event = $this->service->events->get($this->calendar_id, $eventId);
        $event->setStart(new \Google\Service\Calendar\EventDateTime ([
            'dateTime' => $start,
            'timeZone' => config('app.timezone'),
        ]));
        $event->setEnd(new \Google\Service\Calendar\EventDateTime ([
            'dateTime' => $end,
            'timeZone' => config('app.timezone'),
        ]));
        return $this->service->events->patch($this->calendar_id, $event->id, $event, [
            'sendUpdates' => 'all',
        ]);
    }
}
