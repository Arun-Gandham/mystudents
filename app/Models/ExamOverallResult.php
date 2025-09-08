<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExamOverallResult extends Model
{
    use HasFactory;

    protected $table = 'exam_overall_results';
    protected $fillable = [
        'id',
        'exam_id',
        'student_id',
        'total_obtained',
        'total_max',
        'overall_grade',
        'percentage',
        'rank',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
