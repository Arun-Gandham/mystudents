@extends('superadmin.baselayout')

@section('title', 'School Metrics')
@section('description', 'School Metrics')

@php
  // Static demo values (replace with real data later)
  $schoolId   = $schoolId   ?? 1;
  $schoolName = $schoolName ?? 'Green Valley High School';
  $schoolCode = $schoolCode ?? 'GVH-2025';
  $domain     = $domain     ?? 'greenvalley.edu';
  $principal  = $principal  ?? 'Ms. A. Nair';
  $city       = $city       ?? 'Bengaluru';
  $status     = $status     ?? 'Active';
  $students   = $students   ?? 1248;
  $teachers   = $teachers   ?? 82;
  $classes    = $classes    ?? 36;
  $sections   = $sections   ?? 108;
  $activeTab  = $activeTab  ?? 'dashboard'; // 'dashboard' | 'people' | 'settings'
@endphp

@section('content')
<div class="container-fluid">

  {{-- School hero card --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-md-3">
          <div class="ratio ratio-16x9 rounded overflow-hidden bg-light school-hero-img">
            <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?q=80&w=1400&auto=format&fit=crop"
                 alt="School Image" class="w-100 h-100 object-fit-cover">
          </div>
        </div>
        <div class="col-md-9">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
              <h4 class="mb-1">{{ $school->name ?? "-" }}</h4>
              <div class="text-muted small d-flex flex-wrap gap-3">
                <span><i class="bi bi-info-circle me-1"></i>Code: <strong>{{ $schoolCode }}</strong></span>
                <span><i class="bi bi-geo-alt me-1"></i>{{ $city }}</span>
                <span><i class="bi bi-person-badge me-1"></i>Principal: {{ $principal }}</span>
                <span>
                  <i class="bi bi-globe me-1"></i>
                  <a href="{{ $school->domain }}" class="text-decoration-none" target="_blank" rel="noopener">{{ $school->domain }}</a>
                </span>
              </div>
            </div>
            <div>
              <span class="badge {{ $status === 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                <i class="bi bi-toggle-{{ $status === 'Active' ? 'on' : 'off' }} me-1"></i>{{ $status }}
              </span>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 mt-3">
            <div class="chip"><i class="bi bi-people me-1"></i>Students: <strong>{{ number_format($school->students->count()) }}</strong></div>
            <div class="chip"><i class="bi bi-person-workspace me-1"></i>Staff: <strong>{{ $school->users->count() }}</strong></div>
            <div class="chip"><i class="bi bi-calendar3 me-1"></i>Classes: <strong>{{ $school->grades->count() }}</strong></div>
            <div class="chip"><i class="bi bi-diagram-3 me-1"></i>Sections: <strong>{{ $school->sections->count() }}</strong></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabs --}}
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ $activeTab==='dashboard' ? 'active' : '' }}"
         href="{{ route('superadmin.school.dashboard', $school->id) }}">
        <i class="bi bi-speedometer2 me-1"></i>Dashboard
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ $activeTab==='people' ? 'active' : '' }}"
         href="{{ route('superadmin.school.students', $school->id) }}">
        <i class="bi bi-people me-1"></i>Students & Staff
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ $activeTab==='settings' ? 'active' : '' }}"
         href="{{ route('superadmin.school.settings', $school->id) }}">
        <i class="bi bi-gear me-1"></i>Settings
      </a>
    </li>
  </ul>

  {{-- Tab body (each tab view fills this) --}}
  <div class="pt-3">
    @yield('tabcontent')
  </div>
</div>
@endsection

@push('styles')
<style>
  .object-fit-cover{ object-fit: cover; }
  .chip{
    background: var(--bs-light,#f8f9fa);
    border:1px solid rgba(0,0,0,.05);
    border-radius:999px; padding:.35rem .6rem; font-size:.875rem;
  }
  .school-hero-img img{ filter: saturate(1.05); }
</style>
@endpush
