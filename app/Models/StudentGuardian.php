<?php
namespace App\Models;

use App\Models\Traits\BelongsToStudent;
use App\Models\Traits\HasTimestampsImmutable;

class StudentGuardian extends BaseUuidModel
{
    use BelongsToStudent, HasTimestampsImmutable;

    protected $table = 'student_guardians';
    protected $casts = [
        'is_primary' => 'boolean',
    ];
}
