<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\StudentJoinApplication;
use App\Models\StudentAdmission;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Grade;
use App\Models\Section;
use App\Models\StudentJoinApplicationLog;

class StudentAdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentAdmission::with(['student','grade','section'])
            ->where('school_id', current_school_id());

        if ($request->filled('status')) {
            $query->where('status',$request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name','like',"%$search%")
                  ->orWhere('last_name','like',"%$search%");
            });
        }

        $admissions = $query->orderByDesc('created_at')->paginate(10);
        return view('tenant.pages.admissions.index', compact('admissions'));
    }

    public function create()
    {
        $grades   = Grade::get();
        $sections = Section::get();
        return view('tenant.pages.admissions.create', [
            'grades'=>$grades,'sections'=>$sections,'admission'=>new StudentAdmission()
        ]);
    }

    public function store(Request $request)
    {
        return $this->saveAdmission($request);
    }

    public function createFromApplication($school_sub, StudentJoinApplication $application)
    {
        $grades   = Grade::get();
        $sections = Section::get();

        return view('tenant.pages.admissions.create', [
            'application'=>$application,
            'grades'=>$grades,'sections'=>$sections,
            'admission'=>new StudentAdmission()
        ]);
    }

    public function storeFromApplication(Request $request, $school_sub, StudentJoinApplication $application)
    {
        return $this->saveAdmission($request, $application);
    }

    public function edit($school_sub, StudentAdmission $admission)
    {
        $grades   = Grade::get();
        $sections = Section::get();
        return view('tenant.pages.admissions.edit', compact('admission','grades','sections'));
    }

    public function update(Request $request, $school_sub, StudentAdmission $admission)
    {
        return $this->saveAdmission($request, null, $admission);
    }

    public function destroy($school_sub, StudentAdmission $admission)
    {
        $admission->delete();
        return back()->with('success','Admission deleted');
    }

    private function saveAdmission(Request $request, StudentJoinApplication $application = null, StudentAdmission $admission = null)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name'=> 'nullable|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'dob'        => 'nullable|date',
            'gender'     => 'nullable|string|max:20',
            'grade_id'   => 'required|uuid',
            'section_id' => 'nullable|uuid',
            'previous_school' => 'nullable|string|max:255',
            'remarks'    => 'nullable|string',
            'status'     => 'nullable|in:pending,offered,admitted,rejected,waitlisted,cancelled',
        ]);

        return DB::transaction(function () use ($data,$application,$admission) {
            if ($admission) {
                $admission->student->update([
                    'first_name'=>$data['first_name'],
                    'middle_name'=>$data['middle_name'] ?? null,
                    'last_name'=>$data['last_name'] ?? null,
                    'dob'=>$data['dob'],
                    'gender'=>$data['gender'],
                ]);

                $admission->update([
                    'offered_grade_id'=>$data['grade_id'],
                    'offered_section_id'=>$data['section_id'],
                    'previous_school'=>$data['previous_school'],
                    'remarks'=>$data['remarks'],
                    'status'=>$data['status'] ?? $admission->status,
                ]);

                return redirect()->to(tenant_route('tenant.admissions.index'))
                    ->with('success','Admission updated.');
            }

            // new student
            $student = Student::create([
                'id'=>Str::uuid(),
                'school_id'=>current_school_id(),
                'first_name'=>$data['first_name'],
                'middle_name'=>$data['middle_name'] ?? null,
                'last_name'=>$data['last_name'] ?? null,
                'dob'=>$data['dob'],
                'gender'=>$data['gender'],
                'admission_no'=>Str::upper('ADM'.now()->year.rand(1000,9999)),
            ]);

            $admission = StudentAdmission::create([
                'id'=>Str::uuid(),
                'school_id'=>current_school_id(),
                'academic_id'=>current_academic_id(),
                'student_id'=>$student->id,
                'source_application_id'=>$application?->id,
                'application_no'=>$application->application_no ?? ('APP'.now()->year.rand(1000,9999)),
                'status'=>$data['status'] ?? 'admitted',
                'applied_on'=>$application?->submitted_on ?? now(),
                'admitted_on'=>now(),
                'offered_grade_id'=>$data['grade_id'],
                'offered_section_id'=>$data['section_id'],
                'previous_school'=>$data['previous_school'],
                'remarks'=>$data['remarks'],
            ]);

            StudentEnrollment::create([
                'id'=>Str::uuid(),
                'student_id'=>$student->id,
                'academic_id'=>current_academic_id(),
                'grade_id'=>$data['grade_id'],
                'section_id'=>$data['section_id'],
                'joined_on'=>now(),
            ]);

            if ($application) {
                $application->update([
                    'status'=>'accepted',
                    'student_id'=>$student->id,
                ]);

                StudentJoinApplicationLog::create([
                    'id'=>Str::uuid(),
                    'application_id'=>$application->id,
                    'user_id'=>Auth::id(),
                    'action'=>"Admitted",
                    'comment'=>"Student admitted",
                ]);
            }

            return redirect()->to(tenant_route('tenant.admissions.index'))
                ->with('success','Student admitted successfully.');
        });
    }
}
