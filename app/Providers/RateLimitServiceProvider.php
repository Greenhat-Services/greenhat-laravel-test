<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Modules\User\Models\User;

class RateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Standard 'api' limiter (60 req/min per user or IP)
        RateLimiter::for('api', function (Request $request) {
            $key = $request->user()?->id ?? $request->ip();
            return [ Limit::perMinute(60)->by($key) ];
        });

        // Custom limiter to satisfy 'throttle:Modules\User\Models\User::api'
        RateLimiter::for(User::class . '::api', function (Request $request) {
            $key = $request->user()?->id ?? $request->ip();
            return [ Limit::perMinute(60)->by($key) ];
        });
    }
}
