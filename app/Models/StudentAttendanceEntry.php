<?php
namespace App\Models;

use App\Models\Traits\BelongsToStudent;
use App\Models\student;
use App\Models\StudentAttendanceSheet;

class StudentAttendanceEntry extends BaseUuidModel
{
    use BelongsToStudent;

    protected $table = 'student_attendance_entries';
    protected $casts = [
        'check_in'  => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
    ];
    protected $fillable = [
        'sheet_id',
        'student_id',
        'status',
        'remarks',
        'check_in',
        'check_out',
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function sheet() {
        return $this->belongsTo(StudentAttendanceSheet::class,'sheet_id');
    }
}
