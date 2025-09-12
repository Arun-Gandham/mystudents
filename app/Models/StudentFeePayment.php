<?php
namespace App\Models;

class StudentFeePayment extends BaseUuidModel
{
    protected $table = 'student_fee_payments';
    protected $fillable = ['receipt_id','student_fee_item_id','paid_amount'];

    public function receipt()
    {
        return $this->belongsTo(StudentFeeReceipt::class, 'receipt_id');
    }

    public function item()
    {
        return $this->belongsTo(StudentFeeItem::class, 'student_fee_item_id');
    }
}
