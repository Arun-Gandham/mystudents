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
}
