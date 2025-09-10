<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $roles = ['super_admin', 'admin', 'teacher', 'student', 'guardian'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Permissions (using 'key')
        $permissions = [
            ['key' => 'school:create', 'description' => 'Create schools', 'group_name' => 'School'],
            ['key' => 'school:view', 'description' => 'View schools', 'group_name' => 'School'],
            ['key' => 'school:update', 'description' => 'Update schools', 'group_name' => 'School'],
            ['key' => 'school:delete', 'description' => 'Delete schools', 'group_name' => 'School'],

            ['key' => 'grade:manage', 'description' => 'Manage grades', 'group_name' => 'Academics'],
            ['key' => 'section:manage', 'description' => 'Manage sections', 'group_name' => 'Academics'],

            ['key' => 'student:admit', 'description' => 'Admit students', 'group_name' => 'Students'],
            ['key' => 'student:enroll', 'description' => 'Enroll students', 'group_name' => 'Students'],

            ['key' => 'timetable:manage', 'description' => 'Manage timetable', 'group_name' => 'Academics'],
            ['key' => 'attendance:mark', 'description' => 'Mark attendance', 'group_name' => 'Academics'],

            ['key' => 'exam:manage', 'description' => 'Manage exams', 'group_name' => 'Exams'],
            ['key' => 'fees:collect', 'description' => 'Collect fees', 'group_name' => 'Finance'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['key' => $perm['key']], $perm);
        }

        // Assign all permissions to super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::all()->pluck('id'));
        }
    }
}
