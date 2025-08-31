<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use BelongsToSchool, HasUuids;

    protected $table = 'users';
    protected $hidden = ['password', 'remember_token'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'email',
        'school_id',
        'password',
        'full_name',
    ];
    protected $casts = [
        'is_active'         => 'boolean',
        'email_verified_at' => 'immutable_datetime',
        'password'          => 'hashed',
    ];
    public function primaryRoles() { return $this->hasMany(UserRole::class); }
}
