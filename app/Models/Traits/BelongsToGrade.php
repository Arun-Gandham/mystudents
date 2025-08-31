<?php
namespace App\Models\Traits;

use App\Models\Grade;

trait BelongsToGrade
{
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function scopeForGrade($query, string $gradeId)
    {
        return $query->where($this->getTable().'.grade_id', $gradeId);
    }
}
