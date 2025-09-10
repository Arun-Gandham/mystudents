<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $app = Application::create([
        'application_no' => 'APP001',
        'child_full_name' => 'Alice Johnson',
        'child_dob' => '2015-06-01',
        'guardian_full_name' => 'Mr Johnson',
        'guardian_phone' => '9876543210',
        'preferred_grade_id' => Grade::first()->id,
        'status' => 'admitted',
    ]);

    Admission::create([
        'application_id' => $app->id,
        'admission_date' => now(),
    ]);

    Enrollment::create([
        'application_id' => $app->id,
        'student_id' => Student::create([
            'full_name' => $app->child_full_name,
            'dob' => $app->child_dob,
        ])->id,
        'section_id' => Section::first()->id,
    ]);

    StudentJoinApplicationLog::create([
        'application_id' => $app->id,
        'user_id' => 1,
        'action' => 'Admitted',
        'comment' => 'Student admitted successfully'
    ]);
}

}
