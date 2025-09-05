<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // If we're inside tenant routes
        if ($request->attributes->has('school')) {
            $school = $request->attributes->get('school');
            return route('tenant.login', ['school_sub' => $school->domain]);
        }

        if ($request->route() && $request->route()->named('tenant.*')) {
            return route('tenant.login', ['school_sub' => $request->route('school_sub')]);
        }
        
        return route('global.homepage');
    }
}
