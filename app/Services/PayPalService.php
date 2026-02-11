<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayPalService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $secret;

    public function __construct()
    {
        $this->baseUrl = config('services.paypal.base_url');
        $this->clientId = config('services.paypal.client_id');
        $this->secret = config('services.paypal.secret');
    }

    protected function getAccessToken()
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->secret)
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        return response()->json(['access_token']);
    }

    public function createOrder($amount)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post($this->baseUrl . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amount
                    ]
                ]]
            ]);

        return response()->json();
    }

    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();

        // return Http::withToken($accessToken)
        //     ->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture")
        //     ->json();
    }
}
