<?php
namespace App\Models;

class RolePermission extends BaseUuidModel
{
    protected $table = 'role_permissions';
    protected $casts = [
        'allow' => 'boolean',
    ];

    public function role()       { return $this->belongsTo(Role::class); }
    public function permission() { return $this->belongsTo(Permission::class); }
}
