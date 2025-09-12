<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

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
            $user->refreshPermissions();
            // $user->clearPermissionCache();
            // $user->getPermissions();
    
            // ✅ use tenant_route() so school_sub is auto-injected
            return redirect()->intended(tenant_route('tenant.dashboard'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();

            // ✅ clear user-specific cache
            $user->clearPermissionCache();
            session()->forget('auth_permissions');

            // ✅ clear cached school by subdomain
            if ($sub = current_school_sub()) {
                Cache::forget("school_by_subdomain:{$sub}");
            }
        }

        // ✅ tenant guard logout
        Auth::guard('tenant')->logout();

        // ✅ clear request attributes
        $request->attributes->remove('school');
        $request->attributes->remove('academic');

        // ✅ session reset
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(tenant_route('tenant.login'));
    }

}
