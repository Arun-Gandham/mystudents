@extends('tenant.baselayout')
@section('title','Application Details')

@section('content')
<div class="container-fluid py-4">
  <h4>Application #{{ $application->application_no }}</h4>
  <div class="card p-3 mb-3">
    <h6>Child</h6>
    <p>{{ $application->child_full_name }} ({{ $application->child_gender }})</p>
    <p>DOB: {{ $application->child_dob }}</p>
    <p>Prev School: {{ $application->previous_school }}</p>

    <h6>Guardian</h6>
    <p>{{ $application->guardian_full_name }} ({{ $application->guardian_relation }})</p>
    <p>Email: {{ $application->guardian_email }}</p>
    <p>Phone: {{ $application->guardian_phone }}</p>
    <p>Address: {{ $application->address }}</p>

    <h6>Preferences</h6>
    <p>Grade: {{ $application->preferredGrade?->name ?? '-' }}</p>
    <p>Section: {{ $application->preferredSection?->name ?? '-' }}</p>

    <h6>Status</h6>
    <p><span class="badge bg-info">{{ ucfirst($application->status) }}</span></p>
  </div>

  <div class="d-flex gap-2">
    <!-- Edit -->
    <a href="{{ tenant_route('tenant.applications.edit', ['application' => $application->id]) }}" 
       class="btn btn-warning">
      <i class="bi bi-pencil-square"></i> Edit
    </a>

    <!-- Delete -->
    <form action="{{ tenant_route('tenant.applications.destroy', ['application' => $application->id]) }}" 
          method="POST" class="d-inline">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger"
              onclick="return confirm('Are you sure you want to delete this application?')">
        <i class="bi bi-trash"></i> Delete
      </button>
    </form>

    <!-- Back -->
    <a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Back to List
    </a>
  </div>
</div>
@endsection
