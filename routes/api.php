<?php

use Illuminate\Support\Facades\Route;

Route::post('/process-text', [\App\Http\Controllers\Api\TextProcessorController::class, 'process']);

Route::post('/register', [\App\Http\Controllers\Api\UserController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);
    Route::get('/profile', [\App\Http\Controllers\Api\UserController::class, 'profile']);
    Route::get('/history', [\App\Http\Controllers\Api\TextProcessorController::class, 'history']);
});
