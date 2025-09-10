<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\School;
use App\Models\User;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class StaffAndSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        // Get Arun school
        $school = School::where('domain', 'arun')->first();

        if (! $school) {
            $this->command->warn('No school with domain "arun" found. Run SchoolsSeeder first.');
            return;
        }

        // 1. Create a teacher user
        $user = User::firstOrCreate(
            ['email' => 'teacher1@test.com'],
            [
                'school_id' => $school->id,
                'full_name' => 'John Teacher',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // 2. Create staff profile for that user
        $staff = Staff::firstOrCreate(
            [
                'user_id'   => $user->id,
                'school_id' => $school->id,
            ],
            [
                'first_name'       => 'John',
                'last_name'        => 'Teacher',
                'joining_date'     => now(),
                'designation'      => 'Teacher',
                'phone'            => '9876543210',
                'experience_years' => 5,
            ]
        );

        // 3. Create subjects and attach to staff
        $subjects = ['Math', 'Science', 'English'];

        foreach ($subjects as $sub) {
            $subject = Subject::firstOrCreate(
                ['school_id' => $school->id, 'name' => $sub],
                ['code' => strtoupper(substr($sub, 0, 3))]
            );

            // Insert pivot with UUID
            DB::table('staff_subject')->updateOrInsert(
                [
                    'staff_id'   => $staff->id,
                    'subject_id' => $subject->id,
                ],
                [
                    'id'         => Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
