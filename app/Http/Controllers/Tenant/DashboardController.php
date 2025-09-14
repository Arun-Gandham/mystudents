<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Student;
use App\Models\Staff;
use App\Models\SchoolHoliday;
use App\Models\StudentAttendanceEntry;
use App\Models\StaffAttendance;
use App\Models\StudentFeeReceipt;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        $data = [];

        // ============================
        // Students
        // ============================
        if ($user->can('dashboard:students')) {
            $data['students'] = Cache::remember("dashboard:students:$schoolId", 600, function () use ($schoolId) {
                $total = Student::where('school_id', $schoolId)->count();

                $presentToday = StudentAttendanceEntry::whereHas('sheet', function ($q) use ($schoolId) {
                        $q->where('school_id', $schoolId)
                          ->whereDate('attendance_date', Carbon::today());
                    })
                    ->where('status', 'present')
                    ->count();

                return compact('total', 'presentToday');
            });
        }

        // ============================
        // Staff
        // ============================
        if ($user->can('dashboard:staff')) {
            $data['staff'] = Cache::remember("dashboard:staff:$schoolId", 600, function () use ($schoolId) {
                $total = Staff::where('school_id', $schoolId)->count();

                $presentToday = StaffAttendance::where('school_id', $schoolId)
                    ->whereDate('attendance_date', Carbon::today())
                    ->where('status', 'present')
                    ->count();

                return compact('total', 'presentToday');
            });
        }

        // ============================
        // Next Holiday
        // ============================
        if ($user->can('dashboard:holidays')) {
            $data['holiday'] = Cache::remember("dashboard:holiday:$schoolId", 600, function () use ($schoolId) {
                return SchoolHoliday::where('school_id', $schoolId)
                    ->whereDate('date', '>=', Carbon::today())
                    ->orderBy('date', 'asc')
                    ->first();
            });
        }


        // ============================
        // Fees Collection (Current Month)
        // ============================
        if ($user->can('dashboard:fees')) {
            $data['fees'] = Cache::remember("dashboard:fees:$schoolId", 600, function () use ($schoolId) {
                $monthStart = Carbon::now()->startOfMonth();
                $monthEnd   = Carbon::now()->endOfMonth();

                $collected = StudentFeeReceipt::where('school_id', $schoolId)
                    ->whereBetween('paid_on', [$monthStart, $monthEnd])
                    ->sum('total_amount');

                // Optional: you can compute target dynamically per school or hardcode
                $target = 1500000; // example

                return compact('collected', 'target');
            });
        }

        return view('tenant.pages.dashboard.dashboard', compact('data'));
    }
}
