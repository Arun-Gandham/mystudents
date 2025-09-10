<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\HasTimestampsImmutable;

class StudentJoinApplication extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, HasTimestampsImmutable;

    protected $table = 'student_join_applications';
    protected $fillable = [
        'school_id',
        'academic_id',
        'preferred_grade_id',
        'preferred_section_id',
        'application_no',
        'status',
        'child_full_name',
        'child_dob',
        'child_gender',
        'previous_school',
        'guardian_full_name',
        'guardian_relation',
        'guardian_email',
        'guardian_phone',
        'address',
        'visited_on',
        'submitted_on',
        'decided_on',
        'remarks',
        'student_id'
    ];
    protected $casts = [
        'visited_on'   => 'date',
        'submitted_on' => 'date',
        'decided_on'   => 'date',
    ];

    public function preferredGrade()
    {
        return $this->belongsTo(Grade::class, 'preferred_grade_id');
    }
    
    public function preferredSection()
    {
        return $this->belongsTo(Section::class, 'preferred_section_id');
    }
    public function student()         { return $this->belongsTo(Student::class); }
    public function logs()
    {
        return $this->hasMany(StudentJoinApplicationLog::class, 'application_id');
    }
}
