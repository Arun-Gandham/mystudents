<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffProfileController extends Controller
{
    protected function currentStaff()
    {
        return auth()->user()->staff ?? abort(404, 'Staff profile not found');
    }

    public function show()
    {
        $staff = auth()->user()->staff;
        return view('tenant.pages.staff.profile.show', compact('staff'));
    }

    public function edit(string $school_sub)
    {
        $staff = $this->currentStaff();
        return view('tenant.pages.staff.profile.edit', compact('staff'));
    }

    public function update(Request $request)
    {
        $staff = $this->currentStaff();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:100',
            'address'     => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('staff_photos', 'public');
            $validated['photo'] = $path;
        }

        $staff->update($validated);

        return redirect()
            ->to(tenant_route('tenant.staff.profile.show'))
            ->with('success', 'Profile updated successfully.');
    }
}
