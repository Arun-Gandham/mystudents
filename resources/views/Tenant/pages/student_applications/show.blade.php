@extends('tenant.baselayout')
@section('title','Application Details')

@section('content')
<div class="container-fluid py-4">

  <!-- Header Section -->
  <div class="card shadow-sm p-3 mb-4">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h4>Application #{{ $application->application_no }}</h4>
        <p class="mb-1">
          <strong>Status:</strong>
          <span class="badge bg-info">{{ ucfirst($application->status) }}</span>
        </p>
      </div>
    </div>

    <!-- Action Buttons (right bottom) -->
    <div class="d-flex justify-content-end gap-2 mt-3">
      <a href="{{ tenant_route('tenant.applications.edit', ['application' => $application->id]) }}" class="btn btn-warning">
        <i class="bi bi-pencil-square"></i> Edit
      </a>
      <form action="{{ tenant_route('tenant.applications.destroy', ['application' => $application->id]) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this application?')">
          <i class="bi bi-trash"></i> Delete
        </button>
      </form>
      <a href="{{ tenant_route('tenant.admissions.fromApp.create',['application'=>$application->id]) }}" 
            class="btn btn-sm btn-success">
            Admit
          </a>
    </div>
  </div>

  <!-- Details Section -->
  <div class="row">
    <!-- Left Side: Child + Guardian -->
    <div class="col-md-6">
      <div class="card p-3 mb-3 shadow-sm">
        <h5>Child Details</h5>
        <p><strong>Name:</strong> {{ $application->child_full_name }}</p>
        <p><strong>Gender:</strong> {{ $application->child_gender }}</p>
        <p><strong>DOB:</strong> {{ $application->child_dob }}</p>
        <p><strong>Previous School:</strong> {{ $application->previous_school }}</p>
      </div>

      <div class="card p-3 mb-3 shadow-sm">
        <h5>Guardian Details</h5>
        <p><strong>Name:</strong> {{ $application->guardian_full_name }} ({{ $application->guardian_relation }})</p>
        <p><strong>Email:</strong> {{ $application->guardian_email }}</p>
        <p><strong>Phone:</strong> {{ $application->guardian_phone }}</p>
        <p><strong>Address:</strong> {{ $application->address }}</p>
      </div>
    </div>

    <!-- Right Side: Preferences -->
    <div class="col-md-6">
      <div class="card p-3 mb-3 shadow-sm">
        <h5>Preferences</h5>
        <p><strong>Grade:</strong> {{ $application->preferredGrade?->name ?? '-' }}</p>
        <p><strong>Section:</strong> {{ $application->preferredSection?->name ?? '-' }}</p>
      </div>
    </div>
  </div>

  <!-- Timeline Logs -->
   <div class="row">
    <div class="col-md-6">
  <!-- Add Log Form -->
  <div class="card p-3 shadow-sm mt-4">
    <h5 class="mb-3">Add Log Entry</h5>
    <form action="{{ tenant_route('tenant.applications.addLog', ['application' => $application->id]) }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label">Action</label>
        <select name="action" class="form-select" required>
          <option value="">-- Select Status --</option>
          <option value="lead">Lead</option>
          <option value="submitted">Submitted</option>
          <option value="reviewing">Reviewing</option>
          <option value="offered">Offered</option>
          <option value="accepted">Accepted</option>
          <option value="rejected">Rejected</option>
          <option value="no_response">No Response</option>
          <option value="withdrawn">Withdrawn</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Comment</label>
        <textarea name="comment" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Log</button>
    </form>
  </div>
</div>
    <div class="col-md-6">
  <div class="card p-3 shadow-sm mt-4">
    <h5 class="mb-3">Application Timeline</h5>

    @forelse($application->logs as $log)
      <div class="mb-2 border-bottom pb-2">
        <strong>{{ ucfirst($log->action) }}</strong>
        <small class="text-muted">
          by {{ $log->user?->full_name ?? 'System' }} on {{ $log->created_at->format('d M Y H:i') }}
        </small>
        <p class="mb-0">{{ $log->comment }}</p>
      </div>
    @empty
      <p class="text-muted">No logs yet.</p>
    @endforelse
  </div>
</div>

</div>
</div>
@endsection
