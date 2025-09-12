<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Cache;
use App\Models\Staff;
use App\Models\Traits\HasRolesAndPermissions; 

class User extends Authenticatable
{
    use BelongsToSchool, HasUuids, HasRolesAndPermissions;

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
    /**
     * Primary role (single record from user_roles)
     */
    public function primaryRole()
    {
        return $this->roles()->wherePivot('is_primary', true)->first();
    }
    /**
     * Roles via pivot, with pivot data
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot(['is_primary', 'starts_on', 'ends_on'])
            ->withTimestamps();
    }


    /**
     * Permissions from roles
     */
    public function getPermissions()
    {
        return Cache::remember("user_permissions_{$this->id}", 3600, function () {
            return $this->roles()
                ->with('permissions') // eager load role permissions
                ->get()
                ->pluck('permissions.*.key')
                ->flatten()
                ->unique()
                ->values()
                ->toArray();
        });
    }

    public function clearPermissionCache()
    {
        Cache::forget("user_permissions_{$this->id}");
    }

    public function staff()
    {
        return $this->hasOne(Staff::class,'user_id');
    }
}
