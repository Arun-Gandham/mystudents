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

    public function examSubjects() { return $this->hasMany(ExamSubject::class); }
}
