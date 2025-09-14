<?php

namespace App\Http\Controllers\Tenant\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\Academic;
use App\Models\Section;
use App\Models\Subject;
use App\Models\ExamSubject;
use App\Models\ExamGrade;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    public function index()
    {
        $exams = Exam::with(['academic','section.grade'])->orderBy('created_at','desc')->paginate(15);
        return view('tenant.pages.exams.index', compact('exams'));
    }

    public function create()
    {
        $academics = Academic::orderBy('start_date','desc')->get();
        $sections  = Section::with('grade')->get();
        $subjects  = Subject::where('school_id', current_school_id())->get();
        return view('tenant.pages.exams.create', compact('academics','sections','subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_id' => 'required|uuid|exists:academics,id',
            'section_id'  => 'required|uuid|exists:sections,id',
            'name'        => 'required|string|max:255',
            'starts_on'   => 'nullable|date',
            'ends_on'     => 'nullable|date|after_or_equal:starts_on',
        ]);
        $exam = [];
        DB::transaction(function () use ($request) {
            $exam = Exam::create([
                'school_id'   => current_school_id(),
                'academic_id' => $request->academic_id,
                'section_id'  => $request->section_id,
                'name'        => $request->name,
                'starts_on'   => $request->starts_on,
                'ends_on'     => $request->ends_on,
                'note'        => $request->note,
                'is_published'=> $request->has('is_published')
            ]);

            // Subjects
            if ($request->has('subjects')) {
                foreach ($request->subjects as $sub) {
                    if (!empty($sub['id'])) {
                        ExamSubject::create([
                            'exam_id'    => $exam->id,
                            'subject_id' => $sub['id'],
                            'max_marks'  => $sub['max_marks'],
                            'pass_marks' => $sub['pass_marks'],
                            'exam_date'  => $sub['exam_date'] ?? null,
                        ]);
                    }
                }
            }

            // Grades
            if ($request->has('grades')) {
                foreach ($request->grades as $g) {
                    if (!empty($g['grade'])) {
                        ExamGrade::create([
                            'exam_id'   => $exam->id,
                            'grade'     => $g['grade'],
                            'min_mark'  => $g['min_mark'],
                            'max_mark'  => $g['max_mark'],
                            'remark'    => $g['remark'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->to(tenant_route('tenant.exams.show', ['exam' => $exam]))->with('success','Exam created.');
    }

    public function show($school_sub, Exam $exam)
    {
        $exam->load(['subjects.subject','results','overallResults','grades','section.enrollments.student']);
        return view('tenant.pages.exams.show', compact('exam'));
    }

    public function edit($school_sub, Exam $exam)
    {
        $academics = Academic::orderBy('start_date','desc')->get();
        $sections  = Section::with('grade')->get();
        $subjects  = Subject::where('school_id', current_school_id())->get();
        $exam->load(['subjects','grades']);
        return view('tenant.pages.exams.edit', compact('exam','academics','sections','subjects'));
    }

    public function update(Request $request, $school_sub, Exam $exam)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'starts_on' => 'nullable|date',
            'ends_on'   => 'nullable|date|after_or_equal:starts_on'
        ]);

        DB::transaction(function () use ($request, $exam) {
            $exam->update($request->only('academic_id','section_id','name','starts_on','ends_on','note','is_published'));

            // Replace subjects
            $exam->subjects()->delete();
            if ($request->has('subjects')) {
                foreach ($request->subjects as $sub) {
                    if (!empty($sub['id'])) {
                        ExamSubject::create([
                            'exam_id'    => $exam->id,
                            'subject_id' => $sub['id'],
                            'max_marks'  => $sub['max_marks'],
                            'pass_marks' => $sub['pass_marks'],
                            'exam_date'  => $sub['exam_date'] ?? null,
                        ]);
                    }
                }
            }

            // Replace grades
            $exam->grades()->delete();
            if ($request->has('grades')) {
                foreach ($request->grades as $g) {
                    if (!empty($g['grade'])) {
                        ExamGrade::create([
                            'exam_id'   => $exam->id,
                            'grade'     => $g['grade'],
                            'min_mark'  => $g['min_mark'],
                            'max_mark'  => $g['max_mark'],
                            'remark'    => $g['remark'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->to(tenant_route('tenant.exams.show', ['exam' => $exam]))->with('success','Exam updated.');
    }

    public function destroy($school_sub, Exam $exam)
    {
        $exam->delete();
        return back()->with('success','Exam deleted.');
    }

    public function tabContent($school_sub, Exam $exam, $tab)
    {
        switch ($tab) {
            case 'dashboard':
                $exam->load(['subjects.subject','overallResults.student','results.student','section.enrollments.student']);

                // Overall topper
                $overallTopper = $exam->overallResults->sortByDesc('total_obtained')->first();

                // Subject toppers
                $subjectToppers = [];
                foreach ($exam->subjects as $sub) {
                    $topResult = $exam->results
                        ->where('subject_id', $sub->subject_id)
                        ->sortByDesc('marks_obtained')
                        ->first();
                    if ($topResult) {
                        $subjectToppers[] = [
                            'subject' => $sub->subject->name,
                            'student' => $topResult->student,
                            'marks'   => $topResult->marks_obtained,
                            'max'     => $sub->max_marks,
                        ];
                    }
                }

                return view('tenant.pages.exams.tabs.dashboard', compact('exam','overallTopper','subjectToppers'));

            case 'grades':
                $exam->load(['grades']);
                return view('tenant.pages.exams.tabs.grades', compact('exam'));

            case 'results':
                $exam->load(['subjects.subject','results','overallResults','grades','section.enrollments.student']);

                // ✅ Sort enrollments by percentage
                $sortedEnrollments = $exam->section->enrollments->sortByDesc(function($enroll) use ($exam) {
                    $overall = $exam->overallResults->firstWhere('student_id', $enroll->student->id);
                    return $overall ? ($overall->total_obtained / max($overall->total_max,1)) : 0;
                });

                return view('tenant.pages.exams.tabs.results', compact('exam','sortedEnrollments'));

        }

        abort(404);
    }
    public function togglePublish($school_sub, Exam $exam)
    {
        $exam->update(['is_published' => !$exam->is_published]);
        return back()->with('success', $exam->is_published ? 'Exam published successfully.' : 'Exam unpublished.');
    }
}
