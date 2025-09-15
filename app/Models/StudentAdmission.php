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
    protected $fillable = [
        'school_id','academic_id','student_id','source_application_id',
        'application_no','status','applied_on','offered_on','admitted_on',
        'offered_grade_id','offered_section_id','previous_school','remarks'
    ];
    protected $casts = [
        'applied_on'  => 'date',
        'offered_on'  => 'date',
        'admitted_on' => 'date',
    ];
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'offered_grade_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'offered_section_id');
    }

    public function offeredGrade()   { return $this->belongsTo(Grade::class, 'offered_grade_id'); }
    public function offeredSection() { return $this->belongsTo(Section::class, 'offered_section_id'); }
    public function sourceApplication()
    {
        return $this->belongsTo(StudentJoinApplication::class, 'source_application_id');
    }
}
