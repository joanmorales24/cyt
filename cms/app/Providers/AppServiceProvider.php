<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Desarrollo: 20 intentos | Producción: 3 por IP cada 15 min
        RateLimiter::for('leads', function (Request $request) {
            $max = app()->isProduction() ? 3 : 20;
            return Limit::perMinutes(15, $max)->by($request->ip());
        });
    }
}
