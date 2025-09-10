<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolsSeeder::class,
            RolesAndPermissionsSeeder::class,
            GradesAndSectionsSeeder::class,
            StaffAndSubjectsSeeder::class,
            ApplicationsAdmissionsSeeder::class,
            TimetableSeeder::class,
            AttendanceSeeder::class,
            FeesSeeder::class,
            ExamsAndResultsSeeder::class,
            CreateAdminUserSeeder::class
        ]);
    }
}
