<?php
namespace App\Models;

use App\Models\Traits\HasTimestampsImmutable;

class Permission extends BaseUuidModel
{
    use HasTimestampsImmutable;

    protected $table = 'permissions';

    public function rolePermissions() { return $this->hasMany(RolePermission::class); }
}
