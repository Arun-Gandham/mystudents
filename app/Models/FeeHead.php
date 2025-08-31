<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\HasTimestampsImmutable;

class FeeHead extends BaseUuidModel
{
    use BelongsToSchool, HasTimestampsImmutable;

    protected $table = 'fee_heads';
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sectionFees()     { return $this->hasMany(SectionFee::class); }
    public function studentFeeItems() { return $this->hasMany(StudentFeeItem::class); }
}
