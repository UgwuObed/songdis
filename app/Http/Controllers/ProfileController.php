<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Create a new profile for the authenticated user based on their subscription plan.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
            
    $subscription = $user->subscriptions()->where('status', 'active')->latest()->first();

        if ($subscription && $subscription->status === 'active') {
            $plan = $subscription->plan;
            $profileCount = Profile::where('user_id', $user->id)->count();

            \Log::info('Subscription details:', ['subscription' => $subscription]);

            if (
                ($plan->id == 1 && $profileCount >= 1) ||
                ($plan->id == 2 && $profileCount >= 3) ||
                ($plan->id == 3)
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have reached your profile limit for this plan.',
                ], 400);
            }

            $request->validate([
                'full_name' => 'required|string',
                'stage_name' => 'required|string',
                'dob' => 'required|date',
                'twitter_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'facebook_url' => 'nullable|url',
                'spotify_url' => 'nullable|url',
                'apple_music_url' => 'nullable|url',
            ]);


            $profile = Profile::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'stage_name' => $request->stage_name,
                'dob' => $request->dob,
                'twitter_url' => $request->twitter_url,
                'instagram_url' => $request->instagram_url,
                'facebook_url' => $request->facebook_url,
                'spotify_url' => $request->spotify_url,
                'apple_music_url' => $request->apple_music_url,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile created successfully!',
                'profile' => $profile
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Your subscription plan is inactive or invalid.'
        ], 400);
    }


    public function show()
   {
    
    $user = Auth::user();

   
    $subscription = $user->subscriptions()->where('status', 'active')->latest()->first();

    
    if ($subscription && $subscription->status === 'active') {
       
        $profile = Profile::where('user_id', $user->id)->first();

  
        if ($profile) {
            return response()->json([
                'status' => 'success',
                'profile' => $profile
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No profile found for the authenticated user.'
        ], 404);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Your subscription plan is inactive or invalid.'
    ], 400);
}

}
