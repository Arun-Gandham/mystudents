@extends('tenant.layouts.layout1')
@section('title','Application Details')
@section('content')
<div class="container-fluid">
  <h2>Application #{{ $application->application_no }}</h2>
  <p><strong>Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</p>
  <p><strong>DOB:</strong> {{ $application->dob }}</p>
  <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>
  <p><strong>Guardian:</strong> {{ $application->guardian_name }} ({{ $application->guardian_relation }})</p>
  <p><strong>Phone:</strong> {{ $application->guardian_phone }}</p>
  <p><strong>Address:</strong> {{ $application->address }}</p>

  <h4>Logs</h4>
  <ul>
    @foreach($application->logs as $log)
      <li>{{ $log->created_at }} - {{ $log->action }} ({{ $log->comment }})</li>
    @endforeach
  </ul>
  <div class="d-flex justify-content-between mt-3">
    <a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-secondary">
        Back to Applications
    </a>

    @if($application->status !== 'accepted' && !$application->student_id)
        <a href="{{ tenant_route('tenant.applications.admit.form',['application' => $application->id]) }}" 
           class="btn btn-success">
           Admit Student
        </a>
    @endif
</div>

  <a href="{{ tenant_route('tenant.applications.edit',['application' => $application->id]) }}" class="btn btn-warning">Edit</a>
  <a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
