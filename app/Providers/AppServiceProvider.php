<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            env('MAINTENANCE', true) &&
            ! (
                request()->is('sys/env/add') &&
                request()->method() === 'GET'
            )
        ) {
            abort(503, 'Service temporarily unavailable due to maintenance.');
        }
        Vite::prefetch(concurrency: 3);
    }
}
