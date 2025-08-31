<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
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
            'name'        => ['required', 'string', 'max:100', 'unique:schools,name'],
            'domain'        => ['required', 'string', 'max:50', 'unique:schools,domain'],
            'is_active'   => ['boolean'],

            'admin_name'        => ['required', 'string'],
            'admin_email'        => ['required', 'string', 'max:50', 'unique:users,email'],
            'password'    => [
            'required','string',
                PasswordRule::min(8)->max(72)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $school = School::create([
            'name'      => $data['name'],
            'domain'    => $data['domain'],
            'is_active' => $data['is_active'] ?? false,
        ]);

        $adminUser = User::create([
            'school_id' => $school->id,
            'full_name' => $data['admin_name'],
            'email' => $data['admin_email'],
            'password' => Hash::make($data['password'])
        ]
        );

        return redirect()->route('superadmin.school.dashboard',[$school->id])->with('success', 'School created successfully!');
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

    public function dashboard(School $schoolId)
    {
        $data = $this->sampleSchool("") + [
            'school'       => $schoolId,
            'pageDescription' => 'Overview, trends and quick stats',
        ];
        return view('superadmin.pages.school.dashboard', $data);
    }

    public function students(School $schoolId)
    {
        $data = $this->sampleSchool("") + [
            'school'       => $schoolId,
            'pageDescription' => 'Students, roles and permissions',
        ];
        return view('superadmin.pages.school.students', $data);
    }

    /** Tab 3: Settings */
    public function settings(School $schoolId)
    {
        $data = $this->sampleSchool("") + [
            'school'       => $schoolId,
            'pageDescription' => 'School configuration',
        ];
        return view('superadmin.pages.school.settings', $data);
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
