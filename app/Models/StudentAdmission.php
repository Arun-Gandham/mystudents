<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToStudent;
use App\Models\Traits\HasTimestampsImmutable;

class StudentAdmission extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, BelongsToStudent, HasTimestampsImmutable;

    protected $table = 'student_admissions';
    protected $casts = [
        'applied_on'  => 'date',
        'offered_on'  => 'date',
        'admitted_on' => 'date',
    ];

    public function offeredGrade()   { return $this->belongsTo(Grade::class, 'offered_grade_id'); }
    public function offeredSection() { return $this->belongsTo(Section::class, 'offered_section_id'); }
}
