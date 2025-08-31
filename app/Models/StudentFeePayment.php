<?php
namespace App\Models;

class StudentFeePayment extends BaseUuidModel
{
    protected $table = 'student_fee_payments';
    protected $casts = [
        'paid_amount' => 'decimal:2',
        'paid_on'     => 'date',
        'created_at'  => 'immutable_datetime',
    ];

    public function feeItem() { return $this->belongsTo(StudentFeeItem::class, 'student_fee_item_id'); }
}
