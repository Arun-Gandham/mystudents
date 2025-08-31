<?php
namespace App\Models\Traits;

use App\Models\School;

trait BelongsToSchool
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function scopeForSchool($query, string $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
