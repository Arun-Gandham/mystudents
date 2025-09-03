<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolHoliday;
use App\Models\Academic;

class SchoolHolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant')->only(['listByAcademic','list','calendar','create','store','edit','update','destroy']);
    }

    // 📌 List all holidays
    public function list()
    {
        $holidays = SchoolHoliday::forSchool(current_school_id())
            ->with('academic')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($holidays);
    }

    // 📌 Calendar-friendly JSON
    public function calendar()
    {
        $holidays = SchoolHoliday::forSchool(current_school_id())->get();

        $events = $holidays->map(function ($h) {
            return [
                'id'    => $h->id,
                'title' => $h->name,
                'start' => $h->is_full_day
                    ? $h->date->toDateString()
                    : $h->date->toDateString() . 'T' . $h->starts_at,
                'end'   => $h->is_full_day
                    ? null
                    : $h->date->toDateString() . 'T' . $h->ends_at,
                'allDay'=> $h->is_full_day,
            ];
        });

        return response()->json($events);
    }

    // 📌 Get academics list for form
    public function create()
    {
        $academics = Academic::orderBy('start_date', 'desc')
            ->get();

        return view('tenant.pages.holidays.create', compact('academics'));
    }

    

   public function store(Request $request)
{
    $request->validate([
        'academic_id' => 'required|uuid',
        'date' => 'required|date',
        'name' => 'required|string|max:255',
        'is_full_day' => 'boolean',
        'starts_at' => 'nullable|date_format:H:i',
        'ends_at'   => 'nullable|date_format:H:i|after:starts_at',
        'repeats_annually' => 'boolean',
    ]);

    SchoolHoliday::create([
        'school_id' => current_school_id(),
        'academic_id' => $request->academic_id,
        'date' => $request->date,
        'name' => $request->name,
        'is_full_day' => $request->boolean('is_full_day', true),
        'starts_at' => $request->starts_at,
        'ends_at' => $request->ends_at,
        'repeats_annually' => $request->boolean('repeats_annually', false),
    ]);

    return redirect()
        ->intended(tenant_route('tenant.school_holidays.index'))
        ->with('success', 'Holiday created successfully!');
}


    // 📌 Edit holiday
    public function edit($school_sub, $id)
{
    $holiday = SchoolHoliday::forSchool(current_school_id())->findOrFail($id);
    $academics = Academic::forSchool(current_school_id())->get();

    return view('tenant.pages.holidays.edit', compact('holiday', 'academics'));
}

public function listByAcademic(Request $request)
{
    $academics = Academic::forSchool(current_school_id())
        ->orderBy('start_date', 'desc')
        ->get();

    // pick academic_id from query or default to first available
    $academicId = $request->input('academic_id', $academics->first()->id ?? null);

    $holidays = collect();
    if ($academicId) {
        $holidays = SchoolHoliday::forSchool(current_school_id())
            ->where('academic_id', $academicId)
            ->orderBy('date', 'asc')
            ->get();
    }

    return view('tenant.pages.holidays.list', compact('academics', 'academicId', 'holidays'));
}


    // 📌 Update holiday
    public function update(Request $request, $school_sub, $id)
{
    $request->validate([
        'academic_id' => 'required|uuid',
        'date' => 'required|date',
        'name' => 'required|string|max:255',
        'is_full_day' => 'boolean',
        'starts_at' => 'nullable|date_format:H:i',
        'ends_at'   => 'nullable|date_format:H:i|after:starts_at',
        'repeats_annually' => 'boolean',
    ]);

    $holiday = SchoolHoliday::forSchool(current_school_id())->findOrFail($id);

    $holiday->update([
        'academic_id' => $request->academic_id,
        'date' => $request->date,
        'name' => $request->name,
        'is_full_day' => $request->boolean('is_full_day', true),
        'starts_at' => $request->starts_at,
        'ends_at' => $request->ends_at,
        'repeats_annually' => $request->boolean('repeats_annually', false),
    ]);

    return redirect()
        ->intended(tenant_route('tenant.calender.calender'))
        ->with('success', 'Holiday updated successfully!');
}

    // 📌 Delete holiday
    public function destroy($school_sub, $id)
{
    $holiday = SchoolHoliday::forSchool(current_school_id())->findOrFail($id);
    $holiday->delete();

    return redirect()
        ->intended(tenant_route('tenant.school_holidays.index'))
        ->with('success', 'Holiday deleted successfully!');
}
}
