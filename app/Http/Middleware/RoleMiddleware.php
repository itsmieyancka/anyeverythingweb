<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access - Not logged in');
        }

        if (!Auth::user()->hasRole($role)) {
            abort(403, 'Unauthorized access - Incorrect role');
        }

        return $next($request);
    }
}

