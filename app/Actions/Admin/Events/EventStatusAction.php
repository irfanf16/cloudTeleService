<?php

namespace App\Actions\Admin\Events;

use App\Models\Contact;
use App\Models\Event;
use App\Jobs\SyncEventsJob;
use App\Models\Calendar;
use App\Models\EventAttendee;
use App\Models\Payment;
use App\Services\Stripe\Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Services\Google\GoogleCalendarService;

class EventStatusAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required',
            'status' => 'required',
        ];
    }


    public function handle(
        string $id,
        string $status,

    ): array
    {
        DB::beginTransaction();
        try {
            Event::where('id',$id)->update([
               'status'=>$status
            ]);
        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return [
            'success' => true,
        ];

    }

    /**
     *
     * @param  ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
    ) {
        $response = $this->handle(

            $request->id,
            $request->status,
        );
        return $response;
    }

    public function htmlResponse(array $response)
    {
        return redirect()->back();
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
