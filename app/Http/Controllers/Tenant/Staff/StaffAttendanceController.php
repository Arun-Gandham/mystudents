<?php
namespace App\Http\Controllers\Tenant\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StaffAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $session = $request->input('session', 'morning');

        return $staff = Staff::with('user')
            ->orderBy('first_name')
            ->get();

        // Get existing attendance
        $attendance = StaffAttendance::where('school_id', current_school_id())
            ->where('attendance_date', $date)
            ->where('session', $session)
            ->get()
            ->keyBy('user_id');
        $staffList = Staff::get();
        return view('tenant.pages.staff_attendance.index', compact('staff','attendance','date','session'));
    }

    public function create()
{
    $staff = Staff::with('user')
        ->where('is_active', true)
        ->get();

    return view('tenant.pages.staff_attendance.create', compact('staff'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'attendance_date' => ['required', 'date'],
        'session'         => ['required', 'in:morning,afternoon'],
        'attendance'      => ['required', 'array'],
    ]);

    foreach ($data['attendance'] as $userId => $status) {
            StaffAttendance::updateOrCreate(
            [
                'school_id'       => current_school_id(),
                'user_id'         => $userId,
                'attendance_date' => $data['attendance_date'],
                'session'         => $data['session'],
            ],
            [
                'status'    => $status,
                'remarks'   => $request->remarks[$userId] ?? null,
                'check_in'  => $request->check_in[$userId] ?? null,
                'check_out' => $request->check_out[$userId] ?? null,
            ]
        );
    }

    return redirect()->to(tenant_route('tenant.staffAttendance.list'))
        ->with('success', 'Attendance recorded successfully!');
}

    public function list(Request $request)
{
    $date = $request->input('date');
    $session = $request->input('session');
    $status = $request->input('status');
    $staff_id = $request->input('staff_id');

    $query = StaffAttendance::with(['user','user.staff'])
        ->where('school_id', current_school_id());

    if ($date) {
        $query->where('attendance_date', $date);
    }
    if ($session) {
        $query->where('session', $session);
    }
    if ($status) {
        $query->where('status', $status);
    }
    if ($staff_id) {
        $query->where('user_id', $staff_id);
    }

    $staffList = Staff::with('user')
        ->where('is_active', true)
        ->get();
    
    $records = $query->orderBy('attendance_date','desc')
        ->orderBy('session')
        ->paginate(15);

    return view('tenant.pages.staff_attendance.list', compact('staffList','records','date','session','status'));
}

public function edit($school_sub, $id)
{
    $attendance = StaffAttendance::with('user.staff')->findOrFail($id);
    return view('tenant.pages.staff_attendance.edit', compact('attendance'));
}

public function update(Request $request, $school_sub, $id)
{
    $data = $request->validate([
        'attendance_date' => ['required','date'],
        'session'         => ['required','in:morning,afternoon'],
        'status'          => ['required','in:present,absent,late,half_day,excused'],
        'check_in'        => ['nullable','date_format:H:i'],
        'check_out'       => ['nullable','date_format:H:i'],
        'remarks'         => ['nullable','string','max:500'],
    ]);

    $attendance = StaffAttendance::findOrFail($id);
    $attendance->update($data);

    return redirect()->to(tenant_route('tenant.staffAttendance.list'))
                     ->with('success','Attendance updated successfully.');
}
}
