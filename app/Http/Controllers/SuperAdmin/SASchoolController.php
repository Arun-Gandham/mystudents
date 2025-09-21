<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Support\FileHelper;

class SASchoolController extends Controller
{
    protected array $baseMeta = [];

    public function __construct()
    {
        $this->baseMeta = [
            'pageTitle'       => 'Schools',
            'pageDescription' => 'Manage all schools in the system',
        ];

        view()->share($this->baseMeta);
    }

    /** ================= Utility Loader ================= */
    private function loadSchool(School $school): School
    {
        return $school->load([
            'details',
            'students',
            'users',
            'grades',
            'sections',
        ]);
    }

    /** ================= CRUD ================= */

    public function index()
    {
        $schools = School::withCount('students')
            ->select('id', 'name', 'domain', 'is_active')
            ->orderBy('name')
            ->get();

        return view('superadmin.pages.school.list', compact('schools'));
    }

    public function create()
    {
        return view('superadmin.pages.school.create', [
            'school' => new School(),
            'action' => route('superadmin.school.store'),
            'mode'   => 'New',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Schools
            'name'      => ['required', 'string', 'max:100', 'unique:schools,name'],
            'domain'    => ['required', 'string', 'max:50', 'unique:schools,domain'],
            'is_active' => ['boolean'],

            // School Details
            'phone'            => ['nullable', 'string', 'max:20'],
            'alt_phone'        => ['nullable', 'string', 'max:20'],
            'email'            => ['required', 'email', 'max:150'],
            'website'          => ['nullable', 'string', 'max:255'],
            'landline'         => ['nullable', 'string', 'max:255'],
            'logo'             => ['nullable', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'favicon'          => ['nullable', 'mimes:jpg,jpeg,png,gif,ico', 'max:1024'],
            'address_line1'    => ['nullable', 'string', 'max:255'],
            'address_line2'    => ['nullable', 'string', 'max:255'],
            'city'             => ['required', 'string', 'max:100'],
            'state'            => ['required', 'string', 'max:100'],
            'postal_code'      => ['required', 'string', 'max:20'],
            'country_code'     => ['required', 'string', 'max:10'],
            'established_year' => ['required', 'integer'],
            'affiliation_no'   => ['required', 'string', 'max:100'],
            'note'             => ['nullable', 'string'],

            // Admin (User + Staff)
            'first_name'       => ['required', 'string', 'max:100'],
            'last_name'        => ['nullable', 'string', 'max:100'],
            'surname'          => ['nullable', 'string', 'max:100'],
            'admin_email'      => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'         => [
                'required',
                PasswordRule::min(8)->max(72)->letters()->mixedCase()->numbers()->symbols(),
            ],
            'admin_phone'      => ['required', 'string', 'max:20'],
            'alt_admin_phone'  => ['nullable', 'string', 'max:20'],
            'admin_address'    => ['nullable', 'string', 'max:255'],
            'admin_experience' => ['nullable', 'integer'],
            'admin_joining'    => ['nullable', 'date'],
            'admin_designation'=> ['nullable', 'string', 'max:100'],
            'admin_photo'      => ['nullable', 'image', 'max:2048'],
        ]);

        // 1. Create school
        $school = School::create([
            'name'      => $data['name'],
            'domain'    => $data['domain'],
            'is_active' => $request->boolean('is_active'),
        ]);

        // 2. Base folders
        $schoolBase = "schools/{$school->id}";
        Storage::disk('public')->makeDirectory("{$schoolBase}/details");
        Storage::disk('public')->makeDirectory("{$schoolBase}/staff");
        Storage::disk('public')->makeDirectory("{$schoolBase}/students");

        // 3. Upload school logo + favicon
        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store("{$schoolBase}/details", 'public')
            : null;

        $faviconPath = $request->hasFile('favicon')
            ? $request->file('favicon')->store("{$schoolBase}/details", 'public')
            : null;

        // 4. Save school details
        $school->details()->create([
            'phone'            => $data['phone'] ?? null,
            'alt_phone'        => $data['alt_phone'] ?? null,
            'landline'         => $data['landline'] ?? null,
            'email'            => $data['email'] ?? null,
            'website'          => $data['website'] ?? null,
            'logo_url'         => $logoPath,
            'favicon_url'      => $faviconPath,
            'address_line1'    => $data['address_line1'] ?? null,
            'address_line2'    => $data['address_line2'] ?? null,
            'city'             => $data['city'] ?? null,
            'state'            => $data['state'] ?? null,
            'postal_code'      => $data['postal_code'] ?? null,
            'country_code'     => $data['country_code'] ?? null,
            'established_year' => $data['established_year'] ?? null,
            'affiliation_no'   => $data['affiliation_no'] ?? null,
            'note'             => $data['note'] ?? null,
        ]);

        // 5. Create admin user
        $adminUser = User::create([
            'school_id' => $school->id,
            'full_name' => trim($data['first_name'].' '.$data['last_name'].' '.$data['surname']),
            'email'     => $data['admin_email'],
            'password'  => Hash::make($data['password']),
        ]);

        // 6. Create staff record for admin
        $staff = Staff::create([
            'school_id'       => $school->id,
            'user_id'         => $adminUser->id,
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'surname'         => $data['surname'],
            'phone'           => $data['admin_phone'] ?? null,
            'alt_phone'       => $data['alt_admin_phone'] ?? null,
            'address'         => $data['admin_address'] ?? null,
            'experience_years'=> $data['admin_experience'] ?? 0,
            'joining_date'    => $data['admin_joining'] ?? null,
            'designation'     => $data['admin_designation'] ?? 'Administrator',
            'is_active'       => true,
        ]);

        // 7. Create staff folder + profile photo
        $staffFolder = "{$schoolBase}/staff/{$staff->id}/profile";
        Storage::disk('public')->makeDirectory($staffFolder);

        if ($request->hasFile('admin_photo')) {
            $adminPhotoPath = $request->file('admin_photo')->store($staffFolder, 'public');
            $staff->update(['photo' => $adminPhotoPath]);
        }

        return redirect()
            ->route('superadmin.school.dashboard', [$school->id])
            ->with('success', 'School, Admin user, and folders created successfully!');
    }

    public function show(School $school)
    {
        return view('superadmin.pages.school.base', [
            'school' => $this->loadSchool($school),
        ]);
    }

    public function edit(School $school)
    {
        return view('superadmin.school.create', [
            'school' => $school,
            'action' => route('superadmin.school.update', $school->id),
            'mode'   => 'edit',
        ]);
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100', Rule::unique('schools', 'name')->ignore($school->id)],
            'domain'    => ['required', 'string', 'max:50', Rule::unique('schools', 'domain')->ignore($school->id)],
            'is_active' => ['boolean'],
        ]);

        $school->update([
            'name'      => $data['name'],
            'domain'    => $data['domain'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('superadmin.school.index')->with('success', 'School updated successfully!');
    }

    public function destroy(School $school)
    {
        if ($school->details && $school->details->logo_url && Storage::disk('public')->exists($school->details->logo_url)) {
            Storage::disk('public')->delete($school->details->logo_url);
        }
        if ($school->details && $school->details->favicon_url && Storage::disk('public')->exists($school->details->favicon_url)) {
            Storage::disk('public')->delete($school->details->favicon_url);
        }

        $school->delete();

        return redirect()->route('superadmin.school.index')->with('success', 'School deleted successfully!');
    }

    /** ================= Tabs ================= */

    public function dashboard(School $school)
    {
        return view('superadmin.pages.school.dashboard', [
            'school'         => $this->loadSchool($school),
            'activeTab'      => 'dashboard',
            'pageDescription'=> 'Overview, trends and quick stats',
        ]);
    }

    public function students(School $school)
    {
        return view('superadmin.pages.school.students', [
            'school'         => $this->loadSchool($school),
            'activeTab'      => 'people',
            'pageDescription'=> 'Students, roles and permissions',
        ]);
    }

    public function settings(School $school)
    {
        return view('superadmin.pages.school.settings', [
            'school'         => $this->loadSchool($school),
            'activeTab'      => 'settings',
            'pageDescription'=> 'School configuration',
        ]);
    }

    public function updateSettings(Request $request, School $school)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'domain'           => ['required', 'string', 'max:50', Rule::unique('schools', 'domain')->ignore($school->id)],
            'is_active'        => ['boolean'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'alt_phone'        => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email'],
            'website'          => ['nullable', 'string', 'max:255'],
            'logo'             => ['nullable', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'favicon'          => ['nullable', 'mimes:jpg,jpeg,png,gif,ico', 'max:1024'],
            'address_line1'    => ['nullable', 'string', 'max:255'],
            'address_line2'    => ['nullable', 'string', 'max:255'],
            'city'             => ['nullable', 'string', 'max:100'],
            'state'            => ['nullable', 'string', 'max:100'],
            'postal_code'      => ['nullable', 'string', 'max:20'],
            'country_code'     => ['nullable', 'string', 'max:10'],
            'established_year' => ['nullable', 'integer'],
            'affiliation_no'   => ['nullable', 'string', 'max:100'],
            'note'             => ['nullable', 'string'],
        ]);

        // ✅ Update schools table
        $school->update([
            'name'      => $data['name'],
            'domain'    => $data['domain'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $details = $school->details ?? $school->details()->make();

        // ✅ Handle uploads
        if ($request->hasFile('logo')) {
            $details->logo_url = FileHelper::replace(
                $details->logo_url,                         // old logo path
                $request->file('logo'),                     // new logo file
                "schools/{$school->id}/details",            // folder
                'public'                                    // disk
            );
        }

        if ($request->hasFile('favicon')) {
            $details->favicon_url = FileHelper::replace(
                $details->favicon_url,                      // old favicon path
                $request->file('favicon'),                  // new favicon file
                "schools/{$school->id}/details",            // folder
                'public'                                    // disk
            );
        }

        // ✅ Update school_details
        $details->fill([
            'phone'            => $data['phone'] ?? null,
            'alt_phone'        => $data['alt_phone'] ?? null,
            'email'            => $data['email'] ?? null,
            'website'          => $data['website'] ?? null,
            'address_line1'    => $data['address_line1'] ?? null,
            'address_line2'    => $data['address_line2'] ?? null,
            'city'             => $data['city'] ?? null,
            'state'            => $data['state'] ?? null,
            'postal_code'      => $data['postal_code'] ?? null,
            'country_code'     => $data['country_code'] ?? null,
            'established_year' => $data['established_year'] ?? null,
            'affiliation_no'   => $data['affiliation_no'] ?? null,
            'note'             => $data['note'] ?? null,
        ]);

        $details->save();

        // Refresh tenant school cache so branding updates reflect immediately
        $cacheKey = 'tenant:domain:' . $school->domain;
        Cache::forget($cacheKey);
        Cache::put($cacheKey, [
            'id'          => $school->id,
            'name'        => $school->name,
            'domain'      => $school->domain,
            'is_active'   => $school->is_active,
            'logo_url'    => $details->logo_url,
            'favicon_url' => $details->favicon_url,
        ], now()->addMinutes(10));

        return redirect()->route('superadmin.school.settings', $school->id)
            ->with('success', 'School settings updated successfully!');
    }
    
    public function resetPasswordForm(School $school)
    {
        return view('superadmin.pages.school.reset-password', [
            'school'     => $school,
            'activeTab'  => 'reset-password',
            'pageDescription' => 'Reset the admin password for this school',
        ]);
    }

    public function resetPasswordUpdate(Request $request, School $school)
    {
        $data = $request->validate([
            'password' => [
                'required',
                PasswordRule::min(8)->max(72)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        // Find main admin user (simplest way: first user of the school)
        $adminUser = $school->users()->orderBy('created_at')->first();

        if ($adminUser) {
            $adminUser->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        return redirect()
            ->route('superadmin.school.resetPassword', $school->id)
            ->with('success', 'Password reset successfully!');
    }

}
