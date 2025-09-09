<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\BelongsToSection;


class Staff extends BaseUuidModel
{
    use BelongsToSchool,BelongsToSection;

    protected $fillable = [
        'school_id','user_id','first_name','last_name','surname',
    'photo','experience_years','joining_date',
        'designation','phone','address','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joining_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'staff_subject');
    }
}
