<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class MenuService
{
    public static function getMenu(): array
    {
        $menu = config('menu');
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        // permissions from cache/session (already preloaded at login)
        $permissions = session('auth_permissions', []);
        $roles = $user->roles->pluck('name')->toArray();

        // Enabled modules from session (set on login/resolve middleware) to avoid extra lookups
        $enabled = session('tenant_enabled_modules');
        if (!is_array($enabled)) {
            // Fallback to tenant context if session not set
            $school = request()->attributes->get('school');
            $enabled = $school->enabled_modules ?? [];
        }
        $enabled = is_array($enabled) ? $enabled : [];

        $filtered = [];
        foreach ($menu as $item) {
            if (!self::allowed($item, $roles, $permissions, $enabled)) {
                continue;
            }

            if (!empty($item['children'])) {
                $children = [];
                foreach ($item['children'] as $child) {
                    if (self::allowed($child, $roles, $permissions, $enabled)) {
                        $children[] = $child;
                    }
                }
                if (count($children) > 0) {
                    $item['children'] = $children;
                    $filtered[] = $item;
                }
                continue;
            }

            $filtered[] = $item;
        }

        return $filtered;
    }

    protected static function allowed(array $item, array $roles, array $permissions, array $enabledModules): bool
    {
        // Module filter: if modules are selected, hide items not in the list
        if (!empty($item['module'] ?? null) && !empty($enabledModules)) {
            if (!in_array($item['module'], $enabledModules, true)) {
                return false;
            }
        }

        // if roles/permissions are explicitly empty arrays — always show
        if ((isset($item['roles']) && $item['roles'] === [])
            && (isset($item['permissions']) && $item['permissions'] === [])) {
            return true;
        }

        // role check
        if (!empty($item['roles'])) {
            foreach ($item['roles'] as $role) {
                if (in_array($role, $roles, true)) {
                    return true;
                }
            }
        }

        // permission check
        if (!empty($item['permissions'])) {
            foreach ($item['permissions'] as $perm) {
                if (in_array($perm, $permissions, true)) {
                    return true;
                }
            }
        }

        // parent menu with children — show if at least one child allowed
        if (!empty($item['children'] ?? [])) {
            foreach ($item['children'] as $child) {
                if (self::allowed($child, $roles, $permissions, $enabledModules)) {
                    return true;
                }
            }
        }

        return false;
    }
}
