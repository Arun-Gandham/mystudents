<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    public function show()
    {
        $user = auth()->user();
        return view('tenant.pages.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('tenant.pages.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:100',
            'address'     => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $validated['photo'] = $request->file('photo')->store('user_photos', 'public');
        }

        $user->update($validated);

        return redirect()
            ->to(tenant_route('tenant.profile.show'))
            ->with('success', 'Profile updated successfully.');
    }
}
