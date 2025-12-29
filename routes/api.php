<?php

use App\Http\Controllers\Api\TextProcessorController;
use Illuminate\Support\Facades\Route;

Route::post('/process-text', [TextProcessorController::class, 'process']);
Route::get('/history', [TextProcessorController::class, 'history']);
