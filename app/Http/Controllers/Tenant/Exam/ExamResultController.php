<?php

namespace App\Http\Controllers\Tenant\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamGrade;
use App\Models\Student;
use App\Models\ExamOverallResult;

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
            // ✅ Save subject results
            foreach ($request->results as $row) {
                ExamResult::updateOrCreate(
                    [
                        'exam_id'    => $exam->id,
                        'student_id' => $row['student_id'],
                        'subject_id' => $row['subject_id'],
                    ],
                    [
                        'marks_obtained' => $row['marks_obtained'],
                        'entered_by'     => auth()->id(),
                        'entered_at'     => now(),
                    ]
                );
            }

            // ✅ Recalculate overall results
            $students = Student::whereHas('enrollments', fn($q) => 
                $q->where('section_id', $exam->section_id)
            )->get();

            // Step 1: build array of totals
            $overallData = [];
            foreach ($students as $student) {
                $results = ExamResult::where('exam_id',$exam->id)
                            ->where('student_id',$student->id)->get();

                $totalObtained = $results->sum('marks_obtained');
                $totalMax      = $exam->subjects->sum('max_marks');

                $grade = null;
                if ($grading->count()) {
                    $rule = $grading->first(fn($g) => $totalObtained >= $g->min_mark && $totalObtained <= $g->max_mark);
                    $grade = $rule?->grade;
                }

                $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;

                $overallData[] = [
                    'student_id' => $student->id,
                    'total'      => $totalObtained,
                    'max'        => $totalMax,
                    'grade'      => $grade,
                    'percentage' => $percentage,
                ];
            }

            // Step 2: sort and assign ranks
            usort($overallData, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
            $rank = 1;
            foreach ($overallData as $data) {
                ExamOverallResult::updateOrCreate(
                    [
                        'exam_id'    => $exam->id,
                        'student_id' => $data['student_id'],
                    ],
                    [
                        'total_obtained' => $data['total'],
                        'total_max'      => $data['max'],
                        'overall_grade'  => $data['grade'],
                        'percentage'     => $data['percentage'],
                        'rank'           => $rank,
                    ]
                );
                $rank++;
            }
        });

        return redirect()->to(tenant_route('tenant.exams.show',['exam' => $exam]))->with('success','Results updated successfully.');
    }
}
