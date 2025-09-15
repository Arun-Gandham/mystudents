<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\StudentAddress;

class StudentAddressController extends Controller
{
    public function index($school_sub, Student $student)
    {
        $addresses = $student->addresses()->paginate(10);
        return view('tenant.pages.addresses.index', compact('student','addresses'));
    }

    public function create($school_sub, Student $student)
    {
        return view('tenant.pages.addresses.create', compact('student'));
    }

    public function store(Request $request, $school_sub, Student $student)
    {
        $data = $request->validate([
            'address_line1'=>'required|string|max:255',
            'address_line2'=>'nullable|string|max:255',
            'city'=>'required|string|max:100',
            'district'=>'nullable|string|max:100',
            'state'=>'required|string|max:100',
            'pincode'=>'required|string|max:10',
            'address_type'=>'required|in:permanent,current',
        ]);

        $student->addresses()->create(array_merge($data,['id'=>Str::uuid()]));

        return redirect()->to(tenant_route('tenant.addresses.index',$student->id))
            ->with('success','Address added successfully');
    }

    public function edit($school_sub, Student $student, StudentAddress $address)
    {
        return view('tenant.pages.addresses.edit', compact('student','address'));
    }

    public function update(Request $request, $school_sub, Student $student, StudentAddress $address)
    {
        $data = $request->validate([
            'address_line1'=>'required|string|max:255',
            'address_line2'=>'nullable|string|max:255',
            'city'=>'required|string|max:100',
            'district'=>'nullable|string|max:100',
            'state'=>'required|string|max:100',
            'pincode'=>'required|string|max:10',
            'address_type'=>'required|in:permanent,current',
        ]);

        $address->update($data);

        return redirect()->to(tenant_route('tenant.addresses.index',$student->id))
            ->with('success','Address updated');
    }

    public function destroy($school_sub, Student $student, StudentAddress $address)
    {
        $address->delete();
        return back()->with('success','Address deleted');
    }
}
