<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserMatchesSchool
{
    public function handle(Request $request, Closure $next)
    {
        $user   = Auth::guard('tenant')->user();
        $school = $request->attributes->get('school');
        if ($user && $school && $user->school_id !== $school->id) {
            Auth::guard('tenant')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('tenant.login', ['school_sub' => $school->domain])
                ->withErrors(['domain' => 'Wrong school. Please log in for this subdomain.']);
        }
        return $next($request);
    }
}
