<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'consumer.authenticated' => \App\Http\Middleware\ConsumerAuthenticated::class,
            'consumer.guest' => \App\Http\Middleware\ConsumerGuest::class,
            'admin.authenticated' => \App\Http\Middleware\AdminAuthenticated::class,
            'admin.guest' => \App\Http\Middleware\AdminGuest::class
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (Throwable $e) {
            $key = get_class($e);
            \Illuminate\Support\Facades\Log::error($key, [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ]);
        });
    })->create();
