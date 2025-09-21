<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $school = $request->attributes->get('school');
        $enabled = $school->enabled_modules ?? [];

        // If no selection saved (null/empty), treat as all modules enabled
        if (empty($enabled) || !is_array($enabled)) {
            return $next($request);
        }

        if (!in_array($module, $enabled, true)) {
            abort(404);
        }

        return $next($request);
    }
}

