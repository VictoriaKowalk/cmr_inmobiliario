<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for(
            'ingreso-administracion',
            fn (Request $request) => Limit::perMinute(5)->by(
                Str::lower((string) $request->input('email')).'|'.$request->ip()
            )->response(fn () => back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Realizaste demasiados intentos. Esperá un minuto y volvé a probar.',
                ]))
        );

        RateLimiter::for(
            'formularios-publicos',
            fn (Request $request) => Limit::perMinute(5)->by($request->ip())
        );
    }
}
