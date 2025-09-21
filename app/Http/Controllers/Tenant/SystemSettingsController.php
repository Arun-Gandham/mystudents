<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\School;
use App\Support\FileHelper;

class SystemSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    public function edit()
    {
        // Use a distinct variable name to avoid shadowing the shared $school from middleware
        $settingsSchool = School::with('details')->findOrFail(current_school_id());
        return view('Tenant.pages.Settings.system', compact('settingsSchool'));
    }

    public function update(Request $request)
    {
        $school = School::with('details')->findOrFail(current_school_id());

        $data = $request->validate([
            // Basic
            'name'      => ['required','string','max:100'],

            // Details
            'phone'            => ['nullable','string','max:20'],
            'alt_phone'        => ['nullable','string','max:20'],
            'email'            => ['nullable','email','max:150'],
            'website'          => ['nullable','string','max:255'],
            'landline'         => ['nullable','string','max:255'],
            'address_line1'    => ['nullable','string','max:255'],
            'address_line2'    => ['nullable','string','max:255'],
            'city'             => ['nullable','string','max:100'],
            'state'            => ['nullable','string','max:100'],
            'postal_code'      => ['nullable','string','max:20'],
            'country_code'     => ['nullable','string','max:10'],
            'established_year' => ['nullable','integer'],
            'affiliation_no'   => ['nullable','string','max:100'],
            'note'             => ['nullable','string'],

            // Branding files
            'logo'             => ['nullable','mimes:jpg,jpeg,png,gif,webp','max:2048'],
            'favicon'          => ['nullable','mimes:jpg,jpeg,png,gif,ico','max:1024'],

            // App settings (new)
            'theme'            => ['nullable','in:light,dark,system'],
            'primary_color'    => ['nullable','string','max:20'],
            'secondary_color'  => ['nullable','string','max:20'],
            'timezone'         => ['nullable','string','max:64'],
            'locale'           => ['nullable','string','max:10'],
            'date_format'      => ['nullable','string','max:20'],
        ]);

        // Update school basic
        $school->update([
            'name' => $data['name'],
        ]);

        $details = $school->details ?? $school->details()->make(['school_id' => $school->id]);

        // Branding uploads
        $basePath = "schools/{$school->id}/details";
        if ($request->hasFile('logo')) {
            $details->logo_url = FileHelper::replace($details->logo_url, $request->file('logo'), $basePath, 'public');
        }
        if ($request->hasFile('favicon')) {
            $details->favicon_url = FileHelper::replace($details->favicon_url, $request->file('favicon'), $basePath, 'public');
        }

        $details->fill([
            'phone'            => $data['phone'] ?? null,
            'alt_phone'        => $data['alt_phone'] ?? null,
            'email'            => $data['email'] ?? null,
            'website'          => $data['website'] ?? null,
            'landline'         => $data['landline'] ?? null,
            'address_line1'    => $data['address_line1'] ?? null,
            'address_line2'    => $data['address_line2'] ?? null,
            'city'             => $data['city'] ?? null,
            'state'            => $data['state'] ?? null,
            'postal_code'      => $data['postal_code'] ?? null,
            'country_code'     => $data['country_code'] ?? null,
            'established_year' => $data['established_year'] ?? null,
            'affiliation_no'   => $data['affiliation_no'] ?? null,
            'note'             => $data['note'] ?? null,
            // new fields
            'theme'            => $data['theme'] ?? null,
            'primary_color'    => $data['primary_color'] ?? null,
            'secondary_color'  => $data['secondary_color'] ?? null,
            'timezone'         => $data['timezone'] ?? null,
            'locale'           => $data['locale'] ?? null,
            'date_format'      => $data['date_format'] ?? null,
        ]);

        $details->save();

        // Refresh cached school branding to avoid stale sidebar/favicon
        $domain = $school->domain;
        if ($domain) {
            $cacheKey = "tenant:domain:$domain";
            Cache::forget($cacheKey);
            Cache::put($cacheKey, [
                'id'          => $school->id,
                'name'        => $school->name,
                'domain'      => $school->domain,
                'is_active'   => $school->is_active,
                'logo_url'    => $details->logo_url,
                'favicon_url' => $details->favicon_url,
            ], now()->addMinutes(10));
        }

        return redirect()->to(tenant_route('tenant.settings.system.edit'))
            ->with('success','Settings updated successfully');
    }
}
