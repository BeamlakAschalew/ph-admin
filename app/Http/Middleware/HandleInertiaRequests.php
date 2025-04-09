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
        $user = $request->user();
        $role = null;

        if ($user) {
            // Check roles based on the guard
            if (auth()->guard('admin')->check()) {
                $role = $user->getRoleNames()?->first();
            } elseif (auth()->guard('consumer')->check()) {
                $role = $user->getRoleNames()?->first();
            } elseif (auth()->guard('supplier')->check()) {
                $role = $user->getRoleNames()?->first();
            }
        }
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'user_role' => $request->user() ? $request->user()->getRoleNames()?->first() : null,
                'user_type' => $role
            ],
            'flash' => [
                'message' => $request->session()->get('message'),
            ],
        ];
    }
}
