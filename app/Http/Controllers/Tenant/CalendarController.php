<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolHoliday;

class CalendarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:tenant')->only(['index']);
    }

    public function index()
    {
        // You can preload holidays if you want server-side rendering
        $holidays = SchoolHoliday::orderBy('date', 'asc')
            ->get();

        return view('tenant.pages.holidays.index', compact('holidays'));
    }
}
