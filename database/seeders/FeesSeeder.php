<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Academic;
use App\Models\Student;
use App\Models\FeeHead;
use App\Models\SectionFee;
use App\Models\StudentFeeItem;
use App\Models\StudentFeeReceipt;
use App\Models\StudentFeePayment;
use App\Models\Section;
use Illuminate\Support\Str;

class FeesSeeder extends Seeder
{
    public function run(): void
    {
        $school   = School::where('domain', 'arun')->first();
        $academic = Academic::where('school_id', $school?->id)->where('is_current', true)->first();
        $student  = Student::first();
        $section  = Section::first();

        if (! $school || ! $academic || ! $student || ! $section) {
            $this->command->warn('❌ Missing school, academic, student, or section. Run previous seeders first.');
            return;
        }

        // 1. Create Fee Head
        $tuitionHead = FeeHead::firstOrCreate([
            'school_id' => $school->id,
            'name'      => 'Tuition Fee',
        ], [
            'code'      => 'TUI',
            'is_active' => true,
        ]);

        // 2. Create Section Fee
        $sectionFee = SectionFee::firstOrCreate([
            'school_id'   => $school->id,
            'academic_id' => $academic->id,
            'section_id'  => $section->id,
            'fee_head_id' => $tuitionHead->id,
        ], [
            'base_amount' => 5000,
            'is_optional' => false,
            'note'        => 'Default tuition fee',
            'is_active'   => true,
        ]);

        // 3. Create Student Fee Item
        $item = StudentFeeItem::firstOrCreate([
            'school_id'   => $school->id,
            'academic_id' => $academic->id,
            'student_id'  => $student->id,
            'fee_head_id' => $tuitionHead->id,
        ], [
            'base_amount'    => $sectionFee->base_amount,
            'discount_kind'  => 'none',
            'discount_value' => 0,
            'final_amount'   => $sectionFee->base_amount,
        ]);

        // 4. Create Receipt (master record)
        $receipt = StudentFeeReceipt::firstOrCreate([
            'school_id'   => $school->id,
            'academic_id' => $academic->id,
            'student_id'  => $student->id,
            'reference_no'=> 'RCPT-001',
        ], [
            'total_amount'   => 2000,
            'paid_on'        => now()->toDateString(),
            'method'         => 'cash',
            'payer_name'     => 'Mr Johnson',
            'payer_phone'    => '9876543210',
            'payer_relation' => 'Father',
            'note'           => 'Initial payment',
        ]);

        // 5. Link Receipt → Fee Item (line item)
        StudentFeePayment::firstOrCreate([
            'receipt_id'         => $receipt->id,
            'student_fee_item_id'=> $item->id,
        ], [
            'paid_amount' => 2000,
        ]);

        $this->command->info('✅ FeesSeeder completed: Tuition Fee, Section Fee, Student Fee Item, Receipt & Payment created.');
    }
}
