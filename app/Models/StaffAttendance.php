<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToUser;
use App\Models\Traits\HasTimestampsImmutable;

class StaffAttendance extends BaseUuidModel
{
    use BelongsToSchool, BelongsToUser, HasTimestampsImmutable;

    protected $table = 'staff_attendance';
    protected $casts = [
        'attendance_date' => 'date',
        'check_in'  => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
    ];
    protected $fillable = [
        'school_id',
        'attendance_date',
        'session',
        'user_id',
        'status',
        'remarks',
        'check_in',
        'check_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
