@extends('tenant.baselayout')
@section('title','Admit Student')

@section('content')
<div class="container py-4">
  <h4>Admit Student — Application #{{ $application->application_no }}</h4>
  
  <form method="POST" action="{{ tenant_route('tenant.applications.admit.store',['application'=>$application->id]) }}">
    @csrf
    
    {{-- ================= STUDENT DETAILS ================= --}}
    <div class="card mb-3 p-3">
      <h5 class="mb-3">Student Details</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input type="text" name="full_name" value="{{ old('full_name',$application->child_full_name) }}" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Date of Birth</label>
          <input type="date" name="dob" value="{{ old('dob',$application->child_dob) }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">-- Select --</option>
            <option value="Male" {{ old('gender',$application->child_gender)=='Male'?'selected':'' }}>Male</option>
            <option value="Female" {{ old('gender',$application->child_gender)=='Female'?'selected':'' }}>Female</option>
            <option value="Other" {{ old('gender',$application->child_gender)=='Other'?'selected':'' }}>Other</option>
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
          <input type="text" name="guardian_full_name" value="{{ old('guardian_full_name',$application->guardian_full_name) }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Relation</label>
          <input type="text" name="guardian_relation" value="{{ old('guardian_relation',$application->guardian_relation) }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Email</label>
          <input type="email" name="guardian_email" value="{{ old('guardian_email',$application->guardian_email) }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone</label>
          <input type="text" name="guardian_phone" value="{{ old('guardian_phone',$application->guardian_phone) }}" class="form-control">
        </div>
        <div class="col-md-8">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control">{{ old('address',$application->address) }}</textarea>
        </div>
      </div>
    </div>

    {{-- ================= APPLICATION DETAILS ================= --}}
    <div class="card mb-3 p-3">
      <h5 class="mb-3">Application Info</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Previous School</label>
          <input type="text" name="previous_school" value="{{ old('previous_school',$application->previous_school) }}" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Remarks</label>
          <input type="text" name="remarks" value="{{ old('remarks',$application->remarks) }}" class="form-control">
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
              <option value="{{ $grade->id }}" {{ $application->preferred_grade_id==$grade->id?'selected':'' }}>
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
              <option value="{{ $sec->id }}" {{ $application->preferred_section_id==$sec->id?'selected':'' }}>
                {{ $sec->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="text-end">
      <a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-success">Confirm Admission</button>
    </div>
  </form>
</div>
@endsection
