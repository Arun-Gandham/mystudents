<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Academic;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\ExamResult;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Section;

class ExamsAndResultsSeeder extends Seeder
{
    public function run(): void
    {
        $school   = School::where('domain', 'arun')->first();
        $academic = Academic::where('school_id', $school->id)->where('is_current', true)->first();
        $section  = Section::first();
        $subject  = Subject::first();
        $student  = Student::first();

        if (! $school || ! $academic || ! $section || ! $subject || ! $student) {
            $this->command->warn('Missing school, academic, section, subject, or student. Run previous seeders first.');
            return;
        }

        // 1. Create Exam
        $exam = Exam::firstOrCreate([
            'school_id'   => $school->id,
            'academic_id' => $academic->id,
            'section_id'  => $section->id,
            'name'        => 'Midterm Exam',
        ], [
            'starts_on'    => now()->addDays(7),
            'ends_on'      => now()->addDays(10),
            'is_published' => true,
            'note'         => 'Seeder generated exam',
        ]);

        // 2. Add Subject to Exam
        $examSubject = ExamSubject::firstOrCreate([
            'exam_id'    => $exam->id,
            'subject_id' => $subject->id,
        ], [
            'max_marks' => 100,
            'pass_marks'=> 35,
            'order_no'  => 1,
            'exam_date' => now()->addDays(8),
        ]);

        // 3. Add Result for Student
        ExamResult::firstOrCreate([
            'exam_id'    => $exam->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ], [
            'marks_obtained' => 85,
            'grade'          => 'A',
            'remarks'        => 'Good performance',
            'entered_at'     => now(),
        ]);
    }
}
