<?php

namespace App\Http\Controllers\Tenant\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamGrade;
use App\Models\Student;

class ExamResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    public function edit($school_sub, Exam $exam)
    {
        $exam->load(['subjects.subject','results','grades','section.enrollments.student']);
        $students = $exam->section->enrollments->pluck('student');
        return view('tenant.pages.exams.results', compact('exam','students'));
    }

    public function update(Request $request, $school_sub, Exam $exam)
    {
        $request->validate([
            'results.*.student_id'     => 'required|uuid|exists:students,id',
            'results.*.subject_id'     => 'required|uuid|exists:subjects,id',
            'results.*.marks_obtained' => 'required|numeric|min:0',
        ]);

        $grading = $exam->grades;

        DB::transaction(function () use ($request, $exam, $grading) {
            foreach ($request->results as $row) {
                $marks = $row['marks_obtained'];

                ExamResult::updateOrCreate(
                    [
                        'exam_id'    => $exam->id,
                        'student_id' => $row['student_id'],
                        'subject_id' => $row['subject_id'],
                    ],
                    [
                        'marks_obtained' => $marks,
                        'entered_by'     => auth()->id(),
                        'entered_at'     => now(),
                    ]
                );
            }

            // Compute overall totals
            $students = Student::whereHas('enrollments', fn($q)=>$q->where('section_id',$exam->section_id))->get();

            foreach ($students as $student) {
                $results = ExamResult::where('exam_id',$exam->id)->where('student_id',$student->id)->get();
                $totalObtained = $results->sum('marks_obtained');
                $totalMax      = $exam->subjects->sum('max_marks');

                $grade = null;
                if ($grading->count()) {
                    $rule = $grading->first(fn($g)=>$totalObtained >= $g->min_mark && $totalObtained <= $g->max_mark);
                    $grade = $rule?->grade;
                }

                // Save overall as a "virtual" record, or separate table if you created exam_overall_results
                $student->overall_result = [
                    'total_obtained'=>$totalObtained,
                    'total_max'=>$totalMax,
                    'overall_grade'=>$grade
                ];
            }
        });

        return redirect()->to(tenant_route('tenant.exams.show',['exam' => $exam]))->with('success','Results updated successfully.');
    }
}
