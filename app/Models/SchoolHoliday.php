<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToAcademic;
use App\Models\academic;

class SchoolHoliday extends BaseUuidModel
{
    use BelongsToSchool, BelongsToAcademic;

    protected $table = 'school_holidays';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'school_id',
        'academic_id',
        'date',
        'name',
        'is_full_day',
        'starts_at',
        'ends_at',
        'repeats_annually',
    ];

    protected $casts = [
        'date' => 'date',
        'is_full_day' => 'boolean',
        'repeats_annually' => 'boolean',
        'starts_at' => 'datetime:H:i',
        'ends_at'   => 'datetime:H:i',
    ];

    public function academic()
    {
        return $this->belongsTo(Academic::class);
    }
}
