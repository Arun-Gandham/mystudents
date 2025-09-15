<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\StudentGuardian;
use Illuminate\Support\Facades\DB;

class StudentGuardianController extends Controller
{
    public function index($school_sub, Student $student)
    {
        $guardians = $student->guardians()->paginate(10);
        return view('tenant.pages.guardians.index', compact('student','guardians'));
    }

    public function create($school_sub, Student $student)
    {
        return view('tenant.pages.guardians.create', compact('student'));
    }

    public function store(Request $request, $school_sub, Student $student)
    {
        $data = $request->validate([
            'full_name'=>'required|string|max:150',
            'relation'=>'required|string|max:50',
            'email'=>'nullable|email|max:150',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string',
            'is_primary'=>'nullable|boolean',
        ]);

        $student->guardians()->create([
            'id'=>Str::uuid(),
            'full_name'=>$data['full_name'],
            'relation'=>$data['relation'],
            'email'=>$data['email'] ?? null,
            'phone_e164'=>$data['phone'] ?? null,
            'address'=>$data['address'] ?? null,
            'is_primary'=>$data['is_primary'] ?? false,
        ]);

        return redirect()->to(tenant_route('tenant.guardians.index',$student->id))
            ->with('success','Guardian added successfully');
    }

    public function edit($school_sub, Student $student, StudentGuardian $guardian)
    {
        return view('tenant.pages.guardians.edit', compact('student','guardian'));
    }

    public function update(Request $request, $school_sub, Student $student, StudentGuardian $guardian)
    {
        $data = $request->validate([
            'full_name'=>'required|string|max:150',
            'relation'=>'required|string|max:50',
            'email'=>'nullable|email|max:150',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string',
            'is_primary'=>'nullable|boolean',
        ]);

        $guardian->update($data);

        return redirect()->to(tenant_route('tenant.guardians.index',$student->id))
            ->with('success','Guardian updated');
    }

    public function destroy($school_sub, Student $student, StudentGuardian $guardian)
    {
        $guardian->delete();
        return back()->with('success','Guardian removed');
    }
}
