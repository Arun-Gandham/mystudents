<?php
namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentJoinApplication;
use App\Models\StudentAdmission;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentAdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    /** List admissions */
    public function index(Request $request)
    {
        $query = StudentAdmission::with(['student','grade','section'])
            ->where('school_id', current_school_id());

        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('full_name','like','%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status',$request->status);
        }

        $admissions = $query->orderBy('created_at','desc')->paginate(10);

        return view('tenant.pages.admissions.index', compact('admissions'));
    }

    /** Direct create */
    public function create()
    {
        return view('tenant.pages.admissions.create', [
            'admission' => new StudentAdmission(),
            'application' => null
        ]);
    }

    /** Direct store */
    public function store(Request $request)
    {
        return $this->saveAdmission($request);
    }

    /** Create from Application */
    public function createFromApplication($school_sub, StudentJoinApplication $application)
    {
        return view('tenant.pages.admissions.create', [
            'admission'   => new StudentAdmission(),
            'application' => $application
        ]);
    }

    /** Store from Application */
    public function storeFromApplication(Request $request, $school_sub, StudentJoinApplication $application)
    {
        return $this->saveAdmission($request, $application);
    }

    /** Edit admission */
    public function edit($school_sub, StudentAdmission $admission)
    {
        $student = $admission->student;      // linked student
        $application = $admission->student->sourceApplication ?? null; // if any

        return view('tenant.pages.admissions.edit', compact('admission','student','application'));
    }

    /** Update admission */
    public function update(Request $request, $school_sub, StudentAdmission $admission)
    {
        return $this->saveAdmission($request, null, $admission);
    }

    /** Delete admission */
    public function destroy($school_sub, StudentAdmission $admission)
    {
        $admission->delete();
        return back()->with('success','Admission deleted');
    }

    /** Common save logic */
    private function saveAdmission(Request $request, StudentJoinApplication $application = null, StudentAdmission $admission = null)
    {
        $data = $request->validate([
            // Student
            'full_name' => ['required','string','max:150'],
            'dob'       => ['nullable','date'],
            'gender'    => ['nullable','string','max:10'],

            // Guardian
            'guardian_full_name'=> ['nullable','string','max:150'],
            'guardian_relation' => ['nullable','string','max:100'],
            'guardian_email'    => ['nullable','email'],
            'guardian_phone'    => ['nullable','string','max:20'],
            'address'           => ['nullable','string','max:500'],

            // Admission
            'grade_id'   => ['required','uuid'],
            'section_id' => ['nullable','uuid'],
            'previous_school' => ['nullable','string','max:255'],
            'remarks'   => ['nullable','string'],
            'status'    => ['nullable','in:pending,offered,admitted,rejected,waitlisted,cancelled'],
        ]);

        // If editing admission
        if ($admission) {
            $student = $admission->student;
            $student->update([
                'full_name' => $data['full_name'],
                'dob'       => $data['dob'],
                'gender'    => $data['gender'],
            ]);
            $admission->update([
                'offered_grade_id'=>$data['grade_id'],
                'offered_section_id'=>$data['section_id'],
                'previous_school'=>$data['previous_school'],
                'remarks'=>$data['remarks'],
                'status'=>$data['status'] ?? $admission->status,
            ]);
            return redirect()->to(tenant_route('tenant.admissions.index'))->with('success','Admission updated.');
        }

        // Create new student
        $student = Student::create([
            'school_id' => current_school_id(),
            'full_name' => $data['full_name'],
            'dob'       => $data['dob'],
            'gender'    => $data['gender'],
            'admission_no' => Str::upper('ADM'.now()->year.rand(1000,9999)),
            'source_application_id' => $application?->id,
        ]);

        $applicationNo = $request->input('application_no') 
            ?? 'APP'.now()->year.rand(1000,9999);

        // Create admission
        $admission = StudentAdmission::create([
            'school_id'   => current_school_id(),
            'academic_id' => current_academic_id(),
            'student_id'  => $student->id,
            'application_no' => $applicationNo,
            'status' => $data['status'] ?? 'admitted',
            'applied_on'=> $application?->submitted_on ?? now(),
            'admitted_on'=> now(),
            'offered_grade_id'=>$data['grade_id'],
            'offered_section_id'=>$data['section_id'],
            'previous_school'=>$data['previous_school'],
            'remarks'=>$data['remarks'],
        ]);

        // Create enrollment
        StudentEnrollment::create([
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
                'guardian_full_name'=>$data['guardian_full_name'],
                'guardian_relation'=>$data['guardian_relation'],
                'guardian_email'=>$data['guardian_email'],
                'guardian_phone'=>$data['guardian_phone'],
                'address'=>$data['address'],
            ]);
        }

        return redirect()->route('tenant.admissions.index')->with('success','Student admitted successfully.');
    }
}
