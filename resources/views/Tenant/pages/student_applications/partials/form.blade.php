<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Child Full Name *</label>
    <input type="text" name="child_full_name" class="form-control"
      value="{{ old('child_full_name',$application->child_full_name ?? '') }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">DOB</label>
    <input type="date" name="child_dob" class="form-control"
      value="{{ old('child_dob',$application->child_dob ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Gender</label>
    <select name="child_gender" class="form-select">
      <option value="">--Select--</option>
      @foreach(['Male','Female','Other'] as $g)
        <option value="{{ $g }}" {{ old('child_gender',$application->child_gender ?? '')==$g?'selected':'' }}>
          {{ $g }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Guardian Full Name *</label>
    <input type="text" name="guardian_full_name" class="form-control"
      value="{{ old('guardian_full_name',$application->guardian_full_name ?? '') }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Relation</label>
    <input type="text" name="guardian_relation" class="form-control"
      value="{{ old('guardian_relation',$application->guardian_relation ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Guardian Email</label>
    <input type="email" name="guardian_email" class="form-control"
      value="{{ old('guardian_email',$application->guardian_email ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Phone</label>
    <input type="text" name="guardian_phone" class="form-control"
      value="{{ old('guardian_phone',$application->guardian_phone ?? '') }}">
  </div>
  <div class="col-md-9">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control">{{ old('address',$application->address ?? '') }}</textarea>
  </div>

  <div class="col-md-6">
    <label class="form-label">Preferred Grade</label>
    <select name="preferred_grade_id" class="form-select">
      <option value="">--Select--</option>
      @foreach($grades as $grade)
        <option value="{{ $grade->id }}"
          {{ old('preferred_grade_id',$application->preferred_grade_id ?? '')==$grade->id?'selected':'' }}>
          {{ $grade->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Preferred Section</label>
    <select name="preferred_section_id" class="form-select">
      <option value="">--Select--</option>
      @foreach($sections as $section)
        <option value="{{ $section->id }}"
          {{ old('preferred_section_id',$application->preferred_section_id ?? '')==$section->id?'selected':'' }}>
          {{ $section->name }}
        </option>
      @endforeach
    </select>
  </div>

  @if(isset($application))
  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      @foreach(['lead','submitted','reviewing','offered','accepted','rejected','no_response','withdrawn'] as $s)
        <option value="{{ $s }}" {{ old('status',$application->status ?? '')==$s?'selected':'' }}>
          {{ ucfirst($s) }}
        </option>
      @endforeach
    </select>
  </div>
  @endif
</div>
