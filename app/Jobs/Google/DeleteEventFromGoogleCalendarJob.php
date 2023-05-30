<?php

namespace App\Jobs\Google;

use App\Models\Event;
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

class DeleteEventFromGoogleCalendarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $calendar = $this->event->calendar;
            $service = new GoogleCalendarService($calendar->ref_id);
            $googleEvent = $service->deleteEvent($this->event->ref_id);
        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}
