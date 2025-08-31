<?php
namespace App\Models\Traits;

use App\Models\Section;

trait BelongsToSection
{
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function scopeForSection($query, string $sectionId)
    {
        return $query->where($this->getTable().'.section_id', $sectionId);
    }
}
