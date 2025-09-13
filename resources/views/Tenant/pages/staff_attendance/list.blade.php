@extends('tenant.layouts.layout1')
@section('title','Staff Attendance List')

@section('content')
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Staff Attendance Records</h4>
    <a href="{{ tenant_route('tenant.staffAttendance.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add Attendance
    </a>
  </div>

  {{-- Filters --}}
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="date" name="date" value="{{ $date }}" class="form-control">
    </div>
    <div class="col-md-3">
      <select name="session" class="form-select">
        <option value="">All Sessions</option>
        <option value="morning" {{ $session=='morning'?'selected':'' }}>Morning</option>
        <option value="afternoon" {{ $session=='afternoon'?'selected':'' }}>Afternoon</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">All Status</option>
        @foreach(['present','absent','late','half_day','excused'] as $st)
          <option value="{{ $st }}" {{ $status==$st?'selected':'' }}>
            {{ ucfirst($st) }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="staff_id" class="form-select">
        <option value="">All Staff</option>
        @foreach($staffList as $staff)
          <option value="{{ $staff->user_id }}" {{ request('staff_id') == $staff->user_id ? 'selected' : '' }}>
            {{ $staff->first_name }} {{ $staff->last_name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button class="btn btn-primary">Filter</button>
      <a href="{{ tenant_route('tenant.staffAttendance.list') }}" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  {{-- Attendance Table --}}
<table class="table table-bordered align-middle">
  <thead>
    <tr>
      <th>Date</th>
      <th>Session</th>
      <th>Staff</th>
      <th>Status</th>
      <th>Check In</th>
      <th>Check Out</th>
      <th>Remarks</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @forelse($records as $rec)
      <tr>
        <td>{{ \Carbon\Carbon::parse($rec->attendance_date)->format('d M Y') }}</td>
        <td>{{ ucfirst($rec->session) }}</td>
        <td>
          <img src="{{ $rec->user->staff->photo 
                        ? asset('storage/'.$rec->user->staff->photo) 
                        : asset('images/default-avatar.png') }}"
               class="rounded-circle me-2" width="35" height="35">
          {{ $rec->user->staff->first_name }} {{ $rec->user->staff->last_name }}
        </td>
        <td><span class="badge bg-info">{{ ucfirst($rec->status) }}</span></td>
        <td>{{ $rec->check_in ?? '-' }}</td>
        <td>{{ $rec->check_out ?? '-' }}</td>
        <td>{{ $rec->remarks ?? '-' }}</td>
        <td>
          <a href="{{ tenant_route('tenant.staffAttendance.edit',['attendance'=>$rec->id]) }}" 
             class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit
          </a>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="8" class="text-center text-muted">No attendance records found</td>
      </tr>
    @endforelse
  </tbody>
</table>


  <div class="d-flex justify-content-end">
    {{ $records->links() }}
  </div>
</div>
@endsection
