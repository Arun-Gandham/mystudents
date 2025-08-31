<?php
namespace App\Models\Traits;

use App\Models\Student;

trait BelongsToStudent
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeForStudent($query, string $studentId)
    {
        return $query->where($this->getTable().'.student_id', $studentId);
    }
}
