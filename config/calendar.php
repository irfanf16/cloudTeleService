<?php

return [
    'id' => env('GOOGLE_CALENDAR_ID'), //google_calendar_id
    'application_name' => env('GOOGLE_CALENDAR_APPLICATION_NAME'),
    'notify_to' => [
        'email' => env('GOOGLE_CALENDAR_EVENT_NOTIFY_TO_EMAIL'),
        'displayName' => env('GOOGLE_CALENDAR_EVENT_NOTIFY_TO_NAME'),
    ],
    'admin' => [
        'email' => env('GOOGLE_CALENDAR_EVENT_CREATOR_EMAIL'),
        'displayName' => env('GOOGLE_CALENDAR_EVENT_CREATOR_NAME'),
    ],
    'credentials' => [
        'key_file' => storage_path('app/google/calendar-credentials.json'),
        'impersonate_user' => null,
    ],
    'scopes' => [
        'https://www.googleapis.com/auth/calendar',
    ],
    'event' => [
        'interval' => 60,
        'statuses' => [
            'confirmed',
            'tentative',
            'cancelled',
            'pending',
        ],
    ],
    'booking_fee' => env('BOOKING_FEE'),
    'flight_fee' => env('FLIGHT_FEE'),
    'working_slots' => [
        '09:00:00',
        '10:00:00',
        '11:00:00',
        '12:00:00',
        '13:00:00',
        '14:00:00',
        '15:00:00',
        '16:00:00',
    ],
];
