<?php

use Illuminate\Support\Facades\Request;

if (! function_exists('current_school_sub')) {
    function current_school_sub(): ?string
    {
        $param = optional(Request::route())->parameter('school_sub');
        if ($param) return $param;

        $host = Request::getHost();
        $root = config('app.tenant_root_domain');
        if ($root && str_ends_with($host, '.'.$root)) {
            return substr($host, 0, -1 * (strlen($root) + 1));
        }
        return null;
    }
}

/**
 * Generate a tenant route URL and auto-inject {school_sub}.
 */
if (! function_exists('tenant_route')) {
    function tenant_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $parameters = ['school_sub' => current_school_sub()] + $parameters;
        return route($name, $parameters, $absolute);
    }
}
