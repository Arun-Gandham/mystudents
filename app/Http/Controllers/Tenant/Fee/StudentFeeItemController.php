<?php

namespace App\Http\Controllers\Tenant\Fee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\StudentFeeItem;
use App\Models\Section;
use App\Models\Student;
use App\Models\SectionFee;

class StudentFeeItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function bulkAssign($school_sub,$sectionId, $academicId)
    {
        $section = Section::with('students')->findOrFail($sectionId);
        $sectionFees = SectionFee::where('section_id',$sectionId)
            ->where('academic_id',$academicId)
            ->get();

        foreach ($section->students as $student) {
            foreach ($sectionFees as $fee) {
                StudentFeeItem::updateOrCreate([
                    'school_id'   => current_school_id(),
                    'academic_id' => $academicId,
                    'student_id'  => $student->id,
                    'fee_head_id' => $fee->fee_head_id,
                ], [
                    'base_amount'    => $fee->base_amount,
                    'discount_kind'  => 'none',
                    'discount_value' => 0,
                    'final_amount'   => $fee->base_amount,
                ]);
            }
        }

        return back()->with('success','Fees assigned to all students in section');
    }

    public function index($school_sub, Student $student)
    {
        $items = StudentFeeItem::with(['feeHead', 'payments'])
            ->where('student_id', $student->id)
            ->where('academic_id', current_academic_id())
            ->get();

        return view('tenant.pages.fees.student_fee_items.index', compact('student', 'items'));
    }

    /**
     * Edit a fee item (apply discount, etc.)
     */
    public function edit($school_sub, StudentFeeItem $item)
    {
        return view('tenant.pages.fees.student_fee_items.edit', compact('item'));
    }

    /**
     * Update fee item (discounts, etc.)
     */
    public function update(Request $request, $school_sub, StudentFeeItem $item)
    {
        $data = $request->validate([
            'discount_kind'  => 'required|in:none,percent,flat',
            'discount_value' => 'required|numeric|min:0',
        ]);

        $item->discount_kind  = $data['discount_kind'];
        $item->discount_value = $data['discount_value'];

        // Recalculate final amount
        if ($item->discount_kind === 'percent') {
            $item->final_amount = $item->base_amount - ($item->base_amount * $item->discount_value / 100);
        } elseif ($item->discount_kind === 'flat') {
            $item->final_amount = $item->base_amount - $item->discount_value;
        } else {
            $item->final_amount = $item->base_amount;
        }

        $item->save();

        return redirect()
            ->route('tenant.fees.student-fee-items.index', ['student' => $item->student_id, 'school_sub' => $school_sub])
            ->with('success', 'Fee item updated successfully!');
    }

    /**
     * Delete/reset a fee item
     */
    public function destroy($school_sub, StudentFeeItem $item)
    {
        $studentId = $item->student_id;
        $item->delete();

        return redirect()
            ->route('tenant.fees.student-fee-items.index', ['student' => $studentId, 'school_sub' => $school_sub])
            ->with('success', 'Fee item deleted.');
    }
}
