@extends('superadmin.pages.school.base')

@php $activeTab = 'settings'; @endphp

@section('tabcontent')
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white"><strong><i class="bi bi-gear me-1"></i>School Settings</strong></div>
  <div class="card-body">
    <form class="row g-3">
      <div class="col-md-6">
        <label class="form-label">School Name</label>
        <input type="text" class="form-control" value="Green Valley High School">
      </div>
      <div class="col-md-3">
        <label class="form-label">School Code</label>
        <input type="text" class="form-control" value="GVH-2025">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select class="form-select">
          <option selected>Active</option>
          <option>Inactive</option>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Domain</label>
        <input type="text" class="form-control" value="greenvalley.edu">
      </div>
      <div class="col-md-6">
        <label class="form-label">Principal</label>
        <input type="text" class="form-control" value="Ms. A. Nair">
      </div>

      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" class="form-control" value="+91 80 1234 5678">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="info@greenvalley.edu">
      </div>

      <div class="col-md-8">
        <label class="form-label">Address</label>
        <input type="text" class="form-control" value="12 Lake Road, Indiranagar, Bengaluru">
      </div>
      <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" class="form-control" value="Bengaluru">
      </div>

      <div class="col-12 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-light">Cancel</button>
        <button type="button" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
@endsection
