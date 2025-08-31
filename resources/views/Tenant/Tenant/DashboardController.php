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

    public function dashboard()
    {
        return view('tenant.pages.dashboard.dashboard');
    }
}
