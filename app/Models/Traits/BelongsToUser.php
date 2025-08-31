<?php
namespace App\Models\Traits;

use App\Models\User;

trait BelongsToUser
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForUser($query, string $userId)
    {
        return $query->where($this->getTable().'.user_id', $userId);
    }
}
