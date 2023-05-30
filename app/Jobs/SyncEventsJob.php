<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Calendar;
use App\Models\EventAttendee;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Google\GoogleCalendarService;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncEventsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $calendar;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new GoogleCalendarService($this->calendar->ref_id);
        $calendarEvents = $service->incrementalSync($this->calendar->sync_token);
        DB::beginTransaction();
        try {
            foreach ($calendarEvents as $calendarEvent) {
                // if($calendarEvent->status === )
                $event = Event::where('ref_id', $calendarEvent->id)->first();
                if ($event) {
                    if ($calendarEvent->status === config('calendar.event.statuses')[2]) {
                        $event->update(['status' => $calendarEvent->status]);
                    } else {
                        $event->update([
                            'description' => $calendarEvent->description,
                            'summary' => $calendarEvent->summary,
                            'hangoutLink' => $calendarEvent->hangoutLink,
                            'start' => \Carbon\Carbon::parse($calendarEvent->start->dateTime)->setTimezone(config('app.timezone')),
                            'end' => \Carbon\Carbon::parse($calendarEvent->end->dateTime)->setTimezone(config('app.timezone')),
                            'timezone' => $calendarEvent->start->timeZone,
                            'status' => $calendarEvent->status,
                        ]);
                    }
                } else {
                    if ($calendarEvent->status !== config('calendar.event.statuses')[2]) {
                        $event = $this->calendar->events()->create([
                            'ref_id' => $calendarEvent->id,
                            'description' => $calendarEvent->description,
                            'summary' => $calendarEvent->summary,
                            'hangoutLink' => $calendarEvent->hangoutLink,
                            'start' => \Carbon\Carbon::parse($calendarEvent->start->dateTime)->setTimezone(config('app.timezone')),
                            'end' => \Carbon\Carbon::parse($calendarEvent->end->dateTime)->setTimezone(config('app.timezone')),
                            'timezone' => $calendarEvent->start->timeZone,
                            'status' => $calendarEvent->status,
                        ]);
                        foreach ($calendarEvent->attendees as $eventAttendee) {
                            $attendee = EventAttendee::firstOrCreate([
                                'email' => $eventAttendee->email,
                                'fname' => $calendarEvent->extendedProperties->private['fname'],
                                'lname' => $calendarEvent->extendedProperties->private['lname'],
                                'phone' => $calendarEvent->extendedProperties->private['phone'],
                            ]);
                            $event->attendees()->attach($attendee->id);
                        }
                    }
                }

            }
            $this->calendar->update(['sync_token' => $calendarEvents->nextSyncToken]);
        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}
