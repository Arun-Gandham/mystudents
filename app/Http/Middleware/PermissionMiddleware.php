<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // ✅ get admin role from config
        $superAdminRole = config('auth.school_admin_role');

        // ✅ if user has that role, bypass everything
        if ($user->hasRole($superAdminRole)) {
            return $next($request);
        }

        // ✅ Otherwise check specific permission
        if (!$user->hasPermission($permission)) {
            abort(403, 'Unauthorized - Missing permission: '.$permission);
        }

        return $next($request);
    }
}
