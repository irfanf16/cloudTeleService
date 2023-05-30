<?php

namespace App\Actions\Api\Event;

use App\Jobs\Google\UpdateEventFromGoogleCalendarJob;
use App\Models\Event;
use App\Models\Calendar;
use App\Jobs\SyncEventsJob;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Services\Google\GoogleCalendarService;

class UpdateEventAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start' => 'required',
        ];
    }

    /**
     * creates event in google calendar
     *
     * @param  Calendar $calendar
     * @param  Event    $event
     * @param  string   $start
     * @return array
     */
    public function handle(
        Calendar $calendar,
        Event $event,
        string $start,
    ): array
    {
        DB::beginTransaction();
        try {

            $end = \Carbon\Carbon::parse($start)->addMinutes(config('calendar.event.interval'))->toIso8601String();

            // $calendar = Calendar::find(config('calendar.id'));
            // $service = new GoogleCalendarService($calendar->id);
            // if (!$calendar->isSlotAvailable($start, $end) || $service->freeBusy($start, $end)->calendars[$calendar->id]->busy) {
            //     return [
            //         'errors' => ['Time slot not available!'],
            //     ];
            // }

            if ($event) {
                $event->update([
                    'start' => \Carbon\Carbon::parse($start)->setTimezone(config('app.timezone')),
                    'end' => \Carbon\Carbon::parse($end)->setTimezone(config('app.timezone')),
                ]);
                $json_response = [
                    'message' => 'Event updated successfully',
                ];
                UpdateEventFromGoogleCalendarJob::dispatch($event)->onQueue('events_sync');
            } else {
                $errors = array('Event Not found');
                $json_response = [
                    'errors' => $errors,
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return $json_response;

    }

    /**
     *
     * @param  ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
        Calendar $calendar,
        Event $event,
    ) {
        return $this->handle(
            $calendar,
            $event,
            $request->start,
        );
    }

    public function htmlResponse(array $response)
    {
        return $this->response($response);
    }

    public function jsonResponse(array $response)
    {
        return $this->response($response);
    }

    private function response(array $response)
    {
        return ResponseHelper::getDefaultResponse($response);
    }
}
