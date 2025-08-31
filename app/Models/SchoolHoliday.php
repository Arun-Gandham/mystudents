<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;

class SchoolHoliday extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic;

    protected $table = 'school_holidays';
    protected $casts = [
        'date'             => 'date',
        'is_full_day'      => 'boolean',
        'repeats_annually' => 'boolean',
    ];
}
