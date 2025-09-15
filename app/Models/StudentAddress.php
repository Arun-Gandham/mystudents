<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAddress extends BaseUuidModel
{

    protected $fillable = [
        'student_id','address_line1','address_line2',
        'city','district','state','pincode','address_type'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
