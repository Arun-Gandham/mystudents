<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\SchoolDetail;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

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
            $school = School::firstOrCreate(['domain' => $s['domain']], [
                'name' => $s['name'],
                'is_active' => true,
            ]);

            // Ensure details row exists with sensible defaults matching schema
            SchoolDetail::updateOrCreate(
                ['school_id' => $school->id],
                [
                    'phone'            => null,
                    'alt_phone'        => null,
                    'landline'         => null,
                    'email'            => null,
                    'website'          => null,
                    'logo_url'         => null,
                    'favicon_url'      => null,
                    'address_line1'    => null,
                    'address_line2'    => null,
                    'city'             => null,
                    'state'            => null,
                    'postal_code'      => null,
                    'country_code'     => null,
                    'principal_id'     => null,
                    'established_year' => null,
                    'affiliation_no'   => null,
                    'note'             => null,
                    // App settings
                    'theme'            => 'system',
                    'primary_color'    => '#4f46e5',
                    'secondary_color'  => '#0ea5e9',
                    'timezone'         => 'UTC',
                    'locale'           => 'en',
                    'date_format'      => 'd M Y',
                    // Modules: enable all by default so tenants see everything initially
                    'enabled_modules'  => array_keys(config('modules.list')),
                ]
            );
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
