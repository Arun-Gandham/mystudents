<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\HasTimestampsImmutable;

class Academic extends BaseUuidModel
{
    use BelongsToSchool, HasTimestampsImmutable;

    protected $table = 'academics';
    protected $fillable = [
        'school_id',
        'name',
        'start_date',
        'end_date',
        'is_current',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    public function enrollments() { return $this->hasMany(StudentEnrollment::class); }
    public function admissions()  { return $this->hasMany(StudentAdmission::class); }
    public function holidays()    { return $this->hasMany(SchoolHoliday::class); }
}
