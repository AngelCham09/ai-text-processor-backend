<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Public API (IP-based)
        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(20)
                ->by($request->ip());
        });

        // Authenticated users (user-based)
        RateLimiter::for('user-api', function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip());
        });

        // Login & register (anti brute-force)
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip());
        });

        RateLimiter::for('process-text', function ($request) {
            if ($user = $request->user()) {
                return Limit::perHour(100)->by($user->id);
            }

            return Limit::perHour(20)->by($request->ip());
        });
    }
}
