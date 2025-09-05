@extends('superadmin.baselayout')

@section('title', 'Create School')
@section('content')
<div class="container-fluid py-4">
  <form method="POST" action="{{ route('superadmin.school.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- ================= School Details ================= -->
    <h4 class="mb-3">🏫 School Details</h4>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label">School Name *</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Domain *</label>
        <input type="text" name="domain" class="form-control" required value="{{ old('domain') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Alt Phone</label>
        <input type="text" name="alt_phone" class="form-control" value="{{ old('alt_phone') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">landline</label>
        <input type="text" name="landline" class="form-control" value="{{ old('landline') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImage(event,'logoPreview')">
        <div class="mt-2">
          <img id="logoPreview" width="80" height="80" class="border rounded d-none">
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label">Favicon</label>
        <input type="file" name="favicon" class="form-control" accept="image/*" onchange="previewImage(event,'faviconPreview')">
        <div class="mt-2">
          <img id="faviconPreview" width="40" height="40" class="border rounded d-none">
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Website</label>
        <input type="text" name="website" class="form-control" value="{{ old('website') }}">
      </div>
      <div class="col-md-12">
        <label class="form-label">Address Line 1</label>
        <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1') }}">
      </div>
      <div class="col-md-12">
        <label class="form-label">Address Line 2</label>
        <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" value="{{ old('city') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">State</label>
        <input type="text" name="state" class="form-control" value="{{ old('state') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Country Code</label>
        <input type="text" name="country_code" class="form-control" value="{{ old('country_code') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Established Year</label>
        <input type="number" name="established_year" class="form-control" value="{{ old('established_year') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Affiliation No</label>
        <input type="text" name="affiliation_no" class="form-control" value="{{ old('affiliation_no') }}">
      </div>
      <div class="col-md-12">
        <label class="form-label">Notes</label>
        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Active</label><br>
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" checked> School is active
      </div>
    </div>

    <!-- ================= Admin Details ================= -->
    <h4 class="mb-3">👨‍💼 Admin Details</h4>
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <label class="form-label">First Name *</label>
    <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Last Name</label>
    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Surname</label>
    <input type="text" name="surname" class="form-control" value="{{ old('surname') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Admin Email *</label>
    <input type="email" name="admin_email" class="form-control" required value="{{ old('admin_email') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Password *</label>
    <div class="input-group">
      <input type="password" name="password" id="password" class="form-control" required>
      <button class="btn btn-outline-secondary" type="button" id="togglePwd"><i class="bi bi-eye"></i></button>
      <button class="btn btn-outline-primary" type="button" id="genPwd">Generate</button>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">Phone</label>
    <input type="text" name="admin_phone" class="form-control" value="{{ old('admin_phone') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Alt Phone</label>
    <input type="text" name="alt_admin_phone" class="form-control" value="{{ old('alt_admin_phone') }}">
  </div>

  <div class="col-md-12">
    <label class="form-label">Address</label>
    <textarea name="admin_address" class="form-control">{{ old('admin_address') }}</textarea>
  </div>

  <div class="col-md-4">
    <label class="form-label">Experience Years</label>
    <input type="number" name="admin_experience" class="form-control" value="{{ old('admin_experience') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Joining Date</label>
    <input type="date" name="admin_joining" class="form-control" value="{{ old('admin_joining') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Designation</label>
    <input type="text" name="admin_designation" class="form-control" value="{{ old('admin_designation','Administrator') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Admin Profile Photo</label>
    <input type="file" name="admin_photo" class="form-control" accept="image/*" onchange="previewImage(event,'adminPhotoPreview')">
    <div class="mt-2">
      <img id="adminPhotoPreview" src="{{ asset('images/no-image.png') }}" width="80" height="80" class="border rounded d-none">
    </div>
  </div>
</div>

    <div class="mt-4 text-end">
      <a href="{{ route('superadmin.school.index') }}" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary">Create School</button>
    </div>
  </form>
</div>
@push('scripts')
<script>
function previewImage(event, previewId) {
    const file = event.target.files[0];
    const img = document.getElementById(previewId);
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            img.src = e.target.result;
            img.classList.remove('d-none'); // show only if uploaded
        }
        reader.readAsDataURL(file);
    } else {
        img.classList.add('d-none'); // hide if no file
    }
}

document.getElementById('genPwd').addEventListener('click', function(){
    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*";
    let pwd = "";
    for(let i=0; i<12; i++){ pwd += chars.charAt(Math.floor(Math.random() * chars.length)); }
    document.getElementById('password').value = pwd;
});

document.getElementById('togglePwd').addEventListener('click', function(){
    const input = document.getElementById('password');
    const icon = this.querySelector('i');
    if(input.type === "password"){
        input.type = "text";
        icon.className = "bi bi-eye-slash";
    } else {
        input.type = "password";
        icon.className = "bi bi-eye";
    }
});
</script>
@endpush

@endsection
