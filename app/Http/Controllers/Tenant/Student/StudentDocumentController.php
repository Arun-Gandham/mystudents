<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\StudentDocument;

class StudentDocumentController extends Controller
{
    public function index($school_sub, Student $student)
    {
        $documents = $student->documents()->paginate(10);
        return view('tenant.pages.documents.index', compact('student','documents'));
    }

    public function create($school_sub, Student $student)
    {
        return view('tenant.pages.documents.create', compact('student'));
    }

    public function store(Request $request, $school_sub, Student $student)
    {
        $data = $request->validate([
            'doc_type'=>'required|in:aadhaar,birth_certificate,transfer_certificate,caste_certificate,passport_photo,other',
            'file'=>'required|file|max:2048',
        ]);

        $path = $request->file('file')->store("students/{$student->id}/docs",'public');

        $student->documents()->create([
            'id'=>Str::uuid(),
            'doc_type'=>$data['doc_type'],
            'file_path'=>$path,
        ]);

        return redirect()->to(tenant_route('tenant.documents.index',$student->id))
            ->with('success','Document uploaded successfully');
    }

    public function destroy($school_sub, Student $student, StudentDocument $document)
    {
        $document->delete();
        return back()->with('success','Document deleted');
    }
}
