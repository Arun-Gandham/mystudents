<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\StudentJoinApplication;
use App\Models\Grade;
use App\Models\Section;
use App\Models\StudentJoinApplicationLog;

class StudentApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentJoinApplication::with(['preferredGrade','preferredSection'])
            ->where('school_id', current_school_id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($sub) use ($q) {
                $sub->where('first_name','like',"%$q%")
                    ->orWhere('last_name','like',"%$q%")
                    ->orWhere('application_no','like',"%$q%")
                    ->orWhere('guardian_name','like',"%$q%");
            });
        }

        $applications = $query->orderByDesc('created_at')->paginate(10);

        return view('tenant.pages.student_applications.index', compact('applications'));
    }

    public function create()
    {
        $grades   = Grade::get();
        $sections = Section::get();
        return view('tenant.pages.student_applications.create', compact('grades','sections'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'first_name'        => 'required|string|max:100',
        'middle_name'       => 'nullable|string|max:100',
        'last_name'         => 'nullable|string|max:100',
        'dob'               => 'nullable|date',
        'gender'            => 'nullable|string|max:20',
        'previous_school'   => 'nullable|string|max:255',
        'guardian_name'     => 'required|string|max:150',
        'guardian_relation' => 'nullable|string|max:50',
        'guardian_email'    => 'nullable|email|max:150',
        'guardian_phone'    => 'nullable|string|max:20',
        'address'           => 'nullable|string',
        'preferred_grade_id'=> 'nullable|uuid',
        'preferred_section_id'=> 'nullable|uuid',
    ]);

    DB::transaction(function () use ($data) {
        $data['id']            = Str::uuid();
        $data['school_id']     = current_school_id();
        $data['academic_id']   = current_academic_id();
        $data['application_no']= 'APP-' . strtoupper(Str::random(6));
        $data['status']        = 'submitted';

        $application = StudentJoinApplication::create($data);

        StudentJoinApplicationLog::create([
            'id'            => Str::uuid(),
            'application_id'=> $application->id,
            'user_id'       => Auth::id(),
            'action'        => "Application Submitted",
            'comment'       => "Student submitted join application",
        ]);
    });

    return redirect()
        ->to(tenant_route('tenant.applications.index'))
        ->with('success', 'Application created successfully!');
}


    public function show(string $school_sub, StudentJoinApplication $application)
    {
        $application->load(['preferredGrade','preferredSection','logs' => fn($q) => $q->latest()]);
        return view('tenant.pages.student_applications.show', compact('application'));
    }

    public function edit(string $school_sub, StudentJoinApplication $application)
    {
        $grades   = Grade::get();
        $sections = Section::get();
        return view('tenant.pages.student_applications.edit', compact('application','grades','sections'));
    }

    public function update(Request $request, string $school_sub, StudentJoinApplication $application)
    {
        $data = $request->validate([
            'first_name'        => 'required|string|max:100',
            'middle_name'       => 'nullable|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'dob'               => 'nullable|date',
            'gender'            => 'nullable|string|max:20',
            'previous_school'   => 'nullable|string|max:255',
            'guardian_name'     => 'required|string|max:150',
            'guardian_relation' => 'nullable|string|max:50',
            'guardian_email'    => 'nullable|email|max:150',
            'guardian_phone'    => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'preferred_grade_id'=> 'nullable|uuid',
            'preferred_section_id'=> 'nullable|uuid',
            'status'            => 'required|string',
        ]);

        $application->update($data);

        return redirect()->to(
            tenant_route('tenant.applications.show',$application->id)
        )->with('success','Application updated successfully!');
    }

    public function destroy(string $school_sub, StudentJoinApplication $application)
    {
        $application->delete();
        return redirect()->to(
            tenant_route('tenant.applications.index')
        )->with('success','Application deleted.');
    }

    public function addLog(Request $request, string $school_sub, StudentJoinApplication $application)
    {
        $request->validate([
            'action'  => 'required|string|max:255',
            'comment' => 'nullable|string',
        ]);

        StudentJoinApplicationLog::create([
            'id'            => Str::uuid(),
            'application_id'=> $application->id,
            'user_id'       => Auth::id(),
            'action'        => $request->action,
            'comment'       => $request->comment,
        ]);

        return back()->with('success','Log added successfully!');
    }
}
