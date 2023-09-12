<?php

use App\Http\Controllers\V1\{
    AuthController,
    ForgotPasswordController,
    ResetPasswordController
};

// User Registration
Route::post('register', [AuthController::class, 'register']);

// User Login
Route::post('login',  [AuthController::class, 'login']);

// Password Reset - Request Reset Link
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);

// Password Reset - Reset Password
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

// Password Change (Protected Route)
Route::middleware('auth:api')->group(function () {
    Route::post('password/change', [AuthController::class, 'changePassword']);
    Route::get('checkauth',[AuthController::class,'checkAuth']);
});
