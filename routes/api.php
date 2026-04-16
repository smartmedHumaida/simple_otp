<?php

use App\Http\Controllers\OtpController;
use App\Http\Controllers\WhatsAppOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Twilio\Rest\Client;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/otp/send', [OtpController::class, 'send']);
Route::post('/otp/verify', [OtpController::class, 'verify']);

