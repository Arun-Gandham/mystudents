<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SADashboardController extends Controller
{
    protected array $baseMeta = [];

    public function __construct()
    {
        // Default meta for all School views
        $this->baseMeta = [
            'pageTitle'       => 'Dashboard',
            'pageDescription' => 'Manage all Dashboard in the system',
            
        ];

        // Share to all Blade views automatically
        view()->share($this->baseMeta);
    }
    public function dashboard()
    {
        return view('superadmin.pages.dashboard.dashboard');
    }
}
