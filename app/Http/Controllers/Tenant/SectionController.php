<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Grade;
use App\Models\User;

class SectionController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth:tenant')->only(['index','create','store','edit','update','destroy']);
    }
    public function index()
    {
        $sections = Section::with(['grade', 'teacher'])
        ->join('grades', 'sections.grade_id', '=', 'grades.id')
        ->orderBy('grades.ordinal')
        ->orderBy('sections.name')
        ->select('sections.*') // ensure only section columns returned
        ->get();
        
        return view('tenant.pages.sections.index', compact('sections'));
    }

    public function create()
    {
        $grades = Grade::orderBy('ordinal')->get();
        $teachers = User::all(); // adjust as needed
        return view('tenant.pages.sections.create', compact('grades', 'teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_id'   => 'required|exists:grades,id',
            'name'       => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        Section::create($data);

        return redirect()
            ->intended(tenant_route('tenant.sections.index'))
            ->with('success', 'Section created successfully.');
    }

    public function edit(string $school_sub, string $id)
    {
        $section = Section::findOrFail($id);
        $grades = Grade::orderBy('ordinal')->get();
        $teachers = User::all();
        return view('tenant.pages.sections.edit', compact('section', 'grades', 'teachers'));
    }

    public function update(Request $request,string $school_sub,  string $id)
    {
        $data = $request->validate([
            'grade_id'   => 'required|exists:grades,id',
            'name'       => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $section = Section::findOrFail($id);
        $section->update($data);

        return redirect()
            ->intended(tenant_route('tenant.sections.index'))
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(string $school_sub, string $id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return back()->with('success', 'Section deleted.');
    }

    public function byGrade(Request $request)
    {
        $gradeId = $request->input('grade_id');
        $sections = \App\Models\Section::where('grade_id',$gradeId)->get(['id','name']);
        return response()->json($sections);
    }
}
