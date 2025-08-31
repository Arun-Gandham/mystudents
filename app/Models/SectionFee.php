<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToSection;
use App\Models\Traits\HasTimestampsImmutable;

class SectionFee extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, BelongsToSection, HasTimestampsImmutable;

    protected $table = 'section_fees';
    protected $casts = [
        'base_amount' => 'decimal:2',
        'is_optional' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function feeHead() { return $this->belongsTo(FeeHead::class, 'fee_head_id'); }
}
