@extends('tenant.layouts.layout1')
@section('title','Admissions')

@section('content')
<div class="container-fluid py-3">
  <h4>Admissions</h4>

  <div class="d-flex justify-content-between mb-3">
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Student..." class="form-control">
      <select name="status" class="form-select">
        <option value="">All Status</option>
        @foreach(['pending','offered','admitted','rejected','waitlisted','cancelled'] as $st)
          <option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>{{ ucfirst($st) }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ tenant_route('tenant.admissions.index') }}" class="btn btn-secondary">Reset</a>
    </form>
    <a href="{{ tenant_route('tenant.admissions.create') }}" class="btn btn-success">+ New Admission</a>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Student</th>
        <th>Grade</th>
        <th>Section</th>
        <th>Status</th>
        <th>Admitted On</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($admissions as $admission)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $admission->student->full_name }}</td>
        <td>{{ $admission->grade->name ?? '-' }}</td>
        <td>{{ $admission->section->name ?? '-' }}</td>
        <td><span class="badge bg-info">{{ ucfirst($admission->status) }}</span></td>
        <td>{{ optional($admission->admitted_on)->format('d M Y') }}</td>
        <td>
          <a href="{{ tenant_route('tenant.admissions.edit',['admission'=>$admission->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ tenant_route('tenant.admissions.destroy',['admission'=>$admission->id]) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $admissions->links() }}
</div>
@endsection
