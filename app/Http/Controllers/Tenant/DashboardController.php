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

        // Students
        $data['students'] = Cache::remember("dashboard:students:$schoolId", 600, function () use ($schoolId) {
            $total = Student::count();

            $presentToday = StudentAttendanceEntry::whereHas('sheet', function ($q) {
                    $q->whereDate('attendance_date', Carbon::today());
                })
                ->where('status', 'present')
                ->count();

            return compact('total', 'presentToday');
        });

        // Staff
        $data['staff'] = Cache::remember("dashboard:staff:$schoolId", 600, function () use ($schoolId) {
            $total = Staff::count();

            $presentToday = StaffAttendance::whereDate('attendance_date', Carbon::today())
                ->where('status', 'present')
                ->count();

            return compact('total', 'presentToday');
        });

        // Next Holiday
        $data['holiday'] = Cache::remember("dashboard:holiday:$schoolId", 600, function () use ($schoolId) {
            return SchoolHoliday::whereDate('date', '>=', Carbon::today())
                ->orderBy('date', 'asc')
                ->first();
        });

        // Fees Collection (Current Month)
        $data['fees'] = Cache::remember("dashboard:fees:$schoolId", 600, function () use ($schoolId) {
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd   = Carbon::now()->endOfMonth();

            $collected = StudentFeeReceipt::whereBetween('paid_on', [$monthStart, $monthEnd])
                ->sum('total_amount');

            $receiptsCount = StudentFeeReceipt::whereBetween('paid_on', [$monthStart, $monthEnd])
                ->count();

            $target = 1500000; // placeholder target

            return compact('collected', 'target', 'receiptsCount');
        });

        // Academics snapshot
        $data['academics'] = Cache::remember("dashboard:academics:$schoolId", 600, function () use ($schoolId) {
            $currentYear = \App\Models\Academic::where('is_current', true)->first();
            $grades = \App\Models\Grade::count();
            $sections = \App\Models\Section::count();
            return [
                'currentYear' => $currentYear,
                'grades' => $grades,
                'sections' => $sections,
            ];
        });

        // Applications & Admissions
        $data['applications'] = Cache::remember("dashboard:applications:$schoolId", 600, function () use ($schoolId) {
            $total = \App\Models\StudentJoinApplication::count();
            $byStatus = \App\Models\StudentJoinApplication::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            return [ 'total' => $total, 'byStatus' => $byStatus ];
        });

        $data['admissions'] = Cache::remember("dashboard:admissions:$schoolId", 600, function () use ($schoolId) {
            $total = \App\Models\StudentAdmission::count();
            $today = \App\Models\StudentAdmission::whereDate('admitted_on', Carbon::today())
                ->count();
            return compact('total', 'today');
        });

        // Upcoming exams (next 5)
        $data['exams'] = Cache::remember("dashboard:exams:$schoolId", 600, function () use ($schoolId) {
            return \App\Models\Exam::whereDate('starts_on', '>=', Carbon::today())
                ->orderBy('starts_on', 'asc')
                ->limit(5)
                ->get(['id','name','starts_on','ends_on','is_published']);
        });

        return view('Tenant.pages.Dashboard.dashboard', compact('data'));
    }
}
