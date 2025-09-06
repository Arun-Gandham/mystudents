<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    /** =========================
     * LIST STUDENTS (with Ajax filter)
     * ========================= */
    public function index(Request $request)
{
    $query = Student::with(['enrollments.grade', 'enrollments.section'])
        ->where('school_id', current_school_id());

    // ✅ Apply search
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('full_name', 'like', '%'.$request->search.'%')
              ->orWhere('admission_no', 'like', '%'.$request->search.'%')
              ->orWhereHas('enrollments', function ($sub) use ($request) {
                  $sub->where('roll_no', 'like', '%'.$request->search.'%');
              });
        });
    }

    // ✅ Apply grade filter
    if ($request->filled('grade_id')) {
        $query->whereHas('enrollments', function ($q) use ($request) {
            $q->where('grade_id', $request->grade_id);
        });
    }

    // ✅ Apply section filter
    if ($request->filled('section_id')) {
        $query->whereHas('enrollments', function ($q) use ($request) {
            $q->where('section_id', $request->section_id);
        });
    }

    // ✅ Paginate
    $students = $query->orderBy('full_name')->paginate(10);

    // ✅ If AJAX, return partial only
    if ($request->ajax()) {
        return view('tenant.pages.students.partials.table', compact('students'))->render();
    }

    return view('tenant.pages.students.index', compact('students'));
}


    /** =========================
     * CREATE FORM
     * ========================= */
    public function create()
    {
        $grades = Grade::forSchool(current_school_id())->get();
        $sections = Section::forSchool(current_school_id())->get();
        return view('tenant.pages.students.create', compact('grades','sections'));
    }

    /** =========================
     * STORE NEW STUDENT
     * ========================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'   => 'required|string|max:150',
            'dob'         => 'nullable|date',
            'gender'      => 'nullable|string|max:10',
            'grade_id'    => 'required|uuid',
            'section_id'  => 'nullable|uuid',
        ]);

        $student = Student::create([
            'school_id'    => current_school_id(),
            'full_name'    => $data['full_name'],
            'dob'          => $data['dob'],
            'gender'       => $data['gender'],
            'admission_no' => Str::upper('ADM'.now()->year.rand(1000,9999)),
        ]);

        $student->enrollments()->create([
            'academic_id' => current_academic_id(),
            'grade_id'    => $data['grade_id'],
            'section_id'  => $data['section_id'],
            'joined_on'   => now(),
        ]);

        return redirect()->route('tenant.students.index')
                         ->with('success','Student created successfully');
    }

    /** =========================
     * SHOW PROFILE
     * ========================= */
    public function show($school_sub, Student $student)
    {
        $student->load(['enrollments.grade','enrollments.section']);
        return view('tenant.pages.students.show', compact('student'));
    }

    /** =========================
     * EDIT FORM
     * ========================= */
    public function edit($school_sub, Student $student)
    {
        $grades = Grade::forSchool(current_school_id())->get();
        $sections = Section::forSchool(current_school_id())->get();
        return view('tenant.pages.students.edit', compact('student','grades','sections'));
    }

    /** =========================
     * UPDATE STUDENT
     * ========================= */
    public function update(Request $request, $school_sub, Student $student)
    {
        $data = $request->validate([
            'full_name'   => 'required|string|max:150',
            'dob'         => 'nullable|date',
            'gender'      => 'nullable|string|max:10',
            'grade_id'    => 'required|uuid',
            'section_id'  => 'nullable|uuid',
        ]);

        $student->update([
            'full_name' => $data['full_name'],
            'dob'       => $data['dob'],
            'gender'    => $data['gender'],
        ]);

        // update enrollment
        $enrollment = $student->enrollments()->where('academic_id',current_academic_id())->first();
        if ($enrollment) {
            $enrollment->update([
                'grade_id'   => $data['grade_id'],
                'section_id' => $data['section_id'],
            ]);
        }

        return redirect()->route('tenant.students.index')
                         ->with('success','Student updated successfully');
    }

    /** =========================
     * DELETE STUDENT
     * ========================= */
    public function destroy($school_sub, Student $student)
    {
        $student->delete();
        return redirect()->route('tenant.students.index')
                         ->with('success','Student deleted successfully');
    }
}
