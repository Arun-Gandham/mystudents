<?php
namespace App\Models;

use App\Models\Traits\BelongsToSubject;

class SectionDayPeriod extends BaseUuidModel
{
    use BelongsToSubject;

    protected $table = 'section_day_periods';
    protected $casts = [
        'period_no' => 'integer',
        'starts_at' => 'datetime:H:i:s',
        'ends_at'   => 'datetime:H:i:s',
    ];

    public function dayTimetable() { return $this->belongsTo(SectionDayTimetable::class, 'day_timetable_id'); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
}
