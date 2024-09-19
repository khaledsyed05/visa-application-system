<?php

use App\Http\Controllers\API\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\VisaTypeController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\VisaFieldRequirementController;

Route::post('login', [AuthController::class, 'login']);

Route::prefix('admin')->middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/destinations')->group(function () {
        Route::get('/', [DestinationController::class, 'index']);
        Route::post('/', [DestinationController::class, 'store']);
        Route::get('/{destination}', [DestinationController::class, 'show']);
        Route::put('/{destination}', [DestinationController::class, 'update']);
        Route::delete('/{destination}', [DestinationController::class, 'destroy']);
    });

    Route::apiResource('/visa-types', VisaTypeController::class);

    Route::get('/analytics', [AnalyticsController::class, 'index']);

    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::put('/applications/{application}', [ApplicationController::class, 'update']);
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']);
    Route::post('/visa-requirements/toggle', [VisaFieldRequirementController::class, 'toggle']);
    Route::get('/visa-requirements', [VisaFieldRequirementController::class, 'getRequirements']);
});

Route::get('/destinations', [DestinationController::class, 'index']);
Route::post('applications', [ApplicationController::class, 'store']);
Route::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
Route::post('/paypal/capture-payment', [PayPalController::class, 'capturePayment'])->name('paypal.capture');
Route::post('/paypal/cancel-payment', [PayPalController::class, 'cancelPayment'])->name('paypal.cancel');
