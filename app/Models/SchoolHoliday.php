<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;

class SchoolHoliday extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic;

    protected $table = 'school_holidays';
    protected $fillable = [
        'school_id', 'academic_id', 'title', 'description',
        'holiday_date', 'start_date', 'end_date',
        'is_recurring', 'is_active'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'holiday_date' => 'date',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];
}
