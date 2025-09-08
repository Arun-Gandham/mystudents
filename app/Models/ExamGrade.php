<?php
namespace App\Models;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamGrade extends BaseUuidModel
{
    use HasFactory;

    protected $fillable = ['id','exam_id','grade','min_mark','max_mark','remark'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
