<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\HasTimestampsImmutable;

class Grade extends BaseUuidModel
{
    use BelongsToSchool, HasTimestampsImmutable;

    protected $table = 'grades';
    protected $casts = [
        'ordinal'   => 'integer',
        'is_active' => 'boolean',
    ];

    public function sections() { return $this->hasMany(Section::class); }
}
