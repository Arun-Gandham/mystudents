<?php
namespace App\Models\Traits;

use App\Models\School;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToSchool
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function scopeForSchool($query, string $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }

    // 🔹 Auto-scope all queries to current school
    protected static function bootBelongsToSchool()
    {

        // this method is used to get the current school id from 
        static::creating(function ($model) {
            if (function_exists('current_school_id') && current_school_id()) {
                // auto-fill on create if empty
                if (empty($model->school_id)) {
                    $model->school_id = current_school_id();
                }
            }
        });

        static::addGlobalScope('school', function (Builder $builder) {
            $school = request()->attributes->get('school');

            // Skip scoping if no tenant context, or the user is Super Admin
            if (!$school) {
                return;
            }
            if ($school = request()->attributes->get('school')) {
                $builder->where(function ($q) use ($builder, $school) {
                    $q->where($builder->getModel()->getTable().'.school_id', $school->id);
                });
            }
        });
    }
}
