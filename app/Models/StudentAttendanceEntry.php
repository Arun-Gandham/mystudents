<?php
namespace App\Models;

use App\Models\Traits\BelongsToStudent;

class StudentAttendanceEntry extends BaseUuidModel
{
    use BelongsToStudent;

    protected $table = 'student_attendance_entries';
    protected $casts = [
        'check_in'  => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
    ];

    public function sheet() { return $this->belongsTo(StudentAttendanceSheet::class, 'sheet_id'); }
}
