<?php
namespace App\Models\Traits;

use App\Models\Academic;

trait BelongsToAcademic
{
    public function academic()
    {
        return $this->belongsTo(Academic::class, 'academic_id');
    }

    public function scopeForAcademic($query, string $academicId)
    {
        return $query->where($this->getTable().'.academic_id', $academicId);
    }

    protected static function bootBelongsToAcademic()
    {
        static::creating(function ($model) {
            if (function_exists('current_academic_id') && current_academic_id()) {
                if (empty($model->academic_id)) {
                    $model->academic_id = current_academic_id();
                }
            }
        });

        static::addGlobalScope('academic', function ($builder) {
            $academic = request()->attributes->get('academic');
            if (!$academic || empty($academic->id)) {
                return;
            }
            $builder->where(function ($q) use ($builder, $academic) {
                $q->where($builder->getModel()->getTable().'.academic_id', $academic->id);
            });
        });
    }
}
