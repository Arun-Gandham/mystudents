<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendanceSheet;
use App\Models\StudentAttendanceEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $academicId = current_academic_id();
        $date       = $request->input('date');
        $gradeId    = $request->input('grade_id');
        $sectionId  = $request->input('section_id');

        $query = \App\Models\StudentAttendanceSheet::with(['section.grade','entries'])
            ->where('academic_id', $academicId);

        // Apply filters
        if ($date) {
            $query->whereDate('attendance_date', $date);
        }
        if ($gradeId) {
            $query->whereHas('section', function($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        }
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        // Sort latest first
        $sheets = $query->orderByDesc('attendance_date')->paginate(15);

        $grades   = \App\Models\Grade::forSchool(current_school_id())->get();
        $sections = $gradeId
            ? \App\Models\Section::where('grade_id', $gradeId)->get()
            : collect(); // empty until grade selected

        return view('tenant.pages.student_attendance.index', compact(
            'sheets','grades','sections','date','gradeId','sectionId'
        ));
    }

    public function create(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $gradeId = $request->get('grade_id');
        $sectionId = $request->get('section_id');
        $session = $request->get('session', 'morning');

        $grades = Grade::forSchool(current_school_id())->get();
        $sections = $gradeId ? Section::where('grade_id', $gradeId)->get() : collect();

        $students = $sectionId
            ? Student::where('school_id', current_school_id())
                ->whereHas('enrollments', fn($q) => $q->where('section_id', $sectionId)->where('academic_id', current_academic_id()))
                ->get()
            : collect();

        return view('tenant.pages.student_attendance.create', compact('date', 'grades', 'sections', 'gradeId', 'sectionId', 'session', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'       => 'required|date|before_or_equal:today',
            'grade_id'   => 'required|uuid',
            'section_id' => 'required|uuid',
            'session'    => 'required|in:morning,afternoon,both',
            'students'   => 'required|array',
        ]);

        DB::transaction(function () use ($data, $request) {
            $sessions = $data['session'] == 'both' ? ['morning', 'afternoon'] : [$data['session']];

            foreach ($sessions as $sess) {
                $sheet = StudentAttendanceSheet::updateOrCreate(
                    [
                        'school_id'       => current_school_id(),
                        'academic_id'     => current_academic_id(),
                        'section_id'      => $data['section_id'],
                        'attendance_date' => $data['date'],
                        'session'         => $sess,
                    ],
                    [
                        'taken_by' => auth()->id(),
                        'taken_at' => now(),
                    ]
                );

                foreach ($data['students'] as $studentId => $entry) {
                    StudentAttendanceEntry::updateOrCreate(
                        [
                            'sheet_id'   => $sheet->id,
                            'student_id' => $studentId,
                        ],
                        [
                            'status'   => $entry['status'] ?? 'present',
                            'remarks'  => $entry['remarks'] ?? null,
                            'check_in' => $entry['check_in'] ?? null,
                            'check_out'=> $entry['check_out'] ?? null,
                        ]
                    );
                }
            }
        });

        return redirect()->to(tenant_route('tenant.studentAttendance.index'))->with('success', 'Attendance saved successfully.');
    }

    public function edit($school_sub, StudentAttendanceSheet $sheet)
    {
        $students = Student::where('school_id', current_school_id())
            ->whereHas('enrollments', fn($q) => $q->where('section_id', $sheet->section_id)->where('academic_id', current_academic_id()))
            ->get();

        $entries = $sheet->entries->keyBy('student_id');

        return view('tenant.pages.student_attendance.edit', compact('sheet', 'students', 'entries'));
    }

    public function update(Request $request,$school_sub, StudentAttendanceSheet $sheet)
    {
        $data = $request->validate([
            'students'   => 'required|array',
        ]);

        DB::transaction(function () use ($sheet, $data) {
            foreach ($data['students'] as $studentId => $entry) {
                StudentAttendanceEntry::updateOrCreate(
                    [
                        'sheet_id'   => $sheet->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'status'   => $entry['status'] ?? 'present',
                        'remarks'  => $entry['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()->to(tenant_route('tenant.studentAttendance.index'))->with('success', 'Attendance updated.');
    }

    public function view($school_sub,StudentAttendanceSheet $sheet)
    {
        $entries = $sheet->entries()->with('student')->get();
        return view('tenant.pages.student_attendance.view', compact('sheet', 'entries'));
    }

    public function copyMorning(Request $request)
    {
        $data = $request->validate([
            'date'       => 'required|date|before_or_equal:today',
            'section_id' => 'required|uuid',
        ]);

        DB::transaction(function () use ($data) {
            $morning = StudentAttendanceSheet::where('school_id', current_school_id())
                ->where('section_id', $data['section_id'])
                ->where('academic_id', current_academic_id())
                ->where('attendance_date', $data['date'])
                ->where('session', 'morning')
                ->first();

            if (!$morning) {
                throw new \Exception("No morning attendance found for this section.");
            }

            $afternoon = StudentAttendanceSheet::updateOrCreate(
                [
                    'school_id'       => current_school_id(),
                    'academic_id'     => current_academic_id(),
                    'section_id'      => $data['section_id'],
                    'attendance_date' => $data['date'],
                    'session'         => 'afternoon',
                ],
                [
                    'taken_by' => auth()->id(),
                    'taken_at' => now(),
                ]
            );

            foreach ($morning->entries as $entry) {
                StudentAttendanceEntry::updateOrCreate(
                    [
                        'sheet_id'   => $afternoon->id,
                        'student_id' => $entry->student_id,
                    ],
                    [
                        'status'  => $entry->status,
                        'remarks' => $entry->remarks,
                    ]
                );
            }
        });

        return back()->with('success', 'Afternoon attendance copied from morning.');
    }
}
