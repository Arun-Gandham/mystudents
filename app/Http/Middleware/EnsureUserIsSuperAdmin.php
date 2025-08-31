<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('superadmin')->user();

        // Adjust to your actual role/flag logic, e.g. $user->is_superadmin or $user->hasRole('superadmin')
        if (!$user || !$user->is_superadmin) {
            Auth::guard('superadmin')->logout();
            return redirect()->route('superadmin.login')
                ->withErrors(['auth' => 'Not authorized.']);
        }

        return $next($request);
    }
}
