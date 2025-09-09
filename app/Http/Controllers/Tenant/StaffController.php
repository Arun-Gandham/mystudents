<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    public function index()
    {
        $staff = Staff::forSchool(current_school_id())
            ->with(['user.roles'])
            ->orderBy('first_name')
            ->get();

        return view('tenant.pages.staff.index', compact('staff'));
    }

    public function show($school_sub, $id)
    {
        $staff = Staff::forSchool(current_school_id())
            ->with(['user.roles'])
            ->findOrFail($id);

        $roles = Role::forSchool(current_school_id())->get();

        return view('tenant.pages.staff.show', compact('staff','roles'));
    }

    public function create()
    {
        $roles = Role::forSchool(current_school_id())->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        return view('tenant.pages.staff.create', compact('roles','subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:50',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'roles'        => 'required|array',
            'primary_role' => 'nullable|uuid',
        ]);

        // create user
        $user = User::create([
            'school_id' => current_school_id(),
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
        ]);

        // handle photo
        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('staff_photos','public')
            : null;

        // create staff profile
        $staff = Staff::create([
            'school_id'        => current_school_id(),
            'user_id'          => $user->id,
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'surname'          => $request->surname,
            'photo'            => $photoPath,
            'experience_years' => $request->experience_years ?? 0,
            'joining_date'     => $request->joining_date,
            'designation'      => $request->designation,
            'phone'            => $request->phone,
            'address'          => $request->address,
            'is_active'        => true,
        ]);

        // assign roles → user_roles table
        foreach ($request->roles as $roleId) {
            UserRole::create([
                'id'        => Str::uuid(),
                'user_id'   => $user->id,
                'role_id'   => $roleId,
                'school_id' => current_school_id(),
                'is_primary'=> $request->primary_role == $roleId,
                'starts_on' => now(),
            ]);
        }
        $staff->subjects()->sync($request->input('subjects', []));

        return redirect()->intended(tenant_route('tenant.staff.index'))
            ->with('success','Staff created successfully');
    }

    public function edit($school_sub, $id)
    {
        $staff = Staff::forSchool(current_school_id())->with('user.roles')->findOrFail($id);
        $roles = Role::forSchool(current_school_id())->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $staff->load('subjects');
        return view('tenant.pages.staff.edit', compact('staff','roles','subjects'));
    }

    public function update(Request $request, $school_sub, $id)
    {
        $staff = Staff::forSchool(current_school_id())->with('user')->findOrFail($id);
        $user = $staff->user;

        $request->validate([
            'first_name'   => 'required|string|max:50',
            'email'        => 'required|email|unique:users,email,'.$user->id,
            'roles'        => 'required|array',
            'primary_role' => 'nullable|uuid',
        ]);

        // update user
        $user->update([
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'email'     => $request->email,
        ]);

        // update staff
        $staff->update($request->only([
            'first_name','last_name','surname',
            'experience_years','joining_date','designation',
            'phone','address','is_active'
        ]));
        $staff->subjects()->sync($request->input('subjects', []));

        // clear old roles
        UserRole::where('user_id',$user->id)->where('school_id',current_school_id())->delete();

        // re-assign roles
        foreach ($request->roles as $roleId) {
            UserRole::create([
                'id'        => Str::uuid(),
                'user_id'   => $user->id,
                'role_id'   => $roleId,
                'school_id' => current_school_id(),
                'is_primary'=> $request->primary_role == $roleId,
                'starts_on' => now(),
            ]);
        }

        return redirect()->intended(tenant_route('tenant.staff.show', ['id' => $staff->id]))
            ->with('success','Staff updated successfully');
    }

    public function destroy($school_sub, $id)
    {
        $staff = Staff::forSchool(current_school_id())->with('user')->findOrFail($id);

        // cascade delete
        UserRole::where('user_id',$staff->user->id)->delete();
        $staff->user->delete();
        $staff->delete();

        return redirect()->intended(tenant_route('tenant.staff.index'))
            ->with('success','Staff deleted successfully (with user & roles)');
    }
}
