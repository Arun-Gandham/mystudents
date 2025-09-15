<!-- resources/views/tenant/pages/student_applications/_form.blade.php -->
@csrf
<div class="row">
  <div class="col-md-4">
    <label>First Name</label>
    <input type="text" name="first_name" class="form-control" value="{{ old('first_name',$application->first_name ?? '') }}" required>
  </div>
  <div class="col-md-4">
    <label>Middle Name</label>
    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name',$application->middle_name ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Last Name</label>
    <input type="text" name="last_name" class="form-control" value="{{ old('last_name',$application->last_name ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-3">
    <label>DOB</label>
    <input type="date" name="dob" class="form-control" value="{{ old('dob',$application->dob ?? '') }}">
  </div>
  <div class="col-md-3">
    <label>Gender</label>
    <select name="gender" class="form-control">
      <option value="">Select</option>
      <option value="male" {{ old('gender',$application->gender ?? '')=='male'?'selected':'' }}>Male</option>
      <option value="female" {{ old('gender',$application->gender ?? '')=='female'?'selected':'' }}>Female</option>
    </select>
  </div>
  <div class="col-md-6">
    <label>Previous School</label>
    <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school',$application->previous_school ?? '') }}">
  </div>
</div>

<h5 class="mt-3">Guardian Details</h5>
<div class="row">
  <div class="col-md-4">
    <label>Guardian Name</label>
    <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name',$application->guardian_name ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Relation</label>
    <input type="text" name="guardian_relation" class="form-control" value="{{ old('guardian_relation',$application->guardian_relation ?? '') }}">
  </div>
  <div class="col-md-4">
    <label>Email</label>
    <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email',$application->guardian_email ?? '') }}">
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-6">
    <label>Phone</label>
    <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone',$application->guardian_phone ?? '') }}">
  </div>
  <div class="col-md-6">
    <label>Address</label>
    <textarea name="address" class="form-control">{{ old('address',$application->address ?? '') }}</textarea>
  </div>
</div>

<button class="btn btn-success mt-3">Save</button>
<a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-secondary mt-3">Cancel</a>
