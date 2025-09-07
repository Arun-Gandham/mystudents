@extends('tenant.baselayout')
@section('title','Mark Attendance')

@section('content')
<div class="container-fluid py-3">
  <h4>Mark Attendance</h4>
  @include('components.alert-errors')

  {{-- Filter Section --}}
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="date" name="date" value="{{ $date }}" class="form-control">
    </div>
    <div class="col-md-3">
      <select name="grade_id" class="form-select" onchange="this.form.submit()">
        <option value="">-- Select Grade --</option>
        @foreach($grades as $g)
          <option value="{{ $g->id }}" {{ $gradeId==$g->id?'selected':'' }}>{{ $g->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="section_id" class="form-select" onchange="this.form.submit()">
        <option value="">-- Select Section --</option>
        @foreach($sections as $s)
          <option value="{{ $s->id }}" {{ $sectionId==$s->id?'selected':'' }}>{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="session" class="form-select">
        <option value="morning" {{ $session=='morning'?'selected':'' }}>Morning</option>
        <option value="afternoon" {{ $session=='afternoon'?'selected':'' }}>Afternoon</option>
        <option value="both" {{ $session=='both'?'selected':'' }}>Both</option>
      </select>
    </div>
    <div class="col-md-12 text-end">
      <button class="btn btn-primary">Load Students</button>
    </div>
  </form>

  @if($students->count()==0)
    <div class="alert alert-warning">Select grade & section to load students.</div>
  @else
  <form method="POST" action="{{ tenant_route('tenant.studentAttendance.store') }}">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">
    <input type="hidden" name="grade_id" value="{{ $gradeId }}">
    <input type="hidden" name="section_id" value="{{ $sectionId }}">
    <input type="hidden" name="session" value="{{ $session }}">

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
        <tr>
          <td>{{ $stu->admission_no }}</td>
          <td>{{ $stu->full_name }}</td>
          <td>
            <select name="students[{{ $stu->id }}][status]" class="form-select">
              @foreach(['present','absent','late','half_day','excused'] as $st)
                <option value="{{ $st }}" {{ $st=='present'?'selected':'' }}>{{ ucfirst($st) }}</option>
              @endforeach
            </select>
          </td>
          <td><input type="text" name="students[{{ $stu->id }}][remarks]" class="form-control"></td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end">
      <button type="submit" class="btn btn-success">Save Attendance</button>
    </div>
  </form>
  @endif
</div>
@endsection
