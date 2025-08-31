<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\Traits\BelongsToSection;

class SectionDayTimetable extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic, BelongsToSection;

    protected $table = 'section_day_timetables';
    protected $casts = [
        'is_active'      => 'boolean',
        'effective_from' => 'date',
        'effective_to'   => 'date',
    ];

    public function periods() { return $this->hasMany(SectionDayPeriod::class, 'day_timetable_id'); }
}
