<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\HasTimestampsImmutable;

class Student extends BaseUuidModel
{
    use BelongsToSchool, HasTimestampsImmutable;

    protected $table = 'students';
    protected $casts = [
        'dob' => 'date',
    ];

    public function sourceApplication()
    {
        return $this->belongsTo(StudentJoinApplication::class, 'source_application_id');
    }

    public function admissions() { return $this->hasMany(StudentAdmission::class); }
    public function enrollments() { return $this->hasMany(StudentEnrollment::class); }
    public function guardians() { return $this->hasMany(StudentGuardian::class); }
    public function attendanceEntries() { return $this->hasMany(StudentAttendanceEntry::class, 'student_id'); }
    public function feeItems() { return $this->hasMany(StudentFeeItem::class); }


    public function addresses()
    {
        return $this->hasMany(StudentAddress::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }
}
