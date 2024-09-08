<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    private PayPalClient $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    /**
     * Create PayPal order for a one-time payment.
     *
     */
    public function createOrder()
    {
        $response = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.capture'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "9.99"
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            return response()->json([
                'order_id' => $response['id'],
                'approve_link' => collect($response['links'])->firstWhere('rel', 'approve')['href']
            ]);
        }

        return response()->json(['error' => $response['message'] ?? 'Something went wrong.'], 500);
    }

    /**
     * Capture PayPal payment after approval.
     *
     * @param Request $request
     */
    public function capturePayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $response = $this->provider->capturePaymentOrder($orderId);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // Payment successful, you can update your database here
            return response()->json(['message' => 'Payment successful', 'transaction_id' => $response['id']]);
        }

        return response()->json(['error' => $response['message'] ?? 'Payment failed.'], 400);
    }

    /**
     * Handle cancelled payment.
     *
     */
    public function cancelPayment()
    {
        return response()->json(['message' => 'Payment cancelled.'], 400);
    }
}