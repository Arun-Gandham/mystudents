@extends('superadmin.pages.school.base')

@php $activeTab = 'settings'; @endphp

@section('tabcontent')
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white"><strong><i class="bi bi-gear me-1"></i> School Settings</strong></div>
  <div class="card-body">
    <form class="row g-3" method="POST" action="{{ route('superadmin.school.updateSettings', $school->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <x-alert-errors />
      <div class="col-md-6">
        <label class="form-label">School Name *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $school->name) }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Domain *</label>
        <input type="text" name="domain" class="form-control" value="{{ old('domain', $school->domain) }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Status</label>
        <select class="form-select" name="is_active">
          <option value="1" {{ $school->is_active ? 'selected' : '' }}>Active</option>
          <option value="0" {{ !$school->is_active ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Established Year</label>
        <input type="number" name="established_year" class="form-control" value="{{ old('established_year', optional($school->details)->established_year) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', optional($school->details)->phone) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Alt Phone</label>
        <input type="text" name="alt_phone" class="form-control" value="{{ old('alt_phone', optional($school->details)->alt_phone) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', optional($school->details)->email) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Website</label>
        <input type="text" name="website" class="form-control" value="{{ old('website', optional($school->details)->website) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImage(event,'logoPreview')">
        <div class="mt-2">
          @if(optional($school->details)->logo_url)
            <img id="logoPreview" src="{{ asset('storage/'.optional($school->details)->logo_url) }}" width="80" height="80" class="border rounded">
          @else
            <img id="logoPreview" width="80" height="80" class="border rounded d-none">
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label">Favicon</label>
        <input type="file" name="favicon" class="form-control" accept="image/*" onchange="previewImage(event,'faviconPreview')">
        <div class="mt-2">
          @if(optional($school->details)->favicon_url)
            <img id="faviconPreview" src="{{ asset('storage/'.optional($school->details)->favicon_url) }}" width="40" height="40" class="border rounded">
          @else
            <img id="faviconPreview" width="40" height="40" class="border rounded d-none">
          @endif
        </div>
      </div>

      <div class="col-md-12">
        <label class="form-label">Address Line 1</label>
        <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', optional($school->details)->address_line1) }}">
      </div>

      <div class="col-md-12">
        <label class="form-label">Address Line 2</label>
        <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', optional($school->details)->address_line2) }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" value="{{ old('city', optional($school->details)->city) }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">State</label>
        <input type="text" name="state" class="form-control" value="{{ old('state', optional($school->details)->state) }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', optional($school->details)->postal_code) }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Country Code</label>
        <input type="text" name="country_code" class="form-control" value="{{ old('country_code', optional($school->details)->country_code) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Affiliation No</label>
        <input type="text" name="affiliation_no" class="form-control" value="{{ old('affiliation_no', optional($school->details)->affiliation_no) }}">
      </div>

      <div class="col-md-12">
        <label class="form-label">Notes</label>
        <textarea name="note" class="form-control">{{ old('note', optional($school->details)->note) }}</textarea>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('superadmin.school.dashboard', $school->id) }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

@endsection
@push('scripts')
<script>
function previewImage(event, previewId) {
  const file = event.target.files[0];
  const img = document.getElementById(previewId);
  if(file){
      const reader = new FileReader();
      reader.onload = function(e){
          img.src = e.target.result;
          img.classList.remove('d-none');
      }
      reader.readAsDataURL(file);
  }
}
</script>
@endpush
