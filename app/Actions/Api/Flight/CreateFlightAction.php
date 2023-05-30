<?php

namespace App\Actions\Api\Flight;

use App\Models\Flight;
use App\Models\Payment;
use App\Services\Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateFlightAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'location' => 'required',
            'destination' => 'required',
            'class' => 'required',
            'terms' => 'required',
            'stripeSourceToken' => 'required',

        ];
    }

    public function handle(
        string $name,
        string $email,
        string $phone,
        string $location,
        string $destination,
        string $class,
        string $terms,
        string $stripeSourceToken
    ): array
    {
        DB::beginTransaction();
        try {

            $amount = config('calendar.flight_fee');
            $response = (new Stripe())->StripeCharge($amount, $stripeSourceToken, "Flight Booking - Cloud Tele Service");
            if ($response['status'] == true && $response['response']['status'] == 'succeeded') {

                $flight = Flight::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'location' => $location,
                    'destination' => $destination,
                    'class' => $class,
                    'terms' => $terms,
                ]);

                Payment::firstOrCreate([
                    'flight_id' => $flight->id,
                    'amount' => $amount,
                    'stripeToken' => $response['response']['id'],
                ]);
                $json_response = [
                    'message' => 'Flight request created successfully',
                ];
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

    ) {
        $response = $this->handle(
            $request->name,
            $request->email,
            $request->phone,
            $request->location,
            $request->destination,
            $request->class,
            $request->terms,
            $request->stripeSourceToken,
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
