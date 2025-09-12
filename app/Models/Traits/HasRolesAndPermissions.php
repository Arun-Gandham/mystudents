<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;

trait HasRolesAndPermissions
{
    public function loadPermissions(): array
    {
        return Cache::remember("user_permissions_{$this->id}", 3600, function () {
            return $this->roles()
                ->with('permissions')
                ->get()
                ->flatMap(fn($role) => $role->permissions->pluck('key'))
                ->unique()
                ->values()
                ->toArray();
        });
    }

    public function refreshPermissions(): void
    {
        Cache::forget("user_permissions_{$this->id}");
        $perms = $this->loadPermissions();
        session(['auth_permissions' => $perms]);
        $this->permissions = $perms;
    }

    public function hasPermission(string $key): bool
    {
        $perms = $this->permissions ?? session('auth_permissions', $this->loadPermissions());
        return in_array($key, $perms);
    }

    public function hasAnyPermission(array $keys): bool
    {
        foreach ($keys as $key) {
            if ($this->hasPermission($key)) return true;
        }
        return false;
    }

    public function hasRole(string $name): bool
    {
        return $this->roles->pluck('name')->contains($name);
    }

    public function hasAnyRole(array $names): bool
    {
        return $this->roles->pluck('name')->intersect($names)->isNotEmpty();
    }
}
