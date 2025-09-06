<?php
namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentJoinApplication;
use App\Models\StudentAdmission;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentAdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenant');
    }

    /** Show admission form pre-filled from application */
    public function createFromApplication($school_sub, StudentJoinApplication $application)
    {
        return view('tenant.pages.admissions.create-from-application', [
            'application' => $application
        ]);
    }

    /** Store admission, updating all related tables */
    public function storeFromApplication(Request $request, StudentJoinApplication $application)
    {
        $data = $request->validate([
            // Student
            'full_name'         => ['required', 'string', 'max:150'],
            'dob'               => ['nullable', 'date'],
            'gender'            => ['nullable', 'string', 'max:10'],

            // Guardian / Application
            'guardian_full_name'=> ['nullable', 'string', 'max:150'],
            'guardian_relation' => ['nullable', 'string', 'max:100'],
            'guardian_email'    => ['nullable', 'email'],
            'guardian_phone'    => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:500'],
            'previous_school'   => ['nullable', 'string', 'max:255'],
            'remarks'           => ['nullable', 'string'],

            // Admission
            'grade_id'          => ['required', 'uuid'],
            'section_id'        => ['nullable', 'uuid'],
        ]);

        /** -------------------------
         * 1. Create Student
         * ------------------------*/
        $student = Student::create([
            'school_id' => $application->school_id,
            'full_name' => $data['full_name'],
            'dob'       => $data['dob'] ?? null,
            'gender'    => $data['gender'] ?? null,
            'admission_no' => Str::upper('ADM'.now()->year.rand(1000,9999)),
            'source_application_id' => $application->id,
        ]);

        /** -------------------------
         * 2. Create Admission Record
         * ------------------------*/
        $admission = StudentAdmission::create([
            'school_id'          => $application->school_id,
            'academic_id'        => current_academic_id(),
            'student_id'         => $student->id,
            'application_no'     => $application->application_no,
            'status'             => 'admitted',
            'applied_on'         => $application->submitted_on,
            'admitted_on'        => now(),
            'offered_grade_id'   => $data['grade_id'],
            'offered_section_id' => $data['section_id'],
            'previous_school'    => $data['previous_school'] ?? $application->previous_school,
            'remarks'            => $data['remarks'] ?? $application->remarks,
        ]);

        /** -------------------------
         * 3. Create Enrollment
         * ------------------------*/
        StudentEnrollment::create([
            'student_id' => $student->id,
            'academic_id'=> current_academic_id(),
            'grade_id'   => $data['grade_id'],
            'section_id' => $data['section_id'],
            'joined_on'  => now(),
        ]);

        /** -------------------------
         * 4. Update Application
         * ------------------------*/
        $application->update([
            'status'            => 'accepted',
            'student_id'        => $student->id,
            'guardian_full_name'=> $data['guardian_full_name'],
            'guardian_relation' => $data['guardian_relation'],
            'guardian_email'    => $data['guardian_email'],
            'guardian_phone'    => $data['guardian_phone'],
            'address'           => $data['address'],
            'previous_school'   => $data['previous_school'],
            'remarks'           => $data['remarks'],
        ]);

        return redirect()->to(
            tenant_route('tenant.students.show', ['student' => $student->id])
        )->with('success', 'Student admitted successfully.');
    }
}
