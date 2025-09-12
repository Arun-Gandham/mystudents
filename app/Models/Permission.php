<?php
namespace App\Models;

use App\Models\Traits\HasTimestampsImmutable;

class Permission extends BaseUuidModel
{
    use HasTimestampsImmutable;

    protected $table = 'permissions';
    protected $fillable = ['key', 'description', 'group_name'];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withPivot(['scope', 'allow'])
            ->withTimestamps();
    }
}
