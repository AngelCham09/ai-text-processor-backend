<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\UserController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);
    Route::post('/forgot-password', [\App\Http\Controllers\Api\ForgotPasswordController::class, 'sendResetLink']);
    Route::post('/reset-password', [\App\Http\Controllers\Api\ForgotPasswordController::class, 'resetPassword']);
});

Route::middleware(['optional.sanctum', 'throttle:process-text'])->group(function () {
    Route::post('/process-text', [\App\Http\Controllers\Api\TextProcessorController::class, 'process']);
});

Route::middleware(['auth:sanctum', 'throttle:user-api'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);
    Route::get('/profile', [\App\Http\Controllers\Api\UserController::class, 'profile']);
    Route::get('/history', [\App\Http\Controllers\Api\TextProcessorController::class, 'history']);
});

Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Api\EmailVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);

Route::post('/email/resend', [\App\Http\Controllers\Api\EmailVerificationController::class, 'resend'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);


