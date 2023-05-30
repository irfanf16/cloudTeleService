<?php

namespace App\Actions\Api\Event;

use App\Models\Event;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllEventsSlotAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'timezone' => 'required',
        ];
    }

    public function handle(
        $date,
        $timezone
    ) {
        try {
            $time_slots = config('calendar.working_slots');
            $date = date('y-m-d', strtotime($date));
            $available_slots_of_day = [];
            foreach ($time_slots as $slot) {
                $slot_time = $date . ' ' . $slot;
                if (!Event::where('start', $slot_time)->exists()) {
                    $format_slot = \Carbon\Carbon::parse($slot_time)->setTimezone($timezone);
                    if (!$format_slot->isPast()) {
                        $slot_date = $format_slot->format('m/d/y');
                        $slot_hour = $format_slot->format('H:i:s');
                        array_push($available_slots_of_day, ['date' => $slot_date, 'time' => $slot_hour]);
                    }
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return ['event_slots' => $available_slots_of_day];
    }

    /**
     *
     * @param  ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
    ) {
        return $this->handle(
            $request->date,
            $request->timezone
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
