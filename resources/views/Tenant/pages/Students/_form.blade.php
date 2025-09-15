@csrf

{{-- =======================
  Basic Info
======================= --}}
<div class="row">
  <div class="col-md-4">
    <label>Admission No</label>
    <input type="text" name="admission_no" class="form-control"
           value="{{ old('admission_no', $student->admission_no ?? '') }}" readonly>
  </div>
  <div class="col-md-4">
    <label>First Name <span class="text-danger">*</span></label>
    <input type="text" name="first_name" class="form-control"
           value="{{ old('first_name', $student->first_name ?? '') }}" required>
  </div>
  <div class="col-md-4">
    <label>Middle Name</label>
    <input type="text" name="middle_name" class="form-control"
           value="{{ old('middle_name', $student->middle_name ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-4">
    <label>Last Name</label>
    <input type="text" name="last_name" class="form-control"
           value="{{ old('last_name', $student->last_name ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Date of Birth</label>
    <input type="date" name="dob" class="form-control"
           value="{{ old('dob', $student->dob ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Gender</label>
    <select name="gender" class="form-control">
      <option value="">Select</option>
      <option value="Male"   {{ old('gender', $student->gender ?? '')=='Male'?'selected':'' }}>Male</option>
      <option value="Female" {{ old('gender', $student->gender ?? '')=='Female'?'selected':'' }}>Female</option>
      <option value="Other"  {{ old('gender', $student->gender ?? '')=='Other'?'selected':'' }}>Other</option>
    </select>
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-4">
    <label>Aadhaar No</label>
    <input type="text" name="aadhaar_no" maxlength="12" class="form-control"
           value="{{ old('aadhaar_no', $student->aadhaar_no ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Religion</label>
    <input type="text" name="religion" class="form-control"
           value="{{ old('religion', $student->religion ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Caste</label>
    <input type="text" name="caste" class="form-control"
           value="{{ old('caste', $student->caste ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-4">
    <label>Category</label>
    <input type="text" name="category" class="form-control"
           value="{{ old('category', $student->category ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Blood Group</label>
    <input type="text" name="blood_group" class="form-control"
           value="{{ old('blood_group', $student->blood_group ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control"
           value="{{ old('phone', $student->phone ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-6">
    <label>Email</label>
    <input type="email" name="email" class="form-control"
           value="{{ old('email', $student->email ?? '') }}">
  </div>
  <div class="col-md-6">
    <label>Photo</label>
    <input type="file" name="photo" class="form-control"
           onchange="previewPhoto(event)">
    @if(!empty($student->photo))
      <div class="mt-1">
        <img src="{{ asset('storage/'.$student->photo) }}" width="80" class="img-thumbnail" id="photo-preview">
      </div>
    @else
      <img id="photo-preview" style="max-width:80px; display:none;">
    @endif
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-6">
    <label>Grade <span class="text-danger">*</span></label>
    <select name="grade_id" class="form-control" required>
      <option value="">Select Grade</option>
      @foreach($grades as $grade)
        <option value="{{ $grade->id }}"
          {{ old('grade_id', isset($student) && $student->enrollments->first() ? $student->enrollments->first()->grade_id : '') == $grade->id ? 'selected' : '' }}>
          {{ $grade->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label>Section</label>
    <select name="section_id" class="form-control">
      <option value="">Select Section</option>
      @foreach($sections ?? [] as $section)
        <option value="{{ $section->id }}"
          {{ old('section_id', optional($student->enrollments->first())->section_id ?? '') == $section->id ? 'selected' : '' }}>
          {{ $section->name }}
        </option>
      @endforeach
    </select>
  </div>
</div>

<hr>

{{-- =======================
  Guardians Repeater
======================= --}}
<h5>Guardians</h5>
<div id="guardian-repeater">
  @php $guardians = old('guardians', $student->guardians ?? []); @endphp
  @foreach($guardians as $i => $g)
    <div class="guardian-row mb-2 border p-2 row">
      <div class="col-md-3">
        <input type="text" name="guardians[{{$i}}][full_name]" class="form-control" placeholder="Full Name"
               value="{{ $g['full_name'] ?? $g->full_name ?? '' }}" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="guardians[{{$i}}][relation]" class="form-control" placeholder="Relation"
               value="{{ $g['relation'] ?? $g->relation ?? '' }}">
      </div>
      <div class="col-md-2">
        <input type="text" name="guardians[{{$i}}][phone]" class="form-control" placeholder="Phone"
               value="{{ $g['phone'] ?? $g->phone_e164 ?? '' }}">
      </div>
      <div class="col-md-3">
        <input type="email" name="guardians[{{$i}}][email]" class="form-control" placeholder="Email"
               value="{{ $g['email'] ?? $g->email ?? '' }}">
      </div>
      <div class="col-md-2">
        <label><input type="checkbox" name="guardians[{{$i}}][is_primary]" value="1"
          {{ (!empty($g['is_primary']) || (!empty($g->is_primary))) ? 'checked' : '' }}> Primary</label>
      </div>
    </div>
  @endforeach
</div>
<button type="button" class="btn btn-sm btn-secondary" onclick="addGuardian()">+ Add Guardian</button>

<hr>

{{-- =======================
  Addresses Repeater
======================= --}}
<h5>Addresses</h5>
<div id="address-repeater">
  @php $addresses = old('addresses', $student->addresses ?? []); @endphp
  @foreach($addresses as $i => $a)
    <div class="address-row mb-2 border p-2 row">
      <div class="col-md-4">
        <input type="text" name="addresses[{{$i}}][address_line1]" class="form-control" placeholder="Line 1"
               value="{{ $a['address_line1'] ?? $a->address_line1 ?? '' }}" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="addresses[{{$i}}][city]" class="form-control" placeholder="City"
               value="{{ $a['city'] ?? $a->city ?? '' }}">
      </div>
      <div class="col-md-2">
        <input type="text" name="addresses[{{$i}}][state]" class="form-control" placeholder="State"
               value="{{ $a['state'] ?? $a->state ?? '' }}">
      </div>
      <div class="col-md-2">
        <input type="text" name="addresses[{{$i}}][pincode]" class="form-control" placeholder="Pincode"
               value="{{ $a['pincode'] ?? $a->pincode ?? '' }}">
      </div>
      <div class="col-md-2">
        <select name="addresses[{{$i}}][address_type]" class="form-control">
          <option value="current" {{ ($a['address_type'] ?? $a->address_type ?? '')=='current'?'selected':'' }}>Current</option>
          <option value="permanent" {{ ($a['address_type'] ?? $a->address_type ?? '')=='permanent'?'selected':'' }}>Permanent</option>
        </select>
      </div>
    </div>
  @endforeach
</div>
<button type="button" class="btn btn-sm btn-secondary" onclick="addAddress()">+ Add Address</button>

<hr>

{{-- =======================
  Documents Repeater
======================= --}}
<h5>Documents</h5>
<div id="doc-repeater">
  @php $docs = old('documents', $student->documents ?? []); @endphp
  @foreach($docs as $i => $d)
    <div class="doc-row mb-2 border p-2 row">
      <div class="col-md-4">
        <select name="documents[{{$i}}][doc_type]" class="form-control">
          <option value="">Select</option>
          <option value="aadhaar" {{ ($d['doc_type'] ?? $d->doc_type ?? '')=='aadhaar'?'selected':'' }}>Aadhaar</option>
          <option value="birth_certificate" {{ ($d['doc_type'] ?? $d->doc_type ?? '')=='birth_certificate'?'selected':'' }}>Birth Certificate</option>
          <option value="transfer_certificate" {{ ($d['doc_type'] ?? $d->doc_type ?? '')=='transfer_certificate'?'selected':'' }}>Transfer Certificate</option>
          <option value="caste_certificate" {{ ($d['doc_type'] ?? $d->doc_type ?? '')=='caste_certificate'?'selected':'' }}>Caste Certificate</option>
          <option value="passport_photo" {{ ($d['doc_type'] ?? $d->doc_type ?? '')=='passport_photo'?'selected':'' }}>Passport Photo</option>
          <option value="other" {{ ($d['doc_type'] ?? $d->doc_type ?? '')=='other'?'selected':'' }}>Other</option>
        </select>
      </div>
      <div class="col-md-6">
        <input type="file" name="documents[{{$i}}][file]" class="form-control">
        @if(!empty($d->file_path))
          <small>Existing: <a href="{{ asset('storage/'.$d->file_path) }}" target="_blank">View</a></small>
        @endif
      </div>
    </div>
  @endforeach
</div>
<button type="button" class="btn btn-sm btn-secondary" onclick="addDocument()">+ Add Document</button>

<hr>
<button class="btn btn-success mt-3">{{ isset($student) ? 'Update' : 'Save' }}</button>
<a href="{{ isset($student) ? tenant_route('tenant.students.show',['student' => $student->id]) : tenant_route('tenant.students.index') }}" class="btn btn-secondary mt-3">Cancel</a>

{{-- =======================
  Scripts
======================= --}}
<script>
function previewPhoto(e){
  const output = document.getElementById('photo-preview');
  output.src = URL.createObjectURL(e.target.files[0]);
  output.style.display = 'block';
}

function addGuardian(){
  let idx = document.querySelectorAll('#guardian-repeater .guardian-row').length;
  let html = `
  <div class="guardian-row mb-2 border p-2 row">
    <div class="col-md-3"><input type="text" name="guardians[${idx}][full_name]" class="form-control" placeholder="Full Name" required></div>
    <div class="col-md-2"><input type="text" name="guardians[${idx}][relation]" class="form-control" placeholder="Relation"></div>
    <div class="col-md-2"><input type="text" name="guardians[${idx}][phone]" class="form-control" placeholder="Phone"></div>
    <div class="col-md-3"><input type="email" name="guardians[${idx}][email]" class="form-control" placeholder="Email"></div>
    <div class="col-md-2"><label><input type="checkbox" name="guardians[${idx}][is_primary]" value="1"> Primary</label></div>
  </div>`;
  document.getElementById('guardian-repeater').insertAdjacentHTML('beforeend', html);
}

function addAddress(){
  let idx = document.querySelectorAll('#address-repeater .address-row').length;
  let html = `
  <div class="address-row mb-2 border p-2 row">
    <div class="col-md-4"><input type="text" name="addresses[${idx}][address_line1]" class="form-control" placeholder="Line 1" required></div>
    <div class="col-md-2"><input type="text" name="addresses[${idx}][city]" class="form-control" placeholder="City"></div>
    <div class="col-md-2"><input type="text" name="addresses[${idx}][state]" class="form-control" placeholder="State"></div>
    <div class="col-md-2"><input type="text" name="addresses[${idx}][pincode]" class="form-control" placeholder="Pincode"></div>
    <div class="col-md-2">
      <select name="addresses[${idx}][address_type]" class="form-control">
        <option value="current">Current</option>
        <option value="permanent">Permanent</option>
      </select>
    </div>
  </div>`;
  document.getElementById('address-repeater').insertAdjacentHTML('beforeend', html);
}

function addDocument(){
  let idx = document.querySelectorAll('#doc-repeater .doc-row').length;
  let html = `
  <div class="doc-row mb-2 border p-2 row">
    <div class="col-md-4">
      <select name="documents[${idx}][doc_type]" class="form-control">
        <option value="">Select</option>
        <option value="aadhaar">Aadhaar</option>
        <option value="birth_certificate">Birth Certificate</option>
        <option value="transfer_certificate">Transfer Certificate</option>
        <option value="caste_certificate">Caste Certificate</option>
        <option value="passport_photo">Passport Photo</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="col-md-6"><input type="file" name="documents[${idx}][file]" class="form-control"></div>
  </div>`;
  document.getElementById('doc-repeater').insertAdjacentHTML('beforeend', html);
}
</script>
