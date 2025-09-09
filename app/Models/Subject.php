<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;

class Subject extends BaseUuidModel
{
    use BelongsToSchool;

    protected $table = 'subjects';
    protected $casts = [
        'is_active' => 'boolean',
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'school_id', 'name', 'code', 'is_active'
    ];
    public function examSubjects() { return $this->hasMany(ExamSubject::class); }
    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_subject');
    }
}
