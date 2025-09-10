<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SchoolsSeeder extends Seeder
{
    public function run(): void
    {
        // Create schools
        $schools = [
            ['domain' => 'arun', 'name' => 'Arun International School'],
            ['domain' => 'school1', 'name' => 'School One'],
            ['domain' => 'school2', 'name' => 'School Two'],
            ['domain' => 'school3', 'name' => 'School Three'],
        ];

        foreach ($schools as $s) {
            School::firstOrCreate(['domain' => $s['domain']], [
                'name' => $s['name'],
                'is_active' => true,
            ]);
        }

        // Super admin user in "arun" school
        $school = School::where('domain', 'arun')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'Super Admin',
                'password' => Hash::make('password'),
                'school_id' => $school?->id,
                'is_active' => true,
            ]
        );

        if ($superAdminRole) {
            $user->roles()->sync([$superAdminRole->id]);
        }
    }
}
