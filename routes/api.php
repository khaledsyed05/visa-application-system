<?php

use App\Http\Controllers\API\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\VisaTypeController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\PayPalController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('destinations', DestinationController::class);
    Route::apiResource('visa-types', VisaTypeController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index']);

    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::put('/applications/{application}', [ApplicationController::class, 'update']);
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']);
});
Route::post('applications', [ApplicationController::class, 'store']);

Route::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
Route::post('/paypal/capture-payment', [PayPalController::class, 'capturePayment'])->name('paypal.capture');
Route::post('/paypal/cancel-payment', [PayPalController::class, 'cancelPayment'])->name('paypal.cancel');
