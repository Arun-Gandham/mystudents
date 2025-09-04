<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    // 📌 List subjects
    public function index()
    {
        $subjects = Subject::forSchool(current_school_id())
            ->orderBy('name')
            ->get();

        return view('tenant.pages.subjects.index', compact('subjects'));
    }

    // 📌 Create form
    public function create()
    {
        return view('tenant.pages.subjects.create');
    }

    // 📌 Store new subject
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|alpha_num',
            'is_active' => 'boolean',
        ]);

        Subject::create([
            'school_id' => current_school_id(),
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->intended(tenant_route('tenant.subjects.index'))
            ->with('success', 'Subject created successfully!');
    }

    // 📌 Edit form
    public function edit($school_sub, $id)
    {
        $subject = Subject::forSchool(current_school_id())->findOrFail($id);
        return view('tenant.pages.subjects.edit', compact('subject'));
    }

    // 📌 Update
    public function update(Request $request, $school_sub, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|alpha_num',
            'is_active' => 'boolean',
        ]);

        $subject = Subject::forSchool(current_school_id())->findOrFail($id);

        $subject->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->intended(tenant_route('tenant.subjects.index'))
            ->with('success', 'Subject updated successfully!');
    }

    // 📌 Delete
    public function destroy($school_sub, $id)
    {
        $subject = Subject::forSchool(current_school_id())->findOrFail($id);
        $subject->delete();

        return redirect()
            ->intended(tenant_route('tenant.subjects.index'))
            ->with('success', 'Subject deleted successfully!');
    }
}
