<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\School;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\SectionDayTimetable;
use App\Models\SectionDayPeriod;
use App\Models\Academic;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('domain', 'arun')->first();
        $academic = Academic::where('school_id', $school->id)->where('is_current', true)->first();
        $section = Section::first();
        $subject = Subject::first();
        $teacher = User::where('email', 'teacher1@test.com')->first();

        if (! $school || ! $academic || ! $section || ! $subject || ! $teacher) {
            $this->command->warn('Missing school, academic, section, subject, or teacher. Run earlier seeders first.');
            return;
        }

        // Create timetable for Monday
        $dayTimetable = SectionDayTimetable::firstOrCreate([
            'school_id'   => $school->id,
            'academic_id' => $academic->id,
            'section_id'  => $section->id,
            'day'         => 'mon',
        ], [
            'title' => 'Monday Schedule',
            'is_active' => true,
        ]);

        // Add a period
        SectionDayPeriod::firstOrCreate([
            'day_timetable_id' => $dayTimetable->id,
            'period_no'        => 1,
        ], [
            'starts_at'   => '09:00',
            'ends_at'     => '10:00',
            'subject_id'  => $subject->id,
            'teacher_id'  => $teacher->id,
            'room'        => 'Room 101',
            'note'        => 'First class of the day',
        ]);
    }
}
