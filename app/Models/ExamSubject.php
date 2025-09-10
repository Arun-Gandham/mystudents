<?php
namespace App\Models;

use App\Models\Traits\BelongsToSubject;

class ExamSubject extends BaseUuidModel
{
    use BelongsToSubject;

    protected $table = 'exam_subjects';
    protected $casts = [
        'max_marks'  => 'decimal:2',
        'pass_marks' => 'decimal:2',
        'order_no'   => 'integer',
        'exam_date' => 'date'
    ];
    protected $fillable = [
        'exam_id',
        'subject_id',
        'max_marks',
        'pass_marks',
        'order_no',
        'exam_date',
    ];

    public function exam() { return $this->belongsTo(Exam::class); }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
