<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['message' => 'CSRF cookie generated']);
});

Route::get('/test-upload', function () {
    $testFile = 'uploads/test.txt';
    Storage::disk('s3')->put($testFile, 'This is a test file');
    \Log::info('Test file path', ['path' => Storage::disk('s3')->url($testFile)]);
    return 'Test file uploaded successfully!';
});
