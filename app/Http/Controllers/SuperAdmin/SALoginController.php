<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SALoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:superadmin')->only(['showLoginForm','login']);
        $this->middleware('auth:superadmin')->only(['logout']);
    }

    public function showLoginForm()
    {
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)
        ->where('is_active', true)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is disabled.',
            ]);
        }

        // ✅ use superadmin guard
        Auth::guard('superadmin')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        
        return redirect()->route('superadmin.dashboard');
    }

    public function logout(Request $request)
    {
        // ✅ superadmin guard logout
        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ back to superadmin login
        return redirect()->route('superadmin.login');
    }
}
