<?php

use App\Http\Middleware\ApiLogger;
use App\Http\Middleware\OptionalSanctumAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'optional.sanctum' => OptionalSanctumAuth::class,
        ]);

        $middleware->group('api', [
            ApiLogger::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $e) {
            if (request()->is('api/*')) {
                \App\Models\ApiLog::create([
                    'method' => request()->method(),
                    'url' => request()->path(),
                    'user_id' => Auth::id(),
                    'status_code' => 500,
                    'error_message' => $e->getMessage(),
                ]);
            }
        });
    })->create();
