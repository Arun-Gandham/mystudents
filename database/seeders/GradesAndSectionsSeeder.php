<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Section;
use App\Models\School;

class GradesAndSectionsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have a school with domain "arun"
        $school = School::firstOrCreate(
            ['domain' => 'arun'],
            ['name' => 'Pocket School', 'is_active' => true]
        );

        $grades = [
            ['name' => 'Grade 1', 'ordinal' => 1],
            ['name' => 'Grade 2', 'ordinal' => 2],
            ['name' => 'Grade 3', 'ordinal' => 3],
        ];

        foreach ($grades as $g) {
            $grade = Grade::create([
                'school_id' => $school->id,
                'name'      => $g['name'],
                'ordinal'   => $g['ordinal'],
            ]);

            Section::create([
                'grade_id'  => $grade->id,
                'name'      => 'A',
            ]);

            Section::create([
                'grade_id'  => $grade->id,
                'name'      => 'B',
            ]);
        }
    }
}
