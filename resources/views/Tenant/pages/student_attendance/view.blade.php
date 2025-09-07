@extends('tenant.baselayout')
@section('title','View Attendance')

@section('content')
<div class="container-fluid py-3">
  <h4>Attendance — {{ \Carbon\Carbon::parse($sheet->attendance_date)->format('d M Y') }} ({{ ucfirst($sheet->session) }})</h4>

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
      @foreach($entries as $entry)
      <tr>
        <td>{{ $entry->student->admission_no }}</td>
        <td>{{ $entry->student->full_name }}</td>
        <td><span class="badge bg-info">{{ ucfirst($entry->status) }}</span></td>
        <td>{{ $entry->remarks ?? '-' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
