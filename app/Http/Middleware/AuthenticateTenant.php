<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AuthenticateTenant extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) return null;

        $root = config('app.tenant_root_domain', 'pocketschool.test');
        $host = $request->getHost();
        $schoolSub = $request->route('school_sub');

        if (!$schoolSub && $root && str_ends_with($host, '.'.$root)) {
            $schoolSub = substr($host, 0, -1 * (strlen($root) + 1));
        }

        // Send tenants to their tenant login
        return route('tenant.login', ['school_sub' => $schoolSub ?: '']);
    }
}
