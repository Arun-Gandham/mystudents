<?php

namespace App\Http\Controllers\Tenant\Fee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\FeeHead;

class FeeHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }
    public function index()
    {
        $feeHeads = FeeHead::where('school_id', current_school_id())->get();
        return view('tenant.pages.fees.fee_heads.index', compact('feeHeads'));
    }

    public function create()
    {
        return view('tenant.pages.fees.fee_heads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
        ]);

        $data['id'] = Str::uuid();
        $data['school_id'] = current_school_id();

        FeeHead::create($data);

        return redirect()->to(tenant_route('tenant.fees.fee-heads.index'))->with('success','Fee Head created');
    }

    public function edit($school_sub, FeeHead $feeHead)
    {
        return view('tenant.pages.fees.fee_heads.edit', compact('feeHead'));
    }

    public function update(Request $request,$school_sub, FeeHead $feeHead)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
        ]);
        $feeHead->update($data);

        return redirect()->to(tenant_route('tenant.fees.fee-heads.index'))->with('success','Fee Head updated');
    }

    public function destroy($school_sub,FeeHead $feeHead)
    {
        $feeHead->delete();
        return back()->with('success','Deleted successfully');
    }
}
