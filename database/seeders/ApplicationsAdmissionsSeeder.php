<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAdmission;
use App\Models\StudentJoinApplication;
use App\Models\StudentJoinApplicationLog;
use App\Models\Academic;

class ApplicationsAdmissionsSeeder extends Seeder
{
    public function run(): void
    {
        $school  = School::where('domain', 'arun')->first();
        $grade   = Grade::first();
        $section = Section::first();

        if (! $school || ! $grade || ! $section) {
            $this->command->warn('Missing school, grade, or section. Run SchoolsSeeder and GradesAndSectionsSeeder first.');
            return;
        }

        // Ensure academic year
        $academic = Academic::firstOrCreate(
            [
                'school_id' => $school->id,
                'name'      => '2025-2026',
            ],
            [
                'start_date' => now()->startOfYear(),
                'end_date'   => now()->endOfYear(),
                'is_current' => true,
            ]
        );

        // 1. Application
        $application = StudentJoinApplication::create([
            'school_id'            => $school->id,
            'academic_id'          => $academic->id,
            'application_no'       => 'APP001',
            'full_name'      => 'Alice Johnson',
            'child_dob'            => '2015-06-01',
            'child_gender'         => 'Female',
            'previous_school'      => 'Little Angels',
            'guardian_full_name'   => 'Mr Johnson',
            'guardian_relation'    => 'Father',
            'guardian_email'       => 'johnson@example.com',
            'guardian_phone'       => '9876543210',
            'address'              => '123 School Street',
            'preferred_grade_id'   => $grade->id,
            'preferred_section_id' => $section->id,
            'status'               => 'accepted',
        ]);

        // 2. Student (from application)
        $student = Student::create([
            'school_id'             => $school->id,
            'full_name'             => $application->child_full_name,
            'dob'                   => $application->child_dob,
            'gender'                => $application->child_gender,
            'status'                => 'accepted',
            'source_application_id' => $application->id,
        ]);

        // Link student back to application
        $application->update(['student_id' => $student->id]);

        // 3. Student Admission (requires student_id)
        $admission = StudentAdmission::create([
            'school_id'        => $school->id,
            'academic_id'      => $academic->id,
            'student_id'       => $student->id, // ✅ FIX
            'application_no'   => $application->application_no,
            'status'           => 'admitted',
            'applied_on'       => now(),
            'admitted_on'      => now(),
            'offered_grade_id' => $grade->id,
            'offered_section_id' => $section->id,
            'previous_school'  => $application->previous_school,
            'remarks'          => 'Admitted via seeder',
        ]);

        // 4. Log
        StudentJoinApplicationLog::create([
            'application_id' => $application->id,
            'user_id'        => null,
            'action'         => 'Admitted',
            'comment'        => 'Student admitted successfully',
        ]);
    }
}
