<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'superadmin':
                        return redirect()->route('superadmin.dashboard');
                    case 'tenant':
                        return redirect()->route('tenant.dashboard', [
                            'school_sub' => request()->route('school_sub') ?? request()->attributes->get('school')->domain ?? ''
                        ]);
                    default: // web fallback
                        return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}
