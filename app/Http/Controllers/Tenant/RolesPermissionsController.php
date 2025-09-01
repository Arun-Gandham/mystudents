<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;


class RolesPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant')->only(['index']);
    }
    public function index(Request $request)
    {
        $roles = Role::orderBy('name')->get();
        $roleId = $request->query('role_id');

        $assigned = [];
        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $assigned = $role->permissions()->pluck('key')->toArray();
            }
        }

        // Group all permissions by prefix (before ':')
        $allPermissions = Permission::orderBy('key')->get()
            ->groupBy(function($perm) {
                return explode(':', $perm->key)[0]; // prefix as group
            });
        return view('tenant.pages.rolespermissions.permissions', compact('roles', 'assigned', 'roleId', 'allPermissions'));
  }

    public function update(Request $request)
{
    $roleId = $request->input('role_id');

    $role = Role::findOrFail($roleId);

    // decode JSON but guarantee an array
    $assigned = json_decode($request->input('permissions', '[]'), true) ?? [];

    // Convert permission keys to IDs
    $permissionIds = !empty($assigned)
        ? Permission::whereIn('key', $assigned)->pluck('id')->toArray()
        : [];

    // Sync pivot
    $role->permissions()->sync($permissionIds);

    return redirect()->back()->with('success', "Permissions updated for {$role->name}");
}
}
