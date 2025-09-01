<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToGrade;
use Illuminate\Database\Eloquent\Model;

class Section extends BaseUuidModel
{
    use BelongsToSchool, BelongsToGrade;

    protected $table = 'sections';
    protected $fillable = ['grade_id', 'name', 'teacher_id'];
    public function grade() {
        return $this->belongsTo(Grade::class);
    }

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function enrollments() { return $this->hasMany(StudentEnrollment::class); }
    public function dayTimetables() { return $this->hasMany(SectionDayTimetable::class); }
    public function exams() { return $this->hasMany(Exam::class); }
    public function attendanceSheets() { return $this->hasMany(StudentAttendanceSheet::class); }
    public function sectionFees() { return $this->hasMany(SectionFee::class); }
}
