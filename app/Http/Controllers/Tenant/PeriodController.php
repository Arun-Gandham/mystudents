<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionDayPeriod;
use App\Models\SectionDayTimetable;
use Illuminate\Validation\Rule;

class PeriodController extends Controller
{
    public function store(string $school_sub, Request $request, $timetableId)
{
    $request->validate([
        'period_no'  => [
            'required',
            'integer',
            'min:1',
            Rule::unique('section_day_periods')
                ->where('day_timetable_id', $timetableId)
        ],
        'starts_at'  => 'required|date_format:H:i',
        'ends_at'    => 'required|date_format:H:i|after:starts_at',
        'subject_id' => 'required|uuid',
        'teacher_id' => 'required|uuid',
        'room'       => 'nullable|string|max:50',
        'note'       => 'nullable|string|max:255',
    ], [
        'period_no.unique' => 'This period number already exists in the timetable.',
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

    public function destroy(string $school_sub, $timetableId, $periodId)
    {
        SectionDayPeriod::where('day_timetable_id', $timetableId)
            ->where('id', $periodId)
            ->delete();

        return back()->with('success', 'Period deleted successfully.');
    }

    public function apiList($school_sub, $timetableId)
{
    $periods = \App\Models\SectionDayPeriod::with(['subject:id,name', 'teacher:id,full_name'])
        ->where('day_timetable_id', $timetableId)
        ->get();

    return $periods->map(function ($p) {
        return [
            'id'         => $p->id,
            'period_no'  => $p->period_no,
            'starts_at'  => $p->starts_at,
            'ends_at'    => $p->ends_at,
            'subject_id' => $p->subject_id,
            'subject'    => $p->subject?->name,
            'teacher_id' => $p->teacher_id,
            'teacher'    => $p->teacher?->full_name,
            'room'       => $p->room,
            'note'       => $p->note,
        ];
    });
}
}
