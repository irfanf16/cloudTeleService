<?php

namespace App\Actions\Api\Event;

use App\Models\Event;
use App\Models\Calendar;
use App\Services\Google\GoogleCalendarService;
use App\Services\Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class GetStripeTokenAction
{
    use AsAction;


    public function handle()
    {
        $response = (new Stripe())->stripeTokenCreate();
        $json_response = [
            'stripeToken' => $response
        ];
        return $json_response;
    }

    /**
     *
     * @param ActionRequest $request
     * @return mixed
     */
    public function asController()
    {
        return $this->handle();
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
