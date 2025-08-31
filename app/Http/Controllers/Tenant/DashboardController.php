<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant')->only(['dashboard']);
    }

    public function dashboard(Request $request)
    {
        $school = $request->attributes->get('school');
        return "Dashboard page " . $school->name;
    }
}
