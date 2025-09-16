@extends('Tenant.layouts.layout1')

@section('title', 'View Student')
@section('content')
<div class="container-fluid">
  <!-- Profile Header (neutral UI) -->
  <div class="profile-hero card mb-4 rounded-3 overflow-hidden shadow-sm">
    <div class="p-4 p-md-5 d-flex align-items-center gap-3">
      @php
        $photoUrl = !empty($student->photo)
          ? asset('storage/'.$student->photo)
          : asset('storage/images/default-avatar.svg');
        $enrollment = isset($currentEnrollment) ? $currentEnrollment : $student->enrollments()->where('academic_id', current_academic_id())->with(['grade','section'])->first();
        $ageYears = $student->dob ? $student->dob->age : null;
        $aadhaarMasked = $student->aadhaar_no ? str_repeat('x', max(0, strlen($student->aadhaar_no) - 4)) . substr($student->aadhaar_no, -4) : null;
      @endphp

      <img class="rounded-circle border shadow-sm bg-white" src="{{ $photoUrl }}" width="96" height="96" alt="Student Photo">
      <div class="flex-grow-1">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
          <h2 class="mb-0 me-2">
            {{ trim($student->first_name.' '.($student->middle_name ?? '').' '.($student->last_name ?? '')) }}
          </h2>
          @if($enrollment && $enrollment->grade)
            <span class="badge bg-light text-dark">{{ $enrollment->grade->name }} @if($enrollment->section) &middot; {{ $enrollment->section->name }} @endif</span>
          @endif
        </div>
        <div class="text-muted small">Admission No: {{ $student->admission_no ?? '-' }}</div>

        <div class="detail-chips mt-2">
          @if($student->gender)
            <span class="chip"><i class="bi bi-person"></i>{{ $student->gender }}</span>
          @endif
          @if($student->dob)
            <span class="chip"><i class="bi bi-cake"></i>{{ $student->dob->format('d M Y') }}@if($ageYears) &middot; {{ $ageYears }} yrs @endif</span>
          @endif
          @if($student->phone)
            <span class="chip"><i class="bi bi-telephone"></i>{{ $student->phone }}</span>
          @endif
          @if($student->email)
            <span class="chip"><i class="bi bi-envelope"></i>{{ $student->email }}</span>
          @endif
          @if($student->blood_group)
            <span class="chip"><i class="bi bi-droplet"></i>{{ strtoupper($student->blood_group) }}</span>
          @endif
          @if($student->category)
            <span class="chip"><i class="bi bi-tag"></i>{{ $student->category }}</span>
          @endif
          @if($aadhaarMasked)
            <span class="chip"><i class="bi bi-card-text"></i>{{ $aadhaarMasked }}</span>
          @endif
          @if($enrollment && $enrollment->joined_on)
            <span class="chip"><i class="bi bi-clipboard-check"></i>Joined {{ $enrollment->joined_on->format('d M Y') }}</span>
          @endif
        </div>
      </div>

      <div class="ms-auto d-none d-lg-block text-end">
        @if(!empty($primaryGuardian))
          <div class="small text-muted mb-2">Primary Guardian</div>
          <div class="fw-semibold">{{ $primaryGuardian->full_name }}</div>
          <div class="small text-muted">{{ $primaryGuardian->relation ?? '' }}</div>
          <div class="small text-muted">{{ $primaryGuardian->phone_e164 ?? '' }}</div>
        @endif
        <a href="{{ tenant_route('tenant.students.edit',['student' => $student->id]) }}" class="btn btn-outline-primary mt-2">Edit Profile</a>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white px-3 px-md-4 py-2 border-top">
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <div class="stat-pill"><i class="bi bi-people me-1"></i><span class="label">Guardians</span><span class="value">{{ $student->guardians_count ?? $student->guardians->count() }}</span></div>
        <div class="stat-pill"><i class="bi bi-folder2-open me-1"></i><span class="label">Documents</span><span class="value">{{ $student->documents_count ?? $student->documents()->count() }}</span></div>
        <div class="stat-pill"><i class="bi bi-calendar-check me-1"></i><span class="label">Attendance</span><span class="value">{{ $student->attendance_entries_count ?? $student->attendanceEntries()->count() }}</span></div>
        @if(!empty($currentAddress))
          <span class="ms-auto small text-muted">Address: {{ trim(($currentAddress->address_line1 ?? '').' '.($currentAddress->city ?? '')) }}</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Tabs -->
  <div class="sticky-subnav">
    <ul class="nav nav-pills mb-3" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabOverview"><i class="bi bi-grid me-1"></i>Overview</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabAttendance"><i class="bi bi-calendar-check me-1"></i>Attendance</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabPerformance"><i class="bi bi-bar-chart-line me-1"></i>Performance</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabBehavior"><i class="bi bi-journal-text me-1"></i>Behavior</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabDocuments"><i class="bi bi-folder2-open me-1"></i>Documents <span class="badge bg-secondary ms-1">{{ $student->documents_count ?? $student->documents()->count() }}</span></button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabTimeTable"><i class="bi bi-clock-history me-1"></i>Timetable</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabGuardians"><i class="bi bi-people me-1"></i>Guardians <span class="badge bg-secondary ms-1">{{ $student->guardians_count ?? $student->guardians->count() }}</span></button></li>
    </ul>
  </div>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="tabOverview"></div>
    <div class="tab-pane fade" id="tabAttendance"></div>
    <div class="tab-pane fade" id="tabPerformance"></div>
    <div class="tab-pane fade" id="tabBehavior"></div>
    <div class="tab-pane fade" id="tabDocuments"></div>
    <div class="tab-pane fade" id="tabTimeTable"></div>
    <div class="tab-pane fade" id="tabGuardians"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Laravel route URLs
const routes = {
  overview:    @json(tenant_route('tenant.students.overview', ['id' => $student->id])),
  attendance:  @json(tenant_route('tenant.students.attendance', ['id' => $student->id])),
  performance: @json(tenant_route('tenant.students.performance', ['id' => $student->id])),
  behavior:    @json(tenant_route('tenant.students.behavior', ['id' => $student->id])),
  documents:   @json(tenant_route('tenant.students.documents', ['id' => $student->id])),
  timetable:   @json(tenant_route('tenant.students.timetable', ['id' => $student->id])),
  guardians:   @json(tenant_route('tenant.students.guardians', ['id' => $student->id])),
};

// When tab is shown, fetch content
document.querySelectorAll('[data-bs-toggle="pill"]').forEach(btn=>{
  btn.addEventListener('shown.bs.tab', e=>{
    const target = e.target.dataset.bsTarget; // e.g. #tabOverview
    let endpoint = target.replace('#tab','').toLowerCase(); // overview, attendance, ...
    const container = document.querySelector(target);

    if (!container.dataset.loaded) {
      container.innerHTML = '<div class="p-4 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

      fetch(routes[endpoint])
        .then(res=>res.text())
        .then(html=>{
          container.innerHTML = html;
          container.dataset.loaded = true;
        })
        .catch(()=>{
          container.innerHTML = '<div class="p-3 text-danger">Failed to load</div>';
        });
    }
  });
});

// Load Overview immediately
document.querySelector('[data-bs-target="#tabOverview"]').dispatchEvent(new Event('shown.bs.tab'));
</script>
@endpush

@push('styles')
<style>
  .profile-hero{ background: var(--bs-card-bg); border: 1px solid var(--bs-border-color); }
  .detail-chips .chip{
    display:inline-flex; align-items:center; gap:.35rem; background:#f1f5f9; color:#0f172a;
    border:1px solid #e2e8f0; border-radius:999px; padding:.25rem .6rem; margin:.15rem .25rem .15rem 0;
    font-size:.8rem;
  }
  .detail-chips .chip .bi{opacity:.8}
  .stat-pill{
    display:inline-flex; align-items:center; gap:.5rem;
    background:#f8fafc; border:1px solid #e2e8f0; border-radius:999px;
    padding:.35rem .75rem; font-size:.875rem;
  }
  .stat-pill .label{ color:#64748b; }
  .stat-pill .value{ font-weight:600; color:#0f172a; }
  .sticky-subnav{ position:sticky; top:0; z-index:5; background:var(--bs-body-bg); padding-top:.25rem; }
  @media (max-width: 576px){ .sticky-subnav{ position:static; } }
</style>
@endpush

