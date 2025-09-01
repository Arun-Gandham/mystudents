<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TenantLoginController extends Controller
{

    public function __construct()
    {
        // use the tenant guard on these routes
        $this->middleware('guest:tenant')->only(['showLoginForm','login']);
        $this->middleware('auth:tenant')->only(['logout']);
    }

    public function showLoginForm(Request $request)
    {
        $school = $request->attributes->get('school');
        return view('tenant.auth.login', ['school' => $school]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        $school = $request->attributes->get('school');

        $user = User::where('email', $request->string('email'))
            ->where('school_id', $school->id)
            ->where('is_active', true)
            ->first();

            if (!$user || !Hash::check($request->string('password'), $user->password)) {
                return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
            }
    
            // ✅ use tenant guard
            Auth::guard('tenant')->login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $user->clearPermissionCache();
            $user->getPermissions();
    
            // ✅ use tenant_route() so school_sub is auto-injected
            return redirect()->intended(tenant_route('tenant.dashboard'));
    }

    public function logout(Request $request)
    {
        // ✅ tenant guard logout
        if (Auth::guard('tenant')->check()) {
            Auth::guard('tenant')->user()->clearPermissionCache(); // ✅ clear permissions cache
        }
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ back to this subdomain’s login
        return redirect()->to(tenant_route('tenant.login'));
    }
}
