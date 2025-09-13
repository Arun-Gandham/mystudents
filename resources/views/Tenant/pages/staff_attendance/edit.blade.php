@extends('tenant.layouts.layout1')
@section('title','Edit Attendance')

@section('content')
<div class="container py-3">
  <h4>Edit Attendance</h4>
  @include('components.alert-errors')

  <form method="POST" action="{{ tenant_route('tenant.staffAttendance.update',['attendance'=>$attendance->id]) }}">
    @csrf @method('PUT')

    <div class="card p-3">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Date</label>
          <input type="date" name="attendance_date" value="{{ old('attendance_date',$attendance->attendance_date) }}" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Session</label>
          <select name="session" class="form-select">
            @foreach(['morning','afternoon'] as $sess)
              <option value="{{ $sess }}" {{ $attendance->session==$sess?'selected':'' }}>
                {{ ucfirst($sess) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['present','absent','late','half_day','excused'] as $st)
              <option value="{{ $st }}" {{ $attendance->status==$st?'selected':'' }}>
                {{ ucfirst($st) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Check In</label>
          <input type="time" name="check_in" value="{{ old('check_in',$attendance->check_in) }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Check Out</label>
          <input type="time" name="check_out" value="{{ old('check_out',$attendance->check_out) }}" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control">{{ old('remarks',$attendance->remarks) }}</textarea>
        </div>
      </div>
    </div>

    <div class="mt-3 text-end">
      <a href="{{ tenant_route('tenant.staffAttendance.list') }}" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-success">Update Attendance</button>
    </div>
  </form>
</div>
@endsection
