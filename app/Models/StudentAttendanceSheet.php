<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToSection;
use App\Models\Traits\HasTimestampsImmutable;

class StudentAttendanceSheet extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, BelongsToSection, HasTimestampsImmutable;

    protected $table = 'student_attendance_sheets';
    protected $casts = [
        'attendance_date' => 'date',
        'taken_at'        => 'immutable_datetime',
    ];

    public function takenBy()  { return $this->belongsTo(User::class, 'taken_by'); }
    public function entries()  { return $this->hasMany(StudentAttendanceEntry::class, 'sheet_id'); }
}
