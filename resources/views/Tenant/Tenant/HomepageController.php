<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function __construct()
    {
    }

    public function homepage(Request $request)
    {
        $school = $request->attributes->get('school');
        return "Home page" . $school->name;
    }
}
