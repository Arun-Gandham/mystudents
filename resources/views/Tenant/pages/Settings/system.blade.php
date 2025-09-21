@extends('Tenant.layouts.layout1')
@section('title','System Settings')

@section('content')
<div class="container-fluid">
  <h2 class="mb-3">System Settings</h2>
  <form method="POST" action="{{ tenant_route('tenant.settings.system.update') }}" enctype="multipart/form-data" class="card p-3 p-md-4 shadow-sm">
    @csrf
    @method('PUT')
    <x-alert-errors />

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">School Profile</h5>
            <div class="mb-3">
              <label class="form-label">School Name</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $settingsSchool->name) }}" required>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', optional($settingsSchool->details)->phone) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Alternate Phone</label>
                <input type="text" name="alt_phone" class="form-control" value="{{ old('alt_phone', optional($settingsSchool->details)->alt_phone) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', optional($settingsSchool->details)->email) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Website</label>
                <input type="text" name="website" class="form-control" value="{{ old('website', optional($settingsSchool->details)->website) }}">
              </div>
            </div>

            <div class="row g-3 mt-1">
              <div class="col-md-12">
                <label class="form-label">Address Line 1</label>
                <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', optional($settingsSchool->details)->address_line1) }}">
              </div>
              <div class="col-md-12">
                <label class="form-label">Address Line 2</label>
                <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', optional($settingsSchool->details)->address_line2) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', optional($settingsSchool->details)->city) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control" value="{{ old('state', optional($settingsSchool->details)->state) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Postal Code</label>
                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', optional($settingsSchool->details)->postal_code) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Country Code</label>
                <input type="text" name="country_code" class="form-control" value="{{ old('country_code', optional($settingsSchool->details)->country_code) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Established Year</label>
                <input type="number" name="established_year" class="form-control" value="{{ old('established_year', optional($settingsSchool->details)->established_year) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Affiliation No</label>
                <input type="text" name="affiliation_no" class="form-control" value="{{ old('affiliation_no', optional($settingsSchool->details)->affiliation_no) }}">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Branding</h5>
            <div class="mb-3">
              <label class="form-label">Logo</label>
              <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImg(event,'logoPreview')">
              <div class="mt-2">
                <img id="logoPreview" src="{{ optional($settingsSchool->details)->logo_url ? asset('storage/'.optional($settingsSchool->details)->logo_url) : asset('images/default-logo.png') }}" height="58" class="border rounded">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Favicon</label>
              <input type="file" name="favicon" class="form-control" accept="image/*" onchange="previewImg(event,'faviconPreview')">
              <div class="mt-2">
                <img id="faviconPreview" src="{{ optional($settingsSchool->details)->favicon_url ? asset('storage/'.optional($settingsSchool->details)->favicon_url) : '' }}" height="32" class="border rounded">
              </div>
            </div>

            <h6 class="mt-4">Application</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Theme</label>
                <select name="theme" class="form-select">
                  @php $theme = old('theme', optional($settingsSchool->details)->theme); @endphp
                  <option value="">System</option>
                  <option value="light" {{ $theme==='light'?'selected':'' }}>Light</option>
                  <option value="dark" {{ $theme==='dark'?'selected':'' }}>Dark</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Primary Color</label>
                <input type="text" name="primary_color" class="form-control" placeholder="#4f46e5" value="{{ old('primary_color', optional($settingsSchool->details)->primary_color) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Secondary Color</label>
                <input type="text" name="secondary_color" class="form-control" placeholder="#0ea5e9" value="{{ old('secondary_color', optional($settingsSchool->details)->secondary_color) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Timezone</label>
                <input type="text" name="timezone" class="form-control" placeholder="Asia/Kolkata" value="{{ old('timezone', optional($settingsSchool->details)->timezone) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Locale</label>
                <input type="text" name="locale" class="form-control" placeholder="en" value="{{ old('locale', optional($settingsSchool->details)->locale) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Date Format</label>
                <input type="text" name="date_format" class="form-control" placeholder="d M Y" value="{{ old('date_format', optional($settingsSchool->details)->date_format) }}">
              </div>
            </div>

            <h6 class="mt-4">Enabled Modules</h6>
            <div class="row g-2">
              @php
                $allModules = config('modules.list');
                $enabled = old('enabled_modules', optional($settingsSchool->details)->enabled_modules ?? []);
                if (!is_array($enabled)) { $enabled = []; }
              @endphp
              @foreach($allModules as $key => $label)
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="enabled_modules[]" id="mod_{{ $key }}" value="{{ $key }}" {{ in_array($key, $enabled, true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="mod_{{ $key }}">{{ $label }}</label>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="mt-3">
              <label class="form-label">Notes</label>
              <textarea name="note" class="form-control" rows="3">{{ old('note', optional($settingsSchool->details)->note) }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-primary">Save Settings</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function previewImg(e, id){
  const file = e.target.files[0];
  if(!file) return;
  const img = document.getElementById(id);
  img.src = URL.createObjectURL(file);
}
</script>
@endpush
