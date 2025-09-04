<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\School;
use App\Models\Academic;

class ResolveSchoolFromHost
{
    public function handle(Request $request, Closure $next)
    {
        
        $host = $request->getHost();                   // e.g. school11.myschool.com
        $root = config('app.tenant_root_domain');      // myschool.com

        // Allow bare root domain for marketing/site root
        if ($host === $root) {
            return $next($request);
        }
        
        if (!str_ends_with($host, '.' . $root)) {
            throw new NotFoundHttpException('Invalid domain.');
        }

        $subdomain = substr($host, 0, -1 * (strlen($root) + 1));
        if ($subdomain === '' || $subdomain === 'www') {
            throw new NotFoundHttpException('Invalid subdomain.');
        }

        $cacheKey = "tenant:domain:$subdomain";

        $schoolData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($subdomain) {
            $s = School::query()
                ->select('id','name','domain','is_active')
                ->where('domain', $subdomain)
                ->where('is_active', true)
                ->first();

            return $s ? $s->toArray() : null;
        });

        if (!$schoolData) {
            throw new NotFoundHttpException('School not found or inactive.');
        }

        

        // attach lightweight object
        $schoolObj = (object) $schoolData;
        $request->attributes->set('school', $schoolObj);
        app()->instance('tenant.school', $schoolObj);

        $academicCacheKey = "tenant:academic:{$schoolObj->id}";

        $academicData = Cache::remember($academicCacheKey, now()->addMinutes(10), function () use ($schoolObj) {
            $a = Academic::query()
                ->select('id','name','start_date','end_date','is_current')
                ->where('school_id', $schoolObj->id)
                ->where('is_current', true)
                ->first();

            return $a ? $a->toArray() : null;
        });
        // if (!$academicData) {
        //     throw new NotFoundHttpException('No active academic year found for this school.');
        // }

        $academicObj = (object) $academicData;
        $request->attributes->set('academic', $academicObj);
        app()->instance('tenant.academic', $academicObj);
        

        return $next($request);
    }
}
