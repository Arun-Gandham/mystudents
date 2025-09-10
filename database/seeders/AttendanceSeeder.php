<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Academic;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendanceSheet;
use App\Models\StudentAttendanceEntry;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $school   = School::where('domain', 'arun')->first();
        $academic = Academic::where('school_id', $school->id)->where('is_current', true)->first();
        $section  = Section::first();
        $student  = Student::first();

        if (! $school || ! $academic || ! $section || ! $student) {
            $this->command->warn('Missing school, academic, section, or student. Run previous seeders first.');
            return;
        }

        // Create attendance sheet
        $sheet = StudentAttendanceSheet::firstOrCreate([
            'school_id'       => $school->id,
            'academic_id'     => $academic->id,
            'section_id'      => $section->id,
            'attendance_date' => now()->toDateString(),
            'session'         => 'morning',
        ]);

        // Add student entry
        StudentAttendanceEntry::firstOrCreate([
            'sheet_id'   => $sheet->id,
            'student_id' => $student->id,
        ], [
            'status' => 'present',
            'remarks' => 'Marked via seeder',
        ]);
    }
}
