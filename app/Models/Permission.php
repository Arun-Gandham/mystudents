<?php
namespace App\Models;

use App\Models\Traits\HasTimestampsImmutable;

class Permission extends BaseUuidModel
{
    use HasTimestampsImmutable;

    protected $table = 'permissions';
    protected $fillable = ['name','group'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')->withTimestamps();
    }
}
