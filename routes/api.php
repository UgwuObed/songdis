<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicUploadController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\MailTestController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware(['api', EnsureFrontendRequestsAreStateful::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});



Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF Cookie Set']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // music route
    Route::post('/upload-music', [MusicUploadController::class, 'store']);
    Route::get('/music', [MusicUploadController::class, 'index']);
    Route::get('/albums/{releaseTitle}/tracks', [MusicUploadController::class, 'fetchAlbumWithTracks']);
    Route::get('/albums-ep', [MusicUploadController::class, 'fetchAllAlbumsWithTracks']);


    // Subscription routes
    Route::get('/plans', [SubscriptionController::class, 'getPlans']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('/status', [SubscriptionController::class, 'checkSubscription']);
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription']);


    //Profile routes
    Route::post('/create-profile', [ProfileController::class, 'create']);
    Route::get('/profile', [ProfileController::class, 'show']);



});

    // Payment callback route
    Route::get('/payment/callback', [SubscriptionController::class, 'handleGatewayCallback'])
        ->name('payment.callback');

        Route::apiResource('email-templates', EmailTemplateController::class);

        Route::post('/test-email', [MailTestController::class, 'testBasicEmail']);