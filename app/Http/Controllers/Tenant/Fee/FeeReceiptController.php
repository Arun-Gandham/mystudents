<?php

namespace App\Http\Controllers\Tenant\Fee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\StudentFeeItem;
use App\Models\StudentFeeReceipt;
use App\Models\StudentFeePayment;

class FeeReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function index($school_sub, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $receipts = StudentFeeReceipt::where('student_id', $studentId)
            ->orderByDesc('paid_on')
            ->paginate(10); // 10 per page

        return view('tenant.pages.fees.receipts.index', compact('student', 'receipts'));
    }
    public function create($school_sub,$studentId)
    {
        $student = Student::findOrFail($studentId);
        $feeItems = StudentFeeItem::where('student_id',$studentId)->with('payments')->get();
        return view('tenant.pages.fees.receipts.create', compact('student','feeItems'));
    }

    public function store(Request $request,$school_sub, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $data = $request->validate([
            'total_amount'   => 'required|numeric|min:1',
            'paid_on'        => 'required|date',
            'method'         => 'nullable|string',
            'reference_no'   => 'nullable|string',
            'payer_name'     => 'nullable|string|max:150',
            'payer_phone'    => 'nullable|string|max:20',
            'payer_relation' => 'nullable|string|max:50',
            'allocations'    => 'required|array',
        ]);

        $receipt = StudentFeeReceipt::create([
            'id'             => Str::uuid(),
            'school_id'      => current_school_id(),
            'academic_id'    => current_academic_id(),
            'student_id'     => $student->id,
            'total_amount'   => $data['total_amount'],
            'paid_on'        => $data['paid_on'],
            'method'         => $data['method'],
            'reference_no'   => $data['reference_no'],
            'payer_name'     => $data['payer_name'],
            'payer_phone'    => $data['payer_phone'],
            'payer_relation' => $data['payer_relation'],
            'note'           => $request->note,
        ]);

        foreach ($data['allocations'] as $itemId => $amount) {
            if ($amount > 0) {
                StudentFeePayment::create([
                    'id'                 => Str::uuid(),
                    'receipt_id'         => $receipt->id,
                    'student_fee_item_id'=> $itemId,
                    'paid_amount'        => $amount,
                ]);
            }
        }

        return redirect()->route('tenant.fee_receipts.show', [$studentId, $receipt->id]);
    }

    public function show($school_sub,$studentId, $receiptId)
    {
        $receipt = StudentFeeReceipt::with(['payments.item'])->findOrFail($receiptId);
        return view('tenant.pages.fees.receipts.show', compact('receipt'));
    }
    public function edit($school_sub, $studentId, $receiptId)
    {
        $student = Student::findOrFail($studentId);
        $receipt = StudentFeeReceipt::with(['payments.item'])->findOrFail($receiptId);

        return view('tenant.pages.fees.receipts.edit', compact('student', 'receipt'));
    }

    public function allReceipts()
    {
        $receipts = StudentFeeReceipt::with(['student'])
            ->orderByDesc('paid_on')
            ->paginate(20); // 20 per page

        return view('tenant.pages.fees.receipts.all', compact('receipts'));
    }

    public function update(Request $request, $school_sub, $studentId, $receiptId)
    {
        $receipt = StudentFeeReceipt::findOrFail($receiptId);

        $data = $request->validate([
            'total_amount'   => 'required|numeric|min:1',
            'paid_on'        => 'required|date',
            'method'         => 'nullable|string',
            'reference_no'   => 'nullable|string',
            'payer_name'     => 'nullable|string|max:150',
            'payer_phone'    => 'nullable|string|max:20',
            'payer_relation' => 'nullable|string|max:50',
            'note'           => 'nullable|string',
        ]);

        $receipt->update($data);

        return redirect()->to(tenant_route('tenant.fees.fee-receipts.index', ['student' => $studentId]))
                        ->with('success', 'Receipt updated successfully.');
    }
}
