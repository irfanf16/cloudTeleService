<?php

namespace Database\Seeders;

use App\Jobs\SyncEventsJob;
use App\Models\Calendar;
use Illuminate\Database\Seeder;
use App\Services\Google\GoogleCalendarService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = new GoogleCalendarService(config('calendar.id'));
        $calendar = $service->getCalendar();
        Calendar::create([
            'id' => '98cc7316-bdd5-4075-8f50-285622686f88',
            'ref_id' => $calendar->id,
            'timezone' => $calendar->timeZone,
            'sync_token' => $service->initalFullSync()->nextSyncToken,
        ]);
        // Calendar::create([
        //     'id' => '98cc7316-bdd5-4075-8f50-285622686f88',
        //     'ref_id' => config('calendar.id'),
        //     'timezone' => config('app.timezone'),
        //     'sync_token' => 'abc'
        // ]);
    }
}
