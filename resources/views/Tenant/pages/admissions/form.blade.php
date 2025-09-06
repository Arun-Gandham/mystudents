{{-- ================= STUDENT DETAILS ================= --}}
<div class="card mb-3 p-3">
  <h5 class="mb-3">Student Details</h5>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Full Name *</label>
      <input type="text" name="full_name"
             value="{{ old('full_name', $student->full_name ?? $application->child_full_name ?? '') }}"
             class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Date of Birth</label>
      <input type="date" name="dob"
             value="{{ old('dob', $student->dob ?? $application->child_dob ?? '') }}"
             class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Gender</label>
      <select name="gender" class="form-select">
        <option value="">-- Select --</option>
        @foreach(['Male','Female','Other'] as $g)
          <option value="{{ $g }}" {{ old('gender', $student->gender ?? $application->child_gender ?? '')==$g?'selected':'' }}>{{ $g }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>

{{-- ================= GUARDIAN DETAILS ================= --}}
<div class="card mb-3 p-3">
  <h5 class="mb-3">Guardian Details</h5>
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Guardian Name</label>
      <input type="text" name="guardian_full_name"
             value="{{ old('guardian_full_name',$application->guardian_full_name ?? '') }}"
             class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Relation</label>
      <input type="text" name="guardian_relation"
             value="{{ old('guardian_relation',$application->guardian_relation ?? '') }}"
             class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Email</label>
      <input type="email" name="guardian_email"
             value="{{ old('guardian_email',$application->guardian_email ?? '') }}"
             class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Phone</label>
      <input type="text" name="guardian_phone"
             value="{{ old('guardian_phone',$application->guardian_phone ?? '') }}"
             class="form-control">
    </div>
    <div class="col-md-8">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control">{{ old('address',$application->address ?? '') }}</textarea>
    </div>
  </div>
</div>

    {{-- ================= APPLICATION DETAILS ================= --}}
    <div class="card mb-3 p-3">
    <h5 class="mb-3">Application Info</h5>
    <div class="row g-3">
        <div class="col-md-6">
    <label class="form-label">Application No</label>
    <input type="text" name="application_no"
            value="{{ old('application_no', 
                $application->application_no 
                    ?? $admission->application_no 
                    ?? 'APP'.now()->year.rand(1000,9999)) }}"
            class="form-control" readonly>
    </div>
    <div class="col-md-6">
      <label class="form-label">Previous School</label>
      <input type="text" name="previous_school"
             value="{{ old('previous_school',$application->previous_school ?? $admission->previous_school ?? '') }}"
             class="form-control">
    </div>
    <div class="col-md-12">
      <label class="form-label">Remarks</label>
      <textarea name="remarks" class="form-control">{{ old('remarks',$application->remarks ?? $admission->remarks ?? '') }}</textarea>
    </div>
  </div>
</div>

{{-- ================= ADMISSION DETAILS ================= --}}
<div class="card mb-3 p-3">
  <h5 class="mb-3">Admission Details</h5>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Grade *</label>
      <select name="grade_id" class="form-select" required>
        @foreach(\App\Models\Grade::forSchool(current_school_id())->get() as $grade)
          <option value="{{ $grade->id }}"
            {{ old('grade_id',$admission->offered_grade_id ?? $application->preferred_grade_id ?? '')==$grade->id?'selected':'' }}>
            {{ $grade->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Section</label>
      <select name="section_id" class="form-select">
        <option value="">-- Optional --</option>
        @foreach(\App\Models\Section::get() as $sec)
          <option value="{{ $sec->id }}"
            {{ old('section_id',$admission->offered_section_id ?? $application->preferred_section_id ?? '')==$sec->id?'selected':'' }}>
            {{ $sec->name }}
          </option>
        @endforeach
      </select>
    </div>
  </div>
  {{-- ================= SUBMIT BUTTON ================= --}}
<div class="mt-3 text-end">
  <a href="{{ tenant_route('tenant.admissions.index') }}" class="btn btn-light">Cancel</a>
  <button type="submit" class="btn btn-success">
    {{ isset($admission) && $admission->id ? 'Update Admission' : 'Admit Student' }}
  </button>
</div>

</div>
