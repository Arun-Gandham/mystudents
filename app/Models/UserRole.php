<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToUser;

class UserRole extends BaseUuidModel
{
    use BelongsToSchool, BelongsToUser;

    protected $table = 'user_roles';
    protected $casts = [
        'is_primary' => 'boolean',
        'starts_on'  => 'date',
        'ends_on'    => 'date',
    ];

    public function role() { return $this->belongsTo(Role::class); }
}
