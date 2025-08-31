<?php
namespace App\Models\Traits;

use App\Models\Subject;

trait BelongsToSubject
{
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function scopeForSubject($query, string $subjectId)
    {
        return $query->where($this->getTable().'.subject_id', $subjectId);
    }
}
