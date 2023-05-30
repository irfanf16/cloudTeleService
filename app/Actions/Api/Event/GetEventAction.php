<?php

namespace App\Actions\Api\Event;

use App\Models\Event;
use App\Models\Calendar;
use App\Services\Google\GoogleCalendarService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEventAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
        ];
    }

    /**
     * handle
     *
     * @param  Calendar $calendar
     * @param  Event    $event
     * @return array
     */
    public function handle(
        Calendar $calendar,
        Event $event
    ): array
    {
        DB::beginTransaction();
        try {
            if ($event->status === config('calendar.event.statuses')[2]) {
                return [
                    'errors' => ['Event not found!'],
                ];
            }
            if (\Carbon\Carbon::parse($event->start)->setTimezone(config('app.timezone'))->isPast()) {
                return [
                    'errors' => ['Token expired, event can not be updated'],
                ];
            }
            $event = $event->load('guests');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return $event->toArray();
    }

    /**
     *
     * @param  ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
        Calendar $calendar,
        Event $event
    ) {
        return $this->handle(
            $calendar,
            $event
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
