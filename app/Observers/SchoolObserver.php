<?php

namespace App\Observers;

use App\Models\School;
use Illuminate\Support\Facades\Cache;

class SchoolObserver
{
    public function saved(School $school): void
    {
        Cache::forget("tenant:domain:{$school->domain}");
    }

    public function deleted(School $school): void
    {
        Cache::forget("tenant:domain:{$school->domain}");
    }
}
