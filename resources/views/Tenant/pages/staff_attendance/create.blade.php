@extends('tenant.baselayout')
@section('title','Record Staff Attendance')

@section('content')
<div class="container-fluid py-3">
  <h4>Record Staff Attendance</h4>
  @include('components.alert-errors')

  <form method="POST" action="{{ tenant_route('tenant.staffAttendance.store') }}">
    @csrf

    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="date" name="attendance_date" class="form-control" value="{{ now()->toDateString() }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Session</label>
        <select name="session" class="form-select" required>
          <option value="morning">Morning</option>
          <option value="afternoon">Afternoon</option>
        </select>
      </div>
    </div>

    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Staff</th>
          <th>Status</th>
          <th>Check In</th>
          <th>Check Out</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        @foreach($staff as $member)
        <tr>
          <td>
            <img src="{{ $member->photo ? asset('storage/'.$member->photo) : asset('images/default-avatar.png') }}"
                 class="rounded-circle me-2" width="35" height="35">
            {{ $member->first_name }} {{ $member->last_name }}
          </td>
          <td>
            <select name="attendance[{{ $member->user_id }}]" class="form-select">
              @foreach(['present','absent','late','half_day','excused'] as $status)
                <option value="{{ $status }}" {{ $status=='present'?'selected':'' }}>
                  {{ ucfirst($status) }}
                </option>
              @endforeach
            </select>
          </td>
          <td><input type="time" name="check_in[{{ $member->user_id }}]" class="form-control"></td>
          <td><input type="time" name="check_out[{{ $member->user_id }}]" class="form-control"></td>
          <td><input type="text" name="remarks[{{ $member->user_id }}]" class="form-control"></td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end">
      <a href="{{ tenant_route('tenant.staffAttendance.list') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-success">Save Attendance</button>
    </div>
  </form>
</div>
@endsection
