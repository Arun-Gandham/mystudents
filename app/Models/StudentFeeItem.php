<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToStudent;
use App\Models\Traits\HasTimestampsImmutable;

class StudentFeeItem extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, BelongsToStudent, HasTimestampsImmutable;

    protected $table = 'student_fee_items';
    protected $casts = [
        'base_amount'    => 'decimal:2',
        'discount_value' => 'decimal:2',
        'final_amount'   => 'decimal:2',
    ];
    protected $fillable = [
        'school_id',
        'academic_id',
        'student_id',
        'fee_head_id',
        'base_amount',
        'discount_kind',
        'discount_value',
        'final_amount',
    ];

    public function feeHead()  { return $this->belongsTo(FeeHead::class, 'fee_head_id'); }
    public function payments() { return $this->hasMany(StudentFeePayment::class, 'student_fee_item_id'); }
}
