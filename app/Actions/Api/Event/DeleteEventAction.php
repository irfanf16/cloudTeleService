<?php

namespace App\Actions\Api\Event;

use App\Models\Event;
use App\Models\Calendar;
use App\Jobs\SyncEventsJob;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Services\Google\GoogleCalendarService;
use App\Jobs\Google\DeleteEventFromGoogleCalendarJob;

class DeleteEventAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            'event_id'=>'required'
        ];
    }

    /**
     * creates event in google calendar
     *
     * @param  Calendar $calendar
     * @param  Event    $event
     * @return array
     */
    public function handle(
        Calendar $calendar,
        Event $event,
        $request
    ): array
    {
        DB::beginTransaction();
        try {
            $event->update(['status' => config('calendar.event.statuses')[2]]);
            $json_response = [
                'message' => 'Event deleted successfully',
            ];
            DeleteEventFromGoogleCalendarJob::dispatch($event)->onQueue('events_sync');
        } catch (\Exception$e) {
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
            $request
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
