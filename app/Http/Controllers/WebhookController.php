<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    /**
     * Handle Paystack webhooks.
     */
    public function handlePaystack(Request $request)
    {
        $secret = env('PAYSTACK_SECRET_KEY');
        $signature = $request->header('x-paystack-signature');
        $computedSignature = hash_hmac('sha512', $request->getContent(), $secret);

        if ($signature !== $computedSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payload = $request->all();

        if (!isset($payload['event']) || !isset($payload['data'])) {
            return response()->json(['message' => 'Invalid webhook payload'], 400);
        }

        if ($payload['event'] === 'charge.success') {
            $data = $payload['data'];

            if ($data['status'] !== 'success') {
                return response()->json(['message' => 'Payment not successful'], 400);
            }

            $reference = $data['reference'];
            $metadata = $data['metadata'];

            if (empty($metadata['user_id']) || empty($metadata['plan_id']) || empty($metadata['duration'])) {
                return response()->json(['message' => 'Invalid metadata'], 400);
            }

            $verificationResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secret,
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if (!$verificationResponse->ok() || $verificationResponse['data']['status'] !== 'success') {
                return response()->json(['message' => 'Payment verification failed'], 400);
            }

            try {
                Subscription::create([
                    'user_id' => $metadata['user_id'],
                    'plan_id' => $metadata['plan_id'],
                    'start_date' => now(),
                    'end_date' => now()->addDays($metadata['duration'] === 'monthly' ? 30 : 365),
                    'status' => 'active',
                ]);

                return response()->json(['message' => 'Subscription activated successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to activate subscription'], 500);
            }
        }

        return response()->json(['message' => 'Unhandled event'], 200);
    }



        //     public function handleCallback(Request $request)
        // {
        //     $reference = $request->query('reference');

        //     // Verify the transaction
        //     $verificationResponse = Http::withHeaders([
        //         'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        //     ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        //     if ($verificationResponse->ok() && $verificationResponse['data']['status'] === 'success') {
        //         // Mark the subscription as active
        //         Subscription::create([
        //             'user_id' => auth()->id(),
        //             'plan_id' => $verificationResponse['data']['metadata']['plan_id'],
        //             'start_date' => now(),
        //             'end_date' => now()->addDays($verificationResponse['data']['metadata']['duration'] === 'monthly' ? 30 : 365),
        //             'status' => 'active',
        //         ]);

        //         return redirect('/success'); // Redirect to a success page
        //     }

        //     return redirect('/failed'); // Redirect to a failure page
        // }

}


