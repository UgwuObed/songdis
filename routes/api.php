<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicUploadController;
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

    //upload music route
    Route::post('/upload-music', [MusicUploadController::class, 'store']);

});

