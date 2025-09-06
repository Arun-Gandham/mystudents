@extends('tenant.baselayout')
@section('title', 'Student Profile')

@section('content')
<div class="container-fluid py-4">

  {{-- Profile Header --}}
  <div class="card shadow-sm p-3 mb-4">
    <div class="d-flex align-items-center gap-3">
      <img src="{{ $student->photo ? asset('storage/'.$student->photo) : asset('images/default-avatar.png') }}"
           class="rounded-circle" width="90" height="90" alt="Profile">
      <div>
        <h4 class="mb-1">{{ $student->full_name }}</h4>
        <p class="mb-1 text-muted">
          Roll No: <strong>{{ $student->enrollments->first()?->roll_no ?? 'N/A' }}</strong>
        </p>
        <p class="mb-0">
          {{ $student->enrollments->first()?->grade?->name ?? '' }}
          {{ $student->enrollments->first()?->section?->name ? ' - '.$student->enrollments->first()->section->name : '' }}
        </p>
      </div>
      <div class="ms-auto">
        <a href="{{ tenant_route('tenant.students.edit',['student'=>$student->id]) }}" class="btn btn-warning">
          <i class="bi bi-pencil-square"></i> Edit
        </a>
      </div>
    </div>
  </div>

  {{-- Tabs --}}
  <ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#info">Student Info</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#guardian">Guardian</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#admission">Admission</a></li>
  </ul>

  <div class="tab-content">
    {{-- Student Info --}}
    <div class="tab-pane fade show active" id="info">
      <div class="card p-3">
        <h6>Basic Info</h6>
        <p>DOB: {{ $student->dob }}</p>
        <p>Gender: {{ $student->gender }}</p>
        <p>Status: <span class="badge bg-success">{{ ucfirst($student->status) }}</span></p>
      </div>
    </div>

    {{-- Guardian Info --}}
    <div class="tab-pane fade" id="guardian">
      <div class="card p-3">
        <h6>Guardian Info</h6>
        <p>Name: {{ $student->sourceApplication?->guardian_full_name ?? 'N/A' }}</p>
        <p>Relation: {{ $student->sourceApplication?->guardian_relation }}</p>
        <p>Email: {{ $student->sourceApplication?->guardian_email }}</p>
        <p>Phone: {{ $student->sourceApplication?->guardian_phone }}</p>
        <p>Address: {{ $student->sourceApplication?->address }}</p>
      </div>
    </div>

    {{-- Admission Info --}}
    <div class="tab-pane fade" id="admission">
      <div class="card p-3">
        <h6>Admission</h6>
        <p>Application #: {{ $student->admissions->first()?->application_no ?? 'N/A' }}</p>
        <p>Admitted On: {{ $student->admissions->first()?->admitted_on }}</p>
        <p>Previous School: {{ $student->admissions->first()?->previous_school }}</p>
        <p>Remarks: {{ $student->admissions->first()?->remarks }}</p>
      </div>
    </div>
  </div>
</div>
@endsection
