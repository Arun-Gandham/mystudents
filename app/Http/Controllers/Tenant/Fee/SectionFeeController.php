<?php

namespace App\Http\Controllers\Tenant\Fee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SectionFee;
use App\Models\Section;
use App\Models\Academic;
use App\Models\FeeHead;

class SectionFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function index()
    {
        $fees = SectionFee::with(['section','feeHead'])
            ->where('school_id', current_school_id())
            ->get();

        return view('tenant.pages.fees.section_fees.index', compact('fees'));
    }

    public function create()
    {
        $sections = Section::all();
        $academics = Academic::where('school_id', current_school_id())->get();
        $feeHeads = FeeHead::where('school_id', current_school_id())->get();
        return view('tenant.pages.fees.section_fees.create', compact('sections','academics','feeHeads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_id' => 'required|uuid',
            'section_id'  => 'required|uuid',
            'fee_head_id' => 'required|uuid',
            'base_amount' => 'required|numeric|min:0',
        ]);

        $data['id'] = Str::uuid();
        $data['school_id'] = current_school_id();

        SectionFee::create($data);

        return redirect()->to(tenant_route('tenant.fees.section-fees.index'))->with('success','Section Fee assigned');
    }
}
