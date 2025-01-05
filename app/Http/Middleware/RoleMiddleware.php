<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check if the user has any of the specified roles
        foreach ($roles as $role) {
            if ($user->roles()->where('role_name', $role)->exists()) {
                return $next($request);
            }
        }

        return redirect('home')->with('error', 'You do not have access to this page.');
    }
}
