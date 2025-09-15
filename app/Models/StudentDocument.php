<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDocument extends BaseUuidModel
{
    use SoftDeletes;

    protected $fillable = [
        'student_id','doc_type','file_path',
        'issued_on','verified_on'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
