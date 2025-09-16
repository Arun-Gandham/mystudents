<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentGuardian;
use App\Models\StudentAddress;
use App\Models\StudentDocument;
use App\Models\StudentEnrollment;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\StudentAdmission;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function index(Request $request)
    {
        $query = Student::with(['enrollments.grade','enrollments.section'])
            ->where('school_id', current_school_id());

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($sub) use ($q) {
                $sub->where('first_name','like',"%$q%")
                    ->orWhere('last_name','like',"%$q%")
                    ->orWhere('admission_no','like',"%$q%");
            });
        }

        $students = $query->orderBy('first_name')->paginate(10);
        return view('Tenant.pages.Students.index', compact('students'));
    }

    public function create()
    {
        $grades = Grade::where('school_id', current_school_id())->get();
        $sections = collect(); // empty for new student
        return view('Tenant.pages.Students.create', compact('grades','sections'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'first_name'   => 'required|string|max:100',
        'middle_name'  => 'nullable|string|max:100',
        'last_name'    => 'nullable|string|max:100',
        'dob'          => 'nullable|date',
        'gender'       => 'nullable|string|max:20',
        'aadhaar_no'   => 'nullable|string|max:12|unique:students,aadhaar_no',
        'religion'     => 'nullable|string|max:100',
        'caste'        => 'nullable|string|max:100',
        'category'     => 'nullable|string|max:100',
        'blood_group'  => 'nullable|string|max:10',
        'phone'        => 'nullable|string|max:20',
        'email'        => 'nullable|email|max:150',
        'photo'        => 'nullable|image|max:2048',

        // Enrollment fields
        'grade_id'     => 'required|uuid',
        'section_id'   => 'nullable|uuid',
    ]);

    try {
        $student = DB::transaction(function () use ($request, $data) {
        // ✅ Photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store("students/photos", 'public');
        }

        // ✅ Separate enrollment fields
        $enrollmentData = [
            'grade_id'   => $data['grade_id'],
            'section_id' => $data['section_id'],
        ];
        unset($data['grade_id'], $data['section_id']);

        // ✅ Student core record
        $data['school_id'] = current_school_id();
        $data['admission_no'] = 'ADM' . now()->year . rand(1000,9999);

        $student = Student::create($data);

        // ✅ Enrollment
        StudentEnrollment::create([
            'student_id' => $student->id,
            'academic_id'=> current_academic_id(),
            'grade_id'   => $enrollmentData['grade_id'],
            'section_id' => $enrollmentData['section_id'],
            'joined_on'  => now(),
        ]);

        StudentAdmission::create([
            'school_id'     => current_school_id(),
            'academic_id'   => current_academic_id(),
            'student_id'    => $student->id,
            'application_no'=> 'APP-' . strtoupper(Str::random(6)),
            'status'        => 'admitted',
            'admitted_on'   => now(),
            'offered_grade_id'   => $enrollmentData['grade_id'],
            'offered_section_id' => $enrollmentData['section_id'],
            'remarks'       => 'Auto-created on student admission',
        ]);

        // ✅ Guardians
        foreach ($request->guardians ?? [] as $g) {
            StudentGuardian::create([
                'student_id'=>$student->id,
                'full_name'=>$g['full_name'] ?? null,
                'relation'=>$g['relation'] ?? null,
                'phone_e164'=>$g['phone'] ?? null,
                'email'=>$g['email'] ?? null,
                'address'=>$g['address'] ?? null,
                'is_primary'=>!empty($g['is_primary']),
            ]);
        }

        // ✅ Addresses
        foreach ($request->addresses ?? [] as $a) {
            StudentAddress::create([
                'student_id'=>$student->id,
                'address_line1'=>$a['address_line1'] ?? null,
                'city'=>$a['city'] ?? null,
                'state'=>$a['state'] ?? null,
                'pincode'=>$a['pincode'] ?? null,
                'address_type'=>$a['address_type'] ?? 'current',
            ]);
        }

        // ✅ Documents
        foreach ($request->documents ?? [] as $d) {
            if (!empty($d['file'])) {
                $path = $d['file']->store("students/documents", 'public');
                StudentDocument::create([
                    'student_id'=>$student->id,
                    'doc_type'=>$d['doc_type'] ?? 'other',
                    'file_path'=>$path,
                ]);
            }
        }
        return $student;
    });

    return redirect()->to(tenant_route('tenant.students.show',['id' => $student->id]))
        ->with('success','Student added successfully');
        } catch (\Throwable $e) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Failed to save student: '.$e->getMessage()]);
    }
}


    public function edit($school_sub, Student $student)
    {
        $student->load(['enrollments'=>function($q){
            $q->where('academic_id',current_academic_id());
        },'guardians','addresses','documents']);
        $grades = Grade::where('school_id', current_school_id())->get();
        $sections = Section::where('grade_id', optional($student->enrollments->first())->grade_id)->get();

        return view('Tenant.pages.Students.edit', compact('student','grades','sections'));
    }

    public function update(Request $request, $school_sub, Student $student)
{
    $data = $request->validate([
        'first_name'   => 'required|string|max:100',
        'middle_name'  => 'nullable|string|max:100',
        'last_name'    => 'nullable|string|max:100',
        'dob'          => 'nullable|date',
        'gender'       => 'nullable|string|max:20',
        'aadhaar_no'   => 'nullable|string|max:12|unique:students,aadhaar_no,' . $student->id,
        'religion'     => 'nullable|string|max:100',
        'caste'        => 'nullable|string|max:100',
        'category'     => 'nullable|string|max:100',
        'blood_group'  => 'nullable|string|max:10',
        'phone'        => 'nullable|string|max:20',
        'email'        => 'nullable|email|max:150',
        'photo'        => 'nullable|image|max:2048',

        // Enrollment
        'grade_id'     => 'required|uuid',
        'section_id'   => 'nullable|uuid',
    ]);

    DB::transaction(function () use ($request, $data, $student) {
        // ✅ Photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store("students/photos", 'public');
        }

        // ✅ Separate enrollment fields
        $enrollmentData = [
            'grade_id'   => $data['grade_id'],
            'section_id' => $data['section_id'],
        ];
        unset($data['grade_id'], $data['section_id']);

        // ✅ Update student core
        $student->update($data);

        // ✅ Enrollment update
        $enrollment = $student->enrollments()->where('academic_id',current_academic_id())->first();
        if ($enrollment) {
            $enrollment->update($enrollmentData);
        } else {
            $student->enrollments()->create(array_merge($enrollmentData, [
                'academic_id'=>current_academic_id(),
                'joined_on'=>now(),
            ]));
        }

        // ✅ Replace guardians
        $student->guardians()->delete();
        foreach ($request->guardians ?? [] as $g) {
            StudentGuardian::create([
                'student_id'=>$student->id,
                'full_name'=>$g['full_name'] ?? null,
                'relation'=>$g['relation'] ?? null,
                'phone_e164'=>$g['phone'] ?? null,
                'email'=>$g['email'] ?? null,
                'address'=>$g['address'] ?? null,
                'is_primary'=>!empty($g['is_primary']),
            ]);
        }

        // ✅ Replace addresses
        $student->addresses()->delete();
        foreach ($request->addresses ?? [] as $a) {
            StudentAddress::create([
                'student_id'=>$student->id,
                'address_line1'=>$a['address_line1'] ?? null,
                'city'=>$a['city'] ?? null,
                'state'=>$a['state'] ?? null,
                'pincode'=>$a['pincode'] ?? null,
                'address_type'=>$a['address_type'] ?? 'current',
            ]);
        }

        // Handle documents update
        if ($request->has('documents')) {
            foreach ($request->documents as $i => $d) {
                // If document has an id → update existing
                if (!empty($d['id'])) {
                    $doc = $student->documents()->where('id', $d['id'])->first();
                    if ($doc) {
                        // If new file uploaded → replace
                        if (!empty($d['file'])) {
                            $path = $d['file']->store("students/documents", 'public');
                            $doc->update([
                                'doc_type'  => $d['doc_type'] ?? $doc->doc_type,
                                'file_path' => $path,
                            ]);
                        } else {
                            // Update type only
                            $doc->update([
                                'doc_type' => $d['doc_type'] ?? $doc->doc_type,
                            ]);
                        }
                    }
                } 
                // If no id → it's a new document
                else if (!empty($d['file'])) {
                    $path = $d['file']->store("students/documents", 'public');
                    StudentDocument::create([
                        'student_id' => $student->id,
                        'doc_type'   => $d['doc_type'] ?? 'other',
                        'file_path'  => $path,
                    ]);
                }
            }
        }
    });

    return redirect()->to(tenant_route('tenant.students.show',['id' => $student->id]))
        ->with('success','Student updated successfully');
}


    public function destroy($school_sub, Student $student)
    {
        $student->delete();
        return back()->with('success','Student deleted successfully');
    }

    public function show($school_sub, $id)
    {
        $student = Student::with(['enrollments.grade','enrollments.section','guardians','addresses'])
            ->withCount(['guardians','documents','attendanceEntries'])
            ->findOrFail($id);
        $currentEnrollment = $student->enrollments
            ->where('academic_id', current_academic_id())
            ->first();
        $primaryGuardian = $student->guardians->firstWhere('is_primary', true);
        $currentAddress = $student->addresses
            ->sortBy(function($a){ return $a->address_type === 'current' ? 0 : 1; })
            ->first();
        return view('Tenant.pages.Students.show', compact('student','currentEnrollment','primaryGuardian','currentAddress'));
    }

    public function overview($school_sub, $id)
    {
        $student = Student::findOrFail($id);
        $attendanceSummary = $student->attendanceEntries()
            ->whereHas('sheet', function($q){
                $q->where('academic_id', current_academic_id());
            })
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status');

        return view('Tenant.pages.Students.tabs.overview', compact('student','attendanceSummary'));
    }

    public function attendance($school_sub, $id)
    {
        $student = Student::findOrFail($id);
        $recentAttendance = $student->attendanceEntries()
            ->with(['sheet'])
            ->whereHas('sheet', function($q){
                $q->where('academic_id', current_academic_id());
            })
            ->latest('created_at')
            ->limit(30)
            ->get();
        return view('Tenant.pages.Students.tabs.attendance', compact('student','recentAttendance'));
    }

    public function performance($school_sub, $id)
    {
        $student = Student::findOrFail($id);
        $subjectMarks = \App\Models\ExamResult::where('student_id', $student->id)
            ->with('subject')
            ->get()
            ->groupBy(function($r){ return $r->subject?->name ?? 'Unknown'; })
            ->map(function($g){ return round($g->avg('marks_obtained'),2); });

        return view('Tenant.pages.Students.tabs.performance', compact('student','subjectMarks'));
    }

    public function behavior($school_sub, $id)
    {
        $student = Student::findOrFail($id);
        return view('Tenant.pages.Students.tabs.behavior', compact('student'));
    }

    public function documents($school_sub, $id)
    {
        $student = Student::findOrFail($id);
        $documents = $student->documents()->latest('created_at')->get();
        return view('Tenant.pages.Students.tabs.documents', compact('student','documents'));
    }

    public function timetable($school_sub, $id)
    {
        $student = Student::with(['enrollments.section'])->findOrFail($id);
        $currentEnrollment = $student->enrollments()
            ->where('academic_id', current_academic_id())
            ->with('section')
            ->first();

        $timetables = collect();
        if ($currentEnrollment && $currentEnrollment->section_id) {
            $timetables = \App\Models\SectionDayTimetable::where('school_id', current_school_id())
                ->where('academic_id', current_academic_id())
                ->where('section_id', $currentEnrollment->section_id)
                ->where('is_active', true)
                ->with(['periods.subject','periods.teacher'])
                ->orderByRaw("FIELD(day, 'Mon','Tue','Wed','Thu','Fri','Sat','Sun')")
                ->get()
                ->groupBy('day');
        }

        return view('Tenant.pages.Students.tabs.timetable', compact('student','currentEnrollment','timetables'));
    }

    public function guardians($school_sub, $id)
    {
        $student = Student::with('guardians')->findOrFail($id);
        return view('Tenant.pages.Students.tabs.guardians', compact('student'));
    }

}
