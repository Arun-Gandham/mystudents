<?php
namespace App\Models;

use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToStudent;
use App\Models\Traits\BelongsToGrade;
use App\Models\Traits\BelongsToSection;

class StudentEnrollment extends BaseUuidModel
{
    use BelongsToAcademic, BelongsToStudent, BelongsToGrade, BelongsToSection;

    protected $table = 'student_enrollments';
    protected $casts = [
        'joined_on' => 'date',
        'left_on'   => 'date',
    ];
}
