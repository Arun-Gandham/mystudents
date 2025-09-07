@extends('tenant.baselayout')
@section('title','Edit Attendance')

@section('content')
<div class="container-fluid py-3">
  <h4>Edit Attendance — {{ \Carbon\Carbon::parse($sheet->attendance_date)->format('d M Y') }} ({{ ucfirst($sheet->session) }})</h4>
  @include('components.alert-errors')

  <form method="POST" action="{{ tenant_route('tenant.studentAttendance.update',['sheet'=>$sheet->id]) }}">
    @csrf @method('PUT')

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Roll No</th>
          <th>Name</th>
          <th>Status</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        @foreach($students as $stu)
          @php $entry = $entries[$stu->id] ?? null; @endphp
          <tr>
            <td>{{ $stu->admission_no }}</td>
            <td>{{ $stu->full_name }}</td>
            <td>
              <select name="students[{{ $stu->id }}][status]" class="form-select">
                @foreach(['present','absent','late','half_day','excused'] as $st)
                  <option value="{{ $st }}" {{ ($entry?->status ?? 'present')==$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                @endforeach
              </select>
            </td>
            <td><input type="text" name="students[{{ $stu->id }}][remarks]" value="{{ $entry?->remarks }}" class="form-control"></td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end">
      <button type="submit" class="btn btn-success">Update Attendance</button>
    </div>
  </form>
</div>
@endsection
