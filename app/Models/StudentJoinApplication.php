<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\HasTimestampsImmutable;

class StudentJoinApplication extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, HasTimestampsImmutable;

    protected $table = 'student_join_applications';
    protected $casts = [
        'visited_on'   => 'date',
        'submitted_on' => 'date',
        'decided_on'   => 'date',
    ];

    public function preferredGrade()  { return $this->belongsTo(Grade::class, 'preferred_grade_id'); }
    public function preferredSection(){ return $this->belongsTo(Section::class, 'preferred_section_id'); }
    public function student()         { return $this->belongsTo(Student::class); }
}
