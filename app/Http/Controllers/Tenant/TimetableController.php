<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SectionDayTimetable;
use App\Models\SectionDayPeriod;
use App\Models\Subject;
use App\Models\User;
use App\Constants\WeekDays;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant')->only(['index','create','show','store','edit','update','destroy','copyForm','copySave']);
    }

    public function index(string $school_sub)
    {
        $timetables = SectionDayTimetable::with(['section.grade'])
            ->orderBy('section_id')
            ->orderBy('effective_from')
            ->get()
            ->groupBy('section_id'); // group by section for display

        return view('tenant.pages.timetables.index', compact('timetables'));
    }

    public function create()
    {
        $sections = Section::with('grade')->get();
        return view('tenant.pages.timetables.create', compact('sections'));
    }

    public function store(string $school_sub, Request $request)
{
    $request->validate([
        'section_id'      => 'required|uuid',
        'day'             => ['required', 'string', Rule::in(array_keys(WeekDays::LIST))],
        'title'           => 'required|string|max:255',
        'effective_from'  => 'required|date|after_or_equal:today',
        'effective_to'    => 'nullable|date|after_or_equal:effective_from',
    ]);

    SectionDayTimetable::create([
        'school_id'       => current_school_id(),
        'academic_id'     => current_academic_id(),
        'section_id'      => $request->section_id,
        'day'             => $request->day,
        'title'           => $request->title,
        'effective_from'  => $request->effective_from,
        'effective_to'    => $request->effective_to,
        'is_active'       => true,
    ]);
        return redirect()->intended(tenant_route('tenant.timetables.index'))
            ->with('success', 'Timetable created successfully.');
    }

    public function show(string $school_sub, $id)
    {
        $timetable = SectionDayTimetable::with(['section', 'periods.subject', 'periods.teacher'])
            ->findOrFail($id);

        $subjects = Subject::all();
        $teachers =  User::all();

        return view('tenant.pages.timetables.show', compact('timetable', 'subjects', 'teachers'));
    }

    public function storePeriod(string $school_sub, Request $request, $timetableId)
    {
        $request->validate([
            'period_no'  => 'required|integer',
            'starts_at'  => 'required',
            'ends_at'    => 'required',
            'subject_id' => 'required|uuid',
            'teacher_id' => 'required|uuid',
        ]);

        SectionDayPeriod::create([
            'day_timetable_id' => $timetableId,
            'period_no'        => $request->period_no,
            'starts_at'        => $request->starts_at,
            'ends_at'          => $request->ends_at,
            'subject_id'       => $request->subject_id,
            'teacher_id'       => $request->teacher_id,
            'room'             => $request->room,
            'note'             => $request->note,
        ]);

        return back()->with('success', 'Period added successfully.');
    }

    public function destroy(string $school_sub, $timetableId)
{
    // Delete all periods first
    SectionDayPeriod::where('day_timetable_id', $timetableId)->delete();

    // Then delete the timetable
    SectionDayTimetable::where('id', $timetableId)->delete();

    return back()->with('success', 'Timetable deleted successfully.');
}

    public function copyForm(string $school_sub)
{
    $schoolId = current_school_id();
    $sections = Section::with('grade')
        ->orderBy('name')
        ->get();
    $allTimetables = \App\Models\SectionDayTimetable::with(['section.grade'])
        ->where('school_id', $schoolId)
        ->orderBy('created_at', 'desc')
        ->get();

    $subjects = Subject::all();
    $teachers = User::all();

    return view('tenant.pages.timetables.copy', compact('allTimetables', 'subjects', 'teachers','sections'));
}

public function copySave(string $school_sub, Request $request)
{
    
    // Clean up empty rows
    $periods = collect($request->input('periods', []))
        ->filter(function ($p) {
            return !empty($p['period_no']) || !empty($p['starts_at']) || !empty($p['ends_at'])
                || !empty($p['subject_id']) || !empty($p['teacher_id']);
        })
        ->values()
        ->all();

    $request->merge(['periods' => $periods]);

    $request->validate([
    'source_timetable'   => 'required|uuid|exists:section_day_timetables,id',
    'section_id'         => 'required|uuid|exists:sections,id',
    'day'                => 'required|string|in:mon,tue,wed,thu,fri,sat,sun',
    'title'              => 'required|string|max:255',
    'effective_from'     => 'required|date',
    'effective_to'       => 'nullable|date|after:effective_from',
    'periods'            => 'required|array|min:1',
    'periods.*.period_no'=> 'required|integer|min:1',
    'periods.*.starts_at'=> 'required',
    'periods.*.ends_at'  => 'required|after:periods.*.starts_at',
    'periods.*.subject_id'=> 'required|uuid|exists:subjects,id',
    'periods.*.teacher_id'=> 'required|uuid|exists:users,id',
]);

    // Save timetable
    $newTimetable = SectionDayTimetable::create([
    'school_id'    => current_school_id(),
    'academic_id'  => current_academic_id(),
    'section_id'   => $request->section_id,
    'day'          => $request->day,
    'title'        => $request->title,
    'effective_from' => $request->effective_from,
    'effective_to'   => $request->effective_to,
    'is_active'      => true,
]);

    foreach ($periods as $p) {
        SectionDayPeriod::create([
            'day_timetable_id' => $newTimetable->id,
            'period_no'        => $p['period_no'],
            'starts_at'        => $p['starts_at'],
            'ends_at'          => $p['ends_at'],
            'subject_id'       => $p['subject_id'],
            'teacher_id'       => $p['teacher_id'],
            'room'             => $p['room'] ?? null,
            'note'             => $p['note'] ?? null,
        ]);
    }
    return redirect()->route('tenant.timetables.index', ['school_sub' => $school_sub])
        ->with('success', 'Timetable copied successfully.');
}
}
