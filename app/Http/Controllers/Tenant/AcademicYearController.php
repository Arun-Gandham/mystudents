<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Academic;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant')->only(['index','create','store','edit','update','toggle']);
    }
    public function index()
    {
        $years = Academic::all();

        return view('tenant.pages.academic_years.index', compact('years'));
    }

    public function create()
    {
        return view('tenant.pages.academic_years.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        Academic::create($data);

        return redirect(tenant_route('tenant.academic_years.index'))
            ->with('success', 'Academic Year created successfully.');
    }

    public function edit(string $sub_school, string $academic_year_id)
{
        $academic_year = Academic::findOrFail($academic_year_id);

        return view('tenant.pages.academic_years.edit', compact('academic_year'));
    }

    public function update(Request $request, string $school_sub, string $academic_year_id)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        // fetch record using ID
        $academic_year = Academic::findOrFail($academic_year_id);

        $academic_year->update($data);

        return redirect(tenant_route('tenant.academic_years.index'))
            ->with('success', 'Academic Year updated successfully.');
    }

    // Toggle switch: set current academic year
    public function toggle(string $academic_year_id)
{
    $academic_year = Academic::findOrFail($academic_year_id);

    Academic::forSchool(current_school_id())->update(['is_current' => false]);
    $academic_year->update(['is_current' => true]);

    return redirect(tenant_route('academic_years.index'))
        ->with('success', 'Academic Year set as current.');
}
}
