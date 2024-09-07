<?php

use App\Http\Controllers\API\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\VisaTypeController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\RequirementChecklistController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('destinations', DestinationController::class);
    Route::apiResource('visa-types', VisaTypeController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index'])->middleware('auth:api');

    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::put('/applications/{application}', [ApplicationController::class, 'update']);
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']);
});
Route::post('/applications', [ApplicationController::class, 'store']);
Route::post('applications', [ApplicationController::class, 'store']);
Route::get('paypal/success', [ApplicationController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('paypal/cancel', [ApplicationController::class, 'paypalCancel'])->name('paypal.cancel');
