<?php

namespace App\Actions\Api\Event;

use App\Models\Event;
use App\Models\Calendar;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllEventsAction
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
     * @return array
     */
    public function handle(Calendar $calendar): array
    {
        DB::beginTransaction();
        try {
//            $events = $calendar->confirmedEvents()->with('guests')->get();
            $events=Event::with('attendees')->where('status',config('calendar.event.statuses')[3])->get()->toArray();
        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return ['events_list'=>$events];
    }

    /**
     *
     * @param  ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
        Calendar $calendar
    ) {
        return $this->handle(
            $calendar
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
