<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\HasTimestampsImmutable;

class Role extends BaseUuidModel
{
    use BelongsToSchool, HasTimestampsImmutable;

    protected $table = 'roles';
    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function rolePermissions() { return $this->hasMany(RolePermission::class); }
    public function userRoles()       { return $this->hasMany(UserRole::class); }

    // 🔗 Direct relation to permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    // 🔗 Direct relation to users
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
}

    // ✅ Invalidate cache for all users of this role
    protected static function booted()
    {
        static::saved(function ($role) {
            foreach ($role->users as $user) {
                Cache::forget("user_permissions_{$user->id}");
            }
        });

        static::deleted(function ($role) {
            foreach ($role->users as $user) {
                Cache::forget("user_permissions_{$user->id}");
            }
        });
    }

}
