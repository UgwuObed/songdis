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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\AccountDetailController;
use App\Http\Controllers\BulkEmailController;
use App\Http\Controllers\EmailTemplateController;
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
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
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
    Route::post('/redeem-promo', [SubscriptionController::class, 'redeemPromoCode']);
    Route::get('/promo-codes', [PromoCodeController::class, 'index']);
    Route::post('/promo-codes/generate', [PromoCodeController::class, 'generate']);
    Route::post('/promo-codes/{id}/toggle', [PromoCodeController::class, 'toggleStatus']);
    Route::put('/promo-codes/{id}', [PromoCodeController::class, 'update']);
    Route::get('/promo-codes/{id}/stats', [PromoCodeController::class, 'getStats']);


    //Profile routes
    Route::post('/create-profile', [ProfileController::class, 'create']);
    Route::get('/profile', [ProfileController::class, 'show']);

   
    // admin route
    Route::get('/admin/users', [AuthController::class, 'fetchAllUsers']);
    Route::get('/admin/singles', [AdminController::class, 'fetchAllSingles']);
    Route::get('/admin/albums', [AdminController::class, 'fetchAllAlbums']);
    Route::get('/admin/single/{id}', [AdminController::class, 'fetchSingleById']);
    Route::get('/admin/album/{id}', [AdminController::class, 'fetchAlbumById']);
    Route::put('/admin/music/{id}/status', [AdminController::class, 'updateStatus']);
    

    // account routes
    Route::get('/account-details', [AccountDetailController::class, 'show']);
    Route::post('/account-details', [AccountDetailController::class, 'store']);
    Route::put('/account-details/{accountDetail}', [AccountDetailController::class, 'update']);
    Route::delete('/account-details/{accountDetail}', [AccountDetailController::class, 'destroy']);


    // email routes
    Route::post('/send-bulk', [BulkEmailController::class, 'sendBulkWelcomeEmails']);
    Route::post('/send-welcome-to-all', [BulkEmailController::class, 'sendWelcomeEmailToAllUsers']);
    Route::get('/email', [BulkEmailController::class, 'listEmailTemplates']);

});

    // Payment callback route
    Route::get('/payment/callback', [SubscriptionController::class, 'handleGatewayCallback'])
        ->name('payment.callback');

        Route::apiResource('email-templates', EmailTemplateController::class);

        Route::post('/test-email', [MailTestController::class, 'testBasicEmail']);
