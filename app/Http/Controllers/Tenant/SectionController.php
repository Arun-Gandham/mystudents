<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Grade;
use App\Models\User;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with(['grade', 'teacher'])
            ->orderBy('name')
            ->get();

        return view('tenant.pages.sections.index', compact('sections'));
    }

    public function create()
    {
        $grades = Grade::orderBy('ordinal')->get();
        $teachers = User::where('role', 'teacher')->get(); // adjust as needed
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
            ->route('tenant.sections.index', ['school_sub' => current_school_sub()])
            ->with('success', 'Section created successfully.');
    }

    public function edit(string $id)
    {
        $section = Section::findOrFail($id);
        $grades = Grade::orderBy('ordinal')->get();
        $teachers = User::where('role', 'teacher')->get();
        return view('tenant.pages.sections.edit', compact('section', 'grades', 'teachers'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'grade_id'   => 'required|exists:grades,id',
            'name'       => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $section = Section::findOrFail($id);
        $section->update($data);

        return redirect()
            ->route('tenant.sections.index', ['school_sub' => current_school_sub()])
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(string $id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return back()->with('success', 'Section deleted.');
    }
}
