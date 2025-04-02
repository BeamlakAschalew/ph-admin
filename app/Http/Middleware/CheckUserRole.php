<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles  Role(s) to check for
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles) {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // If no roles are passed, continue
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
