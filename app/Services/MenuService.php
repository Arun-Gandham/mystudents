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

        $filtered = [];
        foreach ($menu as $item) {
            if (self::allowed($item, $roles, $permissions)) {
                if (!empty($item['children'])) {
                    $children = [];
                    foreach ($item['children'] as $child) {
                        if (self::allowed($child, $roles, $permissions)) {
                            $children[] = $child;
                        }
                    }
                    $item['children'] = $children;
                }
                $filtered[] = $item;
            }
        }

        return $filtered;
    }

    protected static function allowed(array $item, array $roles, array $permissions): bool
    {
        // ✅ if roles/permissions are explicitly empty arrays → always show
        if ((isset($item['roles']) && $item['roles'] === [])
            && (isset($item['permissions']) && $item['permissions'] === [])) {
            return true;
        }

        // ✅ role check
        if (!empty($item['roles'])) {
            foreach ($item['roles'] as $role) {
                if (in_array($role, $roles, true)) {
                    return true;
                }
            }
        }

        // ✅ permission check
        if (!empty($item['permissions'])) {
            foreach ($item['permissions'] as $perm) {
                if (in_array($perm, $permissions, true)) {
                    return true;
                }
            }
        }

        // ✅ parent menu with children → show if at least one child allowed
        if (!empty($item['children'] ?? [])) {
            foreach ($item['children'] as $child) {
                if (self::allowed($child, $roles, $permissions)) {
                    return true;
                }
            }
        }

        return false;
    }
}
