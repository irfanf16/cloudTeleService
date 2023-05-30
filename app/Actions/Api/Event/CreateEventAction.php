<?php

namespace App\Actions\Api\Event;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Calendar;
use App\Jobs\SyncEventsJob;
use App\Models\EventAttendee;
use App\Services\Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Services\Google\GoogleCalendarService;
use App\Jobs\Google\AddEventsToGoogleCalendarJob;

class CreateEventAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'required',
            'start' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'services' => 'required',
        ];
    }

    public function handle(
        Calendar $calendar,
        string $description,
        string $start,
        string $stripeSourceToken,
        string $fname,
        string $lname,
        string $email,
        string $phone,
        string $services,

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

            $amount = config('calendar.booking_fee');
            $response = (new Stripe())->StripeCharge($amount, $stripeSourceToken, "Event Booking - Cloud Tele Service");
            if ($response['status'] == true && $response['response']['status'] == 'succeeded') {
                $calendar = Calendar::first();
                $end = \Carbon\Carbon::parse($start)->addMinutes(config('calendar.event.interval'))->toIso8601String();
                $event = Event::create([
                    'calendar_id' => $calendar->id,
                    'description' => $description,
                    'services' => $services,
                    'summary' => 'Appointment booking from ' . $email,
                    'start' => \Carbon\Carbon::parse($start)->setTimezone(config('app.timezone')),
                    'end' => \Carbon\Carbon::parse($end)->setTimezone(config('app.timezone')),
                    'timezone' => config('app.timezone'),
                    'status' => config('calendar.event.statuses')[3],
                ]);
                $attendee = EventAttendee::firstOrCreate([
                    'email' => $email,
                    'fname' => $fname,
                    'lname' => $lname,
                    'phone' => $phone,
                ]);
                $event->attendees()->attach($attendee->id);

                Payment::firstOrCreate([
                    'event_id' => $event->id,
                    'amount' => $amount,
                    'stripeToken' => $response['response']['id'],
                ]);
                $json_response = [
                    'message' => 'Event created successfully',
                ];
                AddEventsToGoogleCalendarJob::dispatch($event)->onQueue('events_sync');
            } else {
                $errors = array($response['response']);
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
        Calendar $calendar
    ) {
        $response = $this->handle(
            $calendar,
            $request->description,
            $request->start,
            $request->stripeSourceToken,
            $request->fname,
            $request->lname,
            $request->email,
            $request->phone,
            $request->services,

        );
        return $response;
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
