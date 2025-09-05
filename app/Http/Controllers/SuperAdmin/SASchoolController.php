<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class SASchoolController extends Controller
{
    protected array $baseMeta = [];

    public function __construct()
    {
        // Default meta for all School views
        $this->baseMeta = [
            'pageTitle'       => 'Schools',
            'pageDescription' => 'Manage all schools in the system',
            
        ];

        // Share to all Blade views automatically
        view()->share($this->baseMeta);
    }
    /**
     * Display a listing of schools.
     */
    public function index()
    {
        $schools = School::all();
        $schools = School::select('id','name','domain','is_active')
        ->withCount('students')
        ->orderBy('name')
        ->get();

        return view('superadmin.pages.school.list', compact('schools'));
    }

    /**
     * Show the form for creating a new school.
     */
    public function create()
    {
        return view('superadmin.pages.school.create', [
            'school' => new School(),
            'action' => route('superadmin.school.store'),
            'mode' => 'New'
        ]);
    }

    /**
     * Store a newly created school in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // Schools
            'name'             => ['required', 'string', 'max:100', 'unique:schools,name'],
            'domain'           => ['required', 'string', 'max:50', 'unique:schools,domain'],
            'is_active'        => ['boolean'],

            // School Details
            'phone'            => ['nullable', 'string', 'max:20'],
            'alt_phone'        => ['nullable', 'string', 'max:20'],
            'email'            => ['required', 'email', 'max:150'],
            'website'          => ['nullable', 'string', 'max:255'],
            'landline'         => ['nullable', 'string', 'max:255'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'favicon'          => ['nullable', 'image', 'max:1024'],
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

        // 1. Create the school
        $school = School::create([
            'name'      => $data['name'],
            'domain'    => $data['domain'],
            'is_active' => $request->boolean('is_active'),
        ]);

        // 4. Save school details
        $school->details()->create([
            'phone'        => $data['phone'] ?? null,
            'alt_phone'    => $data['alt_phone'] ?? null,
            'landline'     => $data['landline'] ?? null,
            'email'        => $data['email'] ?? null,
            'website'      => $data['website'] ?? null,
            'logo_url'     => $logoPath,
            'favicon_url'  => $faviconPath,
            'address_line1'=> $data['address_line1'] ?? null,
            'address_line2'=> $data['address_line2'] ?? null,
            'city'         => $data['city'] ?? null,
            'state'        => $data['state'] ?? null,
            'postal_code'  => $data['postal_code'] ?? null,
            'country_code' => $data['country_code'] ?? null,
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

        // 6. Create staff record
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

        // 7. Create staff folder
        $staffFolder = "{$schoolBase}/staff/{$staff->id}/profile";
        Storage::disk('public')->makeDirectory($staffFolder);
        // 2. Base folders
        $schoolBase = "schools/{$school->id}";
        Storage::disk('public')->makeDirectory("{$schoolBase}/details");
        Storage::disk('public')->makeDirectory("{$schoolBase}/staff");
        Storage::disk('public')->makeDirectory("{$schoolBase}/students");

        // 3. Upload school files
        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store("{$schoolBase}/details", 'public')
            : null;

        $faviconPath = $request->hasFile('favicon')
            ? $request->file('favicon')->store("{$schoolBase}/details", 'public')
            : null;

        // 8. Upload admin profile photo
        $adminPhotoPath = null;
        if ($request->hasFile('admin_photo')) {
            $adminPhotoPath = $request->file('admin_photo')->store($staffFolder, 'public');
            $staff->update(['photo' => $adminPhotoPath]); // store in staff.photo column
        }

        return redirect()
            ->route('superadmin.school.dashboard', [$school->id])
            ->with('success', 'School, Admin user, and folders created successfully!');
    }


    /**
     * Display the specified school.
     */
    public function show(School $school)
    {
        return view('superadmin.pages.school.base', compact('school'));
    }

    /**
     * Show the form for editing the specified school.
     */
    public function edit(School $school)
    {
        return view('superadmin.school.create', 
        [
        'school' => $school, 
        'action' => route('superadmin.school.update', $school->id),
        'mode' => 'edit'
        ]);
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('schools', 'name')->ignore($school->getKey())],
            'domain'        => ['required', 'string', 'max:20', Rule::unique('schools', 'domain')->ignore($school->getKey())],
            'is_active'   => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $school->update($data);

        return redirect()->route('superadmin.school.index')->with('success', 'School updated successfully!');
    }

    /**
     * Remove the specified school from storage.
     */
    public function destroy(School $school)
    {
        // delete logo if exists
        if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
            Storage::disk('public')->delete($school->logo_path);
        }

        $school->delete();

        return redirect()->route('superadmin.pages.schools.index')->with('success', 'School deleted successfully!');
    }

    public function dashboard(School $school)
    {
        $data = $this->sampleSchool("") + [
            'school'       => $school,
            'pageDescription' => 'Overview, trends and quick stats',
        ];
        return view('superadmin.pages.school.dashboard', $data);
    }

    public function students(School $school)
    {
        $data = $this->sampleSchool("") + [
            'school'       => $school,
            'pageDescription' => 'Students, roles and permissions',
        ];
        return view('superadmin.pages.school.students', $data);
    }

    /** Tab 3: Settings */
    public function settings(School $school)
    {
        return view('superadmin.pages.school.settings', [
            'school' => $school->load('details'), // eager load school_details
            'pageDescription' => 'School configuration',
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
        'logo'             => ['nullable', 'image', 'max:2048'],
        'favicon'          => ['nullable', 'image', 'max:1024'],
        'address_line1'    => ['nullable', 'string', 'max:255'],
        'address_line2'    => ['nullable', 'string', 'max:255'],
        'city'             => ['nullable', 'string', 'max:100'],
        'state'            => ['nullable', 'string', 'max:100'],
        'postal_code'      => ['nullable', 'string', 'max:20'],
        'country_code'     => ['nullable', 'string', 'max:10'],
        'principal_name'   => ['nullable', 'string', 'max:100'],
        'established_year' => ['nullable', 'integer'],
        'affiliation_no'   => ['nullable', 'string', 'max:100'],
        'note'             => ['nullable', 'string'],
    ]);

    // Update school
    $school->update([
        'name' => $data['name'],
        'domain' => $data['domain'],
        'is_active' => $request->boolean('is_active'),
    ]);

    $details = $school->details ?? $school->details()->make();

    // Upload logo and favicon if any
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store("schools/{$school->id}/details", 'public');
        $details->logo_url = $logoPath;
    }
    if ($request->hasFile('favicon')) {
        $faviconPath = $request->file('favicon')->store("schools/{$school->id}/details", 'public');
        $details->favicon_url = $faviconPath;
    }

    // Update school details
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

    return redirect()->route('superadmin.school.settings', $school->id)
        ->with('success', 'School settings updated successfully.');
}

    /** Static demo data for a school */
    private function sampleSchool(int|string $id): array
    {
        return [
            'schoolId'   => $id,
            'schoolName' => 'Green Valley High School',
            'schoolCode' => 'GVH-2025',
            'domain'     => 'greenvalley.edu',
            'principal'  => 'Ms. A. Nair',
            'city'       => 'Bengaluru',
            'status'     => 'Active',
            'students'   => 1248,
            'teachers'   => 82,
            'classes'    => 36,
            'sections'   => 108,
        ];
    }

}
