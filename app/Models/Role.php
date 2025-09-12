<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\HasTimestampsImmutable;

class Role extends BaseUuidModel
{
    use BelongsToSchool, HasTimestampsImmutable;

    protected $table = 'roles';
    protected $keyType = 'string';
    protected $fillable = ['school_id', 'name', 'description', 'is_system'];
    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function rolePermissions() { return $this->hasMany(RolePermission::class); }
    public function userRoles()       { return $this->hasMany(UserRole::class); }

      public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withPivot(['scope', 'allow'])
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot(['school_id', 'is_primary', 'starts_on', 'ends_on'])
            ->withTimestamps();
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
