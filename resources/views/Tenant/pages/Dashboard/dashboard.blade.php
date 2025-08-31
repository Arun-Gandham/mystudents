@extends('tenant.baselayout')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol>
    </nav>
    <div class="d-flex gap-2">
      <select class="form-select form-select-sm" style="width: 220px;">
        <option selected>All Regions</option>
        <option>APAC</option><option>EMEA</option><option>AMER</option>
      </select>
      <button class="btn btn-primary btn-sm"><i class="bi bi-download me-1"></i>Export</button>
    </div>
  </div>

  {{-- Sample content only --}}
  <div class="row g-3">
    <div class="col-md-6">
      <div class="card card-soft p-3">
        <h5 class="mb-2">Welcome, Tenant 👋</h5>
        <p class="mb-0 text-muted">This is placeholder content. Plug your KPIs, charts, and tables here later.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-soft p-3">
        <h6 class="mb-2">Quick Links</h6>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-outline-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Add School</a>
          <a class="btn btn-outline-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Add User</a>
          <a class="btn btn-outline-primary btn-sm"><i class="bi bi-shield-lock me-1"></i>New Role</a>
        </div>
      </div>
    </div>
  </div>
    @endsection