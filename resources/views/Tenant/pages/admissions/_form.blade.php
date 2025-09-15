@csrf
<div class="row">
  <div class="col-md-4">
    <label>First Name</label>
    <input type="text" name="first_name" class="form-control" value="{{ old('first_name',$admission->student->first_name ?? $application->first_name ?? '') }}" required>
  </div>
  <div class="col-md-4">
    <label>Middle Name</label>
    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name',$admission->student->middle_name ?? $application->middle_name ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Last Name</label>
    <input type="text" name="last_name" class="form-control" value="{{ old('last_name',$admission->student->last_name ?? $application->last_name ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-3">
    <label>DOB</label>
    <input type="date" name="dob" class="form-control" value="{{ old('dob',$admission->student->dob ?? $application->dob ?? '') }}">
  </div>
  <div class="col-md-3">
    <label>Gender</label>
    <select name="gender" class="form-control">
      <option value="">Select</option>
      <option value="male" {{ old('gender',$admission->student->gender ?? $application->gender ?? '')=='male'?'selected':'' }}>Male</option>
      <option value="female" {{ old('gender',$admission->student->gender ?? $application->gender ?? '')=='female'?'selected':'' }}>Female</option>
    </select>
  </div>
  <div class="col-md-6">
    <label>Previous School</label>
    <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school',$admission->previous_school ?? $application->previous_school ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-6">
    <label>Grade</label>
    <select name="grade_id" class="form-control" required>
      <option value="">Select Grade</option>
      @foreach($grades as $grade)
        <option value="{{ $grade->id }}" {{ old('grade_id',$admission->offered_grade_id ?? $application->preferred_grade_id ?? '')==$grade->id?'selected':'' }}>
          {{ $grade->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label>Section</label>
    <select name="section_id" class="form-control">
      <option value="">Select Section</option>
      @foreach($sections as $section)
        <option value="{{ $section->id }}" {{ old('section_id',$admission->offered_section_id ?? $application->preferred_section_id ?? '')==$section->id?'selected':'' }}>
          {{ $section->name }}
        </option>
      @endforeach
    </select>
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-12">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control">{{ old('remarks',$admission->remarks ?? '') }}</textarea>
  </div>
</div>

<button class="btn btn-success mt-3">Save</button>
<a href="{{ tenant_route('tenant.admissions.index') }}" class="btn btn-secondary mt-3">Cancel</a>
