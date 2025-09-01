<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::orderBy('ordinal')->get();
        return view('tenant.pages.grades.index', compact('grades'));
    }

    public function create()
    {
        return view('tenant.pages.grades.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'ordinal'   => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Grade::create($data);

        return redirect()
            ->route('tenant.grades.index', ['school_sub' => current_school_sub()])
            ->with('success', 'Grade created successfully.');
    }

    public function edit(string $id)
    {
        $grade = Grade::findOrFail($id);
        return view('tenant.pages.grades.edit', compact('grade'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'ordinal'   => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $grade = Grade::findOrFail($id);
        $grade->update($data);

        return redirect()
            ->route('tenant.grades.index', ['school_sub' => current_school_sub()])
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return back()->with('success', 'Grade deleted.');
    }
}
