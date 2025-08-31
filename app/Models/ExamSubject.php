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
    ];

    public function exam() { return $this->belongsTo(Exam::class); }
}
