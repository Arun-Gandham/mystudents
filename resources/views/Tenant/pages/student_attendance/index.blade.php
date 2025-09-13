@extends('tenant.layouts.layout1')
@section('title','Attendance Sheets')

@section('content')
<div class="container-fluid py-3">
  <h4>Attendance Sheets</h4>

  {{-- Filters --}}
  <form method="GET" id="filterForm" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="date" name="date" value="{{ $date }}" class="form-control">
    </div>
    <div class="col-md-3">
      <select name="grade_id" id="gradeSelect" class="form-select">
        <option value="">All Grades</option>
        @foreach($grades as $g)
          <option value="{{ $g->id }}" {{ $gradeId==$g->id?'selected':'' }}>{{ $g->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="section_id" id="sectionSelect" class="form-select">
        <option value="">All Sections</option>
        @foreach($sections as $s)
          <option value="{{ $s->id }}" {{ $sectionId==$s->id?'selected':'' }}>{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="{{ tenant_route('tenant.studentAttendance.index') }}" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <div class="mb-3 text-end">
    <a href="{{ tenant_route('tenant.studentAttendance.create') }}" class="btn btn-success">+ Mark Attendance</a>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Grade</th>
        <th>Section</th>
        <th>Session</th>
        <th>Present / Total</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($sheets as $sheet)
      <tr>
        <td>{{ \Carbon\Carbon::parse($sheet->attendance_date)->format('d M Y') }}</td>
        <td>{{ $sheet->section->grade->name }}</td>
        <td>{{ $sheet->section->name }}</td>
        <td>{{ ucfirst($sheet->session) }}</td>
        <td>
          {{ $sheet->entries->where('status','present')->count() }} /
          {{ $sheet->entries->count() }}
        </td>
        <td>
          <a href="{{ tenant_route('tenant.studentAttendance.view',['sheet'=>$sheet->id]) }}" class="btn btn-sm btn-info">View</a>
          <a href="{{ tenant_route('tenant.studentAttendance.edit',['sheet'=>$sheet->id]) }}" class="btn btn-sm btn-warning">Edit</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center text-muted">No attendance sheets</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="d-flex justify-content-end">
    {{ $sheets->links() }}
  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('gradeSelect').addEventListener('change', function () {
    const gradeId = this.value;
    const sectionSelect = document.getElementById('sectionSelect');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';

    if (!gradeId) {
        sectionSelect.innerHTML = '<option value="">All Sections</option>';
        return;
    }

    fetch("{{ tenant_route('tenant.sections.byGrade') }}?grade_id=" + gradeId)
        .then(res => res.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            data.forEach(sec => {
                sectionSelect.innerHTML += `<option value="${sec.id}">${sec.name}</option>`;
            });
        });
});
</script>
@endpush
