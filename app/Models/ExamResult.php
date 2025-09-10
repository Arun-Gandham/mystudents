<?php
namespace App\Models;

use App\Models\Traits\BelongsToStudent;
use App\Models\Traits\BelongsToSubject;

class ExamResult extends BaseUuidModel
{
    use BelongsToStudent, BelongsToSubject;

    protected $table = 'exam_results';
    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'entered_at'     => 'immutable_datetime',
    ];
    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'marks_obtained',
        'grade',
        'remarks',
        'entered_by',
        'entered_at',
    ];

    public function exam()      { return $this->belongsTo(Exam::class); }
    public function enteredBy() { return $this->belongsTo(User::class, 'entered_by'); }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
