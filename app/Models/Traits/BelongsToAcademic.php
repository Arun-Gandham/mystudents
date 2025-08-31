<?php
namespace App\Models\Traits;

use App\Models\Academic;

trait BelongsToAcademic
{
    public function academic()
    {
        return $this->belongsTo(Academic::class, 'academic_id');
    }

    public function scopeForAcademic($query, string $academicId)
    {
        return $query->where($this->getTable().'.academic_id', $academicId);
    }
}
