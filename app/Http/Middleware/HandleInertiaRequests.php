<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware {
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        $uri = $request->route()->uri;
        $guard = str_contains($uri, 'admin') ? 'admin' : (str_contains($uri, 'supplier') ? 'supplier' : 'consumer');
        $user = $request->user($guard);
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'user_role' => $guard === 'admin' ? ($request->user() ? $request->user()->getRoleNames()?->first() : null) : null,
            ],
            'flash' => [
                'message' => $request->session()->get('message'),
            ],
        ];
    }
}
