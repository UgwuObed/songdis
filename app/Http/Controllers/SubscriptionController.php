<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\PromoCode;
use App\Models\PromoCodeUse;
use Illuminate\Http\Request;
use Paystack;
use GuzzleHttp\Client;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * List all available plans.
     */
    public function getPlans()
    {
        $plans = Plan::all();
        return response()->json($plans, 200);
    }

    /**
     * Subscribe a user to a plan.
     */
    public function subscribe(Request $request)
    {
        // \Log::info('Subscribe endpoint hit', ['plan_id' => $request->plan_id]);
        
        try {
            $request->validate([
                'plan_id' => 'required|exists:plans,id',
            ]);
    
            $user = auth()->user();
            $plan = Plan::findOrFail($request->plan_id);
    
            $data = [
                "amount" => $plan->price * 100,
                "email" => $user->email,
                "reference" => Paystack::genTranxRef(),
                "callback_url" => route('payment.callback'),
                "metadata" => [
                    "plan_id" => $plan->id,
                    "user_id" => $user->id,
                ]
            ];
    
           
    
            $client = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . config('paystack.secretKey'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
    

            $response = $client->post('https://api.paystack.co/transaction/initialize', [
                'json' => $data
            ]);
    
            $result = json_decode($response->getBody()->getContents(), true);
    
            if (!empty($result['data']['authorization_url'])) {
                return response()->json([
                    'status' => 'success',
                    'redirect_url' => $result['data']['authorization_url']
                ]);
            }
    
            throw new \Exception('Invalid response from Paystack');
    
        } catch (\Exception $e) {
            \Log::error('Paystack subscription error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Payment initialization failed: ' . $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Handle Paystack payment callback.
     */
    public function handleGatewayCallback(Request $request)
    {
        \Log::info('Payment callback received', ['request' => $request->all()]);
    
        try {
            $paymentDetails = Paystack::getPaymentData();
            \Log::info('Paystack payment details', ['details' => $paymentDetails]);
    
            if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {
                $metadata = $paymentDetails['data']['metadata'];
                
                $user = User::find($metadata['user_id']);
                $plan = Plan::find($metadata['plan_id']);
    
                if (!$user || !$plan) {
                    return redirect(env('FRONTEND_URL') . '/home/status?status=error&message=Invalid+user+or+plan');
                }
    
                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'start_date' => now(),
                    'end_date' => $plan->duration === 'monthly' ? now()->addMonth() : now()->addYear(),
                    'status' => 'active',
                    'subscription_code' => $paymentDetails['data']['reference'],
                    'payment_reference' => $paymentDetails['data']['reference'],
                    'amount_paid' => $paymentDetails['data']['amount'] / 100,
                ]);
    
    
                return redirect(env('FRONTEND_URL') . '/home/status?status=success&message=Payment+successful');

            }
    
            return redirect(env('FRONTEND_URL') . '/home/status?status=error&message=Payment+failed');
    
        } catch (\Exception $e) {
            \Log::error('Payment callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect(env('FRONTEND_URL') . '/home/status?status=error&message=Payment+failed');
        }
    }
    

    private function handleFailedPayment($message)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], 400);
    }
    /**
     * Check active subscription status for the authenticated user.
     */
    public function checkSubscription()
    {
        $user = auth()->user();

        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        $activePromoUse = $user->promoCodeUses()
            ->where('expires_at', '>=', now())
            ->first();

        if (!$activeSubscription && !$activePromoUse) {
            return response()->json(['message' => 'No active subscription.'], 404);
        }

        return response()->json([
            'subscription' => $activeSubscription ?? [
                'type' => 'promo',
                'expires_at' => $activePromoUse->expires_at
            ],
        ], 200);
    }
        /**
     * Cancel a subscription.
     */
    public function cancelSubscription()
    {
        $user = auth()->user();

        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->first();

        if (!$activeSubscription) {
            return response()->json(['message' => 'No active subscription to cancel.'], 404);
        }

        $activeSubscription->update(['status' => 'canceled']);

        return response()->json(['message' => 'Subscription canceled successfully.'], 200);
    }


    public function redeemPromoCode(Request $request)
{
    $request->validate([
        'code' => 'required|string'
    ]);

    try {
      
        $exists = PromoCode::where('code', $request->code)->exists();
        
        $promoCode = PromoCode::where('code', $request->code)
            ->where('is_active', true)
            ->firstOrFail();

        $isValid = $promoCode->isValid();
        \Log::info('Code validity check:', [
            'is_valid' => $isValid,
            'times_used' => $promoCode->times_used,
            'max_uses' => $promoCode->max_uses
        ]);

        if (!$isValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'This promo code is no longer valid.'
            ], 400);
        }

        $user = auth()->user();
      
        if ($user->promoCodeUses()->where('promo_code_id', $promoCode->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already used this promo code.'
            ], 400);
        }


        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => Plan::where('name', 'Basic')->first()->id,
            'start_date' => now(),
            'end_date' => now()->addDays($promoCode->duration_days),
            'status' => 'active',
            'subscription_code' => 'PROMO-' . strtoupper(uniqid()),
            'payment_reference' => 'PROMO-' . $promoCode->code,
            'amount_paid' => 0,
        ]);

     
        PromoCodeUse::create([
            'user_id' => $user->id,
            'promo_code_id' => $promoCode->id,
            'expires_at' => now()->addDays($promoCode->duration_days)
        ]);

        // Increment times used
        $promoCode->increment('times_used');


        return response()->json([
            'status' => 'success',
            'message' => 'Promo code applied successfully',
            'subscription' => $subscription,
            'expires_at' => now()->addDays($promoCode->duration_days)
        ]);

    } catch (\Exception $e) {
        \Log::error('Promo code redemption error:', [
            'error' => $e->getMessage(),
            'code' => $request->code,
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid promo code: ' . $e->getMessage()
        ], 400);
    }
}

}
