<?php

namespace App\Http\Controllers\Paypal;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PayPalController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
        // dd($this->provider->getAccessToken());
    }

    /**
     * Create a PayPal order and redirect to approval
     */
    public function create(Order $order)
    {
        // Validate order belongs to user
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            abort(403);
        }

        $total = $order->total_amount;

        $response = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', ['order' => $order->id]),
                "cancel_url" => route('paypal.cancel', ['order' => $order->id]),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => config('paypal.currency'),
                        "value" => number_format($total, 2, '.', ''),
                        // "value" => number_format($total, 2),
                    ]
                ]
            ]
        ]);

        // dd($response);

        if (isset($response['id']) && isset($response['links'])) {
            // Find approval URL
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect($link['href']);
                }
            }
        }

        return redirect()->route('checkout.index')
            ->with('error', 'Unable to process PayPal payment.');
    }

    /**
     * PayPal success callback
     */
    public function success(Request $request, Order $order)
    {
        // Validate user + pending
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            abort(403);
        }

        // Capture payment
        $response = $this->provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && strtoupper($response['status']) === 'COMPLETED') {

            // Mark order as paid
            $order->update([
                'status' => 'paid'
            ]);

            // Generate tickets, send emails, etc…
            // Your logic here

            return redirect()->route('checkout.index')
                ->with('success', 'Payment successful, your tickets are issued!');
        }

        return redirect()->route('checkout.index')
            ->with('error', 'Payment not completed.');
    }

    /**
     * Cancel payment
     */
    public function cancel(Order $order)
    {
        return redirect()->route('checkout.index')
            ->with('error', 'Payment cancelled.');
    }
}
