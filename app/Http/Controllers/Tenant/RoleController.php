<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        return view('tenant.pages.RolesPermissions.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('tenant.pages.RolesPermissions.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
        ]);

        $data['school_id'] = current_school_id(); // 🔹 helper to bind to school
        $data['is_system'] = 0;

        Role::create($data);

        return redirect()->intended(tenant_route('tenant.roles.index'))->with('success', 'Role created successfully.');
    }

    public function edit(string $school_sub, string $role_id)
    {
        $role = Role::findOrFail($role_id);
        return view('tenant.pages.RolesPermissions.roles.edit', compact('role'));
    }

    public function update(Request $request,string $school_sub,  string $role_id)
    {
        $role = Role::findOrFail($role_id);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
        ]);

        $role->update($data);

        return redirect()->intended(tenant_route('tenant.roles.index'))->with('success', 'Role updated successfully.');
    }

    public function destroy(string $school_sub, string $role_id)
{
    $role = Role::findOrFail($role_id);

    if ($role->is_system) {
        return redirect()->intended(tenant_route('tenant.roles.index'))
            ->with('error', 'System roles cannot be deleted.');
    }

    $role->delete();

    return redirect()->intended(tenant_route('tenant.roles.index'))
        ->with('success', 'Role deleted successfully.');
}
}
