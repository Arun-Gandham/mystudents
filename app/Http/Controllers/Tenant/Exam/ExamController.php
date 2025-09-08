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
                            'max_marks'  => $sub['max_marks'] ?? 100,
                            'pass_marks' => $sub['pass_marks'] ?? null,
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

        return redirect()->to(tenant_route('tenant.exams.index'))->with('success','Exam created.');
    }

    public function show($school_sub, Exam $exam)
    {
        $exam->load(['subjects.subject','results.student','grades']);
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
                            'max_marks'  => $sub['max_marks'] ?? 100,
                            'pass_marks' => $sub['pass_marks'] ?? null,
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
}
