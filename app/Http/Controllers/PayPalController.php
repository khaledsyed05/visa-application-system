<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function createOrder(Request $request)
    {
        $applicationId = $request->input('application_id');
        $application = Application::findOrFail($applicationId);

        // Fetch the price from visa_types_destinations table
        $priceInfo = DB::table('visa_types_destinations')
            ->where('visa_type_id', $application->visa_type_id)
            ->where('destination_id', $application->destination_id)
            ->first();

        if (!$priceInfo) {
            return response()->json(['error' => 'Price not found for this visa type and destination'], 400);
        }
        $price = $priceInfo->cost;

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
                        "value" => number_format($price, 2, '.', '')
                    ],
                    "reference_id" => $application->id,
                    "custom_id" => $application->id
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            $application->paypal_order_id = $response['id'];
            $application->save();

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
     */
    public function capturePayment(Request $request)
    {
        $paypalOrderId = $request->input('order_id');
        $response = $this->provider->capturePaymentOrder($paypalOrderId);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // Get the application ID from the custom_id in the PayPal response
            $applicationId = $response['purchase_units'][0]['payments']['captures'][0]['custom_id'] ?? null;

            if (!$applicationId) {
                return response()->json(['error' => 'Application ID not found in PayPal response.'], 400);
            }

            // Find the application in your database
            $application = Application::findOrFail($applicationId);

            // Verify that the PayPal Order ID matches
            if ($application->paypal_order_id !== $paypalOrderId) {
                return response()->json(['error' => 'PayPal Order ID mismatch.'], 400);
            }

            // Update the application status
            $application->status = 'paid';
            $application->payment_id = $response['id'];
            $application->save();

            // You might want to trigger other actions here, like sending an email

            return response()->json([
                'message' => 'Payment successful',
                'transaction_id' => $response['id'],
                'application_id' => $application->id
            ]);
        }

        return response()->json(['error' => $response['message'] ?? 'Payment failed.'], 400);
    }
    /**
     * Handle cancelled payment.
     */
    public function cancelPayment(Request $request)
    {
        $paypalOrderId = $request->input('order_id');

        // Find the application using the PayPal Order ID
        $application = Application::where('paypal_order_id', $paypalOrderId)->first();

        if ($application) {
            $application->status = 'payment_cancelled';
            $application->save();

            return response()->json(['message' => 'Payment cancelled.'], 400);
        }

        return response()->json(['error' => 'Application not found.'], 404);
    }
}
