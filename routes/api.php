<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticateController;
use App\Http\Controllers\Api\V1\Uploads\UploadController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Middleware\CheckApiToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/uploads', 'middleware' => ['throttle:5,1', CheckApiToken::class]], function() {
    Route::post("store", [UploadController::class,'store']);
});

Route::group(['prefix' => 'v1/auth', 'middleware' => ['throttle:5,1', CheckApiToken::class] ], function() {
    Route::post('check-user', [AuthenticateController::class, 'checkUser']);
    Route::post('send-otp', [AuthenticateController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthenticateController::class, 'verifyOtp']);
    Route::post('login', [AuthenticateController::class, 'login']);
    Route::post('register', [AuthenticateController::class, 'register']);
    Route::put('complete-profile', [AuthenticateController::class, 'completeProfile'])->middleware('auth:sanctum');
    Route::post('send-otp-email', [AuthenticateController::class, 'sendOtpEmail'])->middleware('auth:sanctum');
    Route::post('verify-email', [AuthenticateController::class, 'verifyEmail'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'v1/user', 'middleware' => ['throttle:5,1', 'auth:sanctum', CheckApiToken::class] ], function() {
    Route::get('show', [UserController::class, 'show']);
    Route::put('update', [UserController::class, 'update']);
    Route::delete('delete', [UserController::class, 'delete']);
});
