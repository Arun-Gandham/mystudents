<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFeeReceipt extends BaseUuidModel
{
    protected $fillable = [
        'school_id','academic_id','student_id','total_amount','paid_on',
        'method','reference_no','payer_name','payer_phone','payer_relation','note'
    ];
    protected $casts = [
        'paid_on' => 'date', // or 'datetime' if includes time
    ];

    public function payments()
    {
        return $this->hasMany(StudentFeePayment::class, 'receipt_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
