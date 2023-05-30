<?php

namespace App\Services\Stripe;

class Stripe
{

    public function stripeTokenCreate(
        string $card_number = null,
        string $exp_month = null,
        string $exp_year = null,
        string $cvc = null
    ) {
        \Stripe\Stripe::setApiKey(config('stripe.secret'));
        $response = \Stripe\Token::create(array(
            "card" => array(
//                "number"    =>$card_number,
//                "exp_month" => $exp_month,
//                "exp_year"  => $exp_year,
//                "cvc"       => $cvc
                "number" => 4242424242424242,
                "exp_month" => 9,
                "exp_year" => 2026,
                "cvc" => 100,
            )));
        return $response;
    }

    public function StripeCharge(
        string $amount,
        string $stripeSourceToken,
        string $description,
    ) {
        try {
            \Stripe\Stripe::setApiKey(config('stripe.secret'));
            $payment = \Stripe\Charge::create([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $stripeSourceToken,
                "description" => $description,
            ]);
            $res = [
                'status' => true,
                'response' => $payment,
            ];
            return $res;
        } catch (\Stripe\Exception\CardException $e) {
            $res = [
                'status' => false,
                'response' => $e->getError()->message,
            ];
            return $res;
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            $res = [
                'status' => false,
                'response' => $e->getError()->message,
            ];
            return $res;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            $res = [
                'status' => false,
                'response' => $e->getError()->message,
            ];
            return $res;
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $res = [
                'status' => false,
                'response' => $e->getError()->message,
            ];
            return $res;
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            $res = [
                'status' => false,
                'response' => $e->getError()->message,
            ];
            return $res;
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $res = [
                'status' => false,
                'response' => $e->getError()->message,
            ];
            return $res;
        }
    }

}
