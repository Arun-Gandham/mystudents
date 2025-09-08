<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToSection;
use App\Models\Traits\HasTimestampsImmutable;

class Exam extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, BelongsToSection, HasTimestampsImmutable;

    protected $table = 'exams';
    protected $casts = [
        'starts_on'    => 'date',
        'ends_on'      => 'date',
        'is_published' => 'boolean',
    ];

    public function subjects() { return $this->hasMany(ExamSubject::class, 'exam_id'); }
    public function results()  { return $this->hasMany(ExamResult::class, 'exam_id'); }
    public function grades()
    {
        return $this->hasMany(ExamGrade::class);
    }
    public function overallResults()
    {
        return $this->hasMany(ExamOverallResult::class);
    }
}
