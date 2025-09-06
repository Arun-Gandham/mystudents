@extends('superadmin.baselayout')

@section('title', 'School Metrics')
@section('description', 'School Metrics')

@php
  $details = $school->details;
  $status  = $school->is_active ? 'Active' : 'Inactive';
@endphp

@section('content')
<div class="container-fluid">

  {{-- School hero card --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        {{-- School logo --}}
        <div class="col-md-3">
          <div class="ratio ratio-16x9 rounded overflow-hidden bg-light school-hero-img">
            @if($details && $details->logo_url)
              <img src="{{ asset('storage/'.$details->logo_url) }}" 
                   alt="School Logo" class="w-100 h-100 object-fit-cover">
            @else
              <img src="{{ asset('images/no-image.png') }}" 
                   alt="No Logo" class="w-100 h-100 object-fit-cover">
            @endif
          </div>
        </div>

        {{-- School info --}}
        <div class="col-md-9">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
              <h4 class="mb-1 d-flex align-items-center gap-2">
                {{ $school->name }}
                @if($details && $details->favicon_url)
                  <img src="{{ asset('storage/'.$details->favicon_url) }}" 
                       alt="Favicon" width="24" height="24" class="border rounded">
                @endif
              </h4>

              <div class="text-muted small d-flex flex-wrap gap-3">
                <span><i class="bi bi-info-circle me-1"></i>Code: <strong>{{ $details->affiliation_no ?? '-' }}</strong></span>
                <span><i class="bi bi-geo-alt me-1"></i>{{ $details->city ?? '-' }}</span>
                <span><i class="bi bi-person-badge me-1"></i>Principal: {{ $details->principal_name ?? '-' }}</span>
                <span>
                  <i class="bi bi-globe me-1"></i>
                  @if($school->domain)
                    <a href="http://{{ $school->domain }}" target="_blank" rel="noopener" class="text-decoration-none">{{ $school->domain }}</a>
                  @endif
                </span>
              </div>
            </div>
            <div>
              <span class="badge {{ $status === 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                <i class="bi bi-toggle-{{ $status === 'Active' ? 'on' : 'off' }} me-1"></i>{{ $status }}
              </span>
            </div>
          </div>

          {{-- Chips --}}
          <div class="d-flex flex-wrap gap-2 mt-3">
            <div class="chip"><i class="bi bi-people me-1"></i>Students: <strong>{{ number_format($school->students->count()) }}</strong></div>
            <div class="chip"><i class="bi bi-person-workspace me-1"></i>Staff: <strong>{{ number_format($school->users->count()) }}</strong></div>
            <div class="chip"><i class="bi bi-calendar3 me-1"></i>Classes: <strong>{{ number_format($school->grades->count()) }}</strong></div>
            <div class="chip"><i class="bi bi-diagram-3 me-1"></i>Sections: <strong>{{ number_format($school->sections->count()) }}</strong></div>
          </div>

          {{-- Additional info --}}
          <div class="mt-3">
            <ul class="list-unstyled small mb-0">
              <li><i class="bi bi-telephone me-1"></i>Phone: {{ $details->phone ?? '-' }}</li>
              <li><i class="bi bi-telephone-plus me-1"></i>Alt Phone: {{ $details->alt_phone ?? '-' }}</li>
              <li><i class="bi bi-envelope me-1"></i>Email: {{ $details->email ?? '-' }}</li>
              <li><i class="bi bi-browser-chrome me-1"></i>Website: 
                @if($details && $details->website)
                  <a href="{{ $details->website }}" target="_blank" rel="noopener">{{ $details->website }}</a>
                @else
                  -
                @endif
              </li>
              <li><i class="bi bi-geo me-1"></i>Address: {{ $details->address_line1 ?? '' }} {{ $details->address_line2 ?? '' }}, {{ $details->city ?? '' }} {{ $details->state ?? '' }} {{ $details->postal_code ?? '' }}</li>
              <li><i class="bi bi-calendar-check me-1"></i>Established: {{ $details->established_year ?? '-' }}</li>
              <li><i class="bi bi-award me-1"></i>Affiliation No: {{ $details->affiliation_no ?? '-' }}</li>
            </ul>
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
      <a class="nav-link {{ $activeTab==='resetPassword' ? 'active' : '' }}"
         href="{{ route('superadmin.school.resetPassword', $school->id) }}">
        <i class="bi bi-gear me-1"></i>Reset Admin Password
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ $activeTab==='settings' ? 'active' : '' }}"
         href="{{ route('superadmin.school.settings', $school->id) }}">
        <i class="bi bi-gear me-1"></i>Settings
      </a>
    </li>
  </ul>

  {{-- Tab body --}}
  <div class="pt-3">
    @yield('tabcontent')
  </div>
</div>
@endsection

@push('styles')
<style>
  .object-fit-cover { object-fit: cover; }
  .chip {
    background: var(--bs-light,#f8f9fa);
    border: 1px solid rgba(0,0,0,.05);
    border-radius: 999px;
    padding: .35rem .6rem;
    font-size: .875rem;
  }
  .school-hero-img img { filter: saturate(1.05); }
</style>
@endpush
