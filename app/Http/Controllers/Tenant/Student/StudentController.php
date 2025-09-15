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
        return view('tenant.pages.students.index', compact('students'));
    }

    public function create()
    {
        $grades = Grade::where('school_id', current_school_id())->get();
        $sections = collect(); // empty for new student
        return view('tenant.pages.students.create', compact('grades','sections'));
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

    return redirect()->to(tenant_route('tenant.students.show',['student' => $student->id]))
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

        return view('tenant.pages.students.edit', compact('student','grades','sections'));
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

    return redirect()->to(tenant_route('tenant.students.show',['student' => $student->id]))
        ->with('success','Student updated successfully');
}


    public function destroy($school_sub, Student $student)
    {
        $student->delete();
        return back()->with('success','Student deleted successfully');
    }

    public function show($school_sub, Student $student)
{
    $student->load([
        'enrollments.grade',
        'enrollments.section',
        'guardians',
        'addresses',
        'documents'
    ]);

    return view('tenant.pages.students.show', compact('student'));
}
}
