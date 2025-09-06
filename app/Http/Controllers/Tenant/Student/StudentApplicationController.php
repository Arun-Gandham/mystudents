<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentJoinApplication;
use App\Models\Grade;
use App\Models\Section;

class StudentApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    /**
     * Display a listing of applications (with search + filter).
     */
    public function index(Request $request)
    {
        $query = StudentJoinApplication::query()->with(['preferredGrade','preferredSection']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status',$request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $q = $request->search;
            $query->where(function($sub) use ($q){
                $sub->where('child_full_name','like',"%$q%")
                    ->orWhere('guardian_full_name','like',"%$q%")
                    ->orWhere('application_no','like',"%$q%");
            });
        }

        $applications = $query->orderByDesc('created_at')->paginate(10);

        return view('tenant.pages.student_applications.index', compact('applications'));
    }

    /**
     * Show form to create application.
     */
    public function create()
    {
        $grades   = Grade::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        return view('tenant.pages.student_applications.create', compact('grades','sections'));
    }

    /**
     * Store new application.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'child_full_name'   => 'required|string|max:150',
            'child_dob'         => 'nullable|date',
            'child_gender'      => 'nullable|string|max:20',
            'previous_school'   => 'nullable|string|max:255',
            'guardian_full_name'=> 'required|string|max:150',
            'guardian_relation' => 'nullable|string|max:50',
            'guardian_email'    => 'nullable|email|max:150',
            'guardian_phone'    => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'preferred_grade_id'=> 'nullable|uuid',
            'preferred_section_id'=> 'nullable|uuid',
        ]);

        $data['school_id'] = current_school_id();
        $data['academic_id'] = current_academic_id();
        $data['application_no'] = 'APP-'.strtoupper(uniqid());

        StudentJoinApplication::create($data);

        return redirect()->to(tenant_route('tenant.applications.index'))->with('success','Application created successfully!');
    }

    /**
     * Show details.
     */
    public function show(string $school_sub, StudentJoinApplication $application)
    {
        return view('tenant.pages.student_applications.show', compact('application'));
    }

    /**
     * Edit form.
     */
    public function edit(string $school_sub, StudentJoinApplication $application)
    {
        $grades   = Grade::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        return view('tenant.pages.student_applications.edit', compact('application','grades','sections'));
    }

    /**
     * Update application.
     */
    public function update(Request $request,$school_sub, StudentJoinApplication $application)
    {
        $data = $request->validate([
            'child_full_name'   => 'required|string|max:150',
            'child_dob'         => 'nullable|date',
            'child_gender'      => 'nullable|string|max:20',
            'previous_school'   => 'nullable|string|max:255',
            'guardian_full_name'=> 'required|string|max:150',
            'guardian_relation' => 'nullable|string|max:50',
            'guardian_email'    => 'nullable|email|max:150',
            'guardian_phone'    => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'preferred_grade_id'=> 'nullable|uuid',
            'preferred_section_id'=> 'nullable|uuid',
            'status'            => 'required|string',
        ]);

        $application->update($data);

        return redirect()->to(tenant_route('tenant.applications.index'))->with('success','Application updated successfully!');
    }

    /**
     * Delete application.
     */
    public function destroy(StudentJoinApplication $application)
    {
        $application->delete();
        return back()->with('success','Application deleted.');
    }
}
