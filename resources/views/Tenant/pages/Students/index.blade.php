@extends('Tenant.layouts.layout1')
@section('title','Students')

@section('content')
<div class="container-fluid">
  <h2>Students</h2>
  <a href="{{ tenant_route('tenant.students.create') }}" class="btn btn-primary mb-3">New Student</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Admission No</th>
        <th>Name</th>
        <th>Grade</th>
        <th>Section</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $student)
      <tr>
        <td>{{ $student->admission_no }}</td>
        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
        <td>{{ $student->enrollments->first()->grade->name ?? '-' }}</td>
        <td>{{ $student->enrollments->first()->section->name ?? '-' }}</td>
        <td>
          <a href="{{ tenant_route('tenant.students.show',['id' =>$student->id]) }}" class="btn btn-sm btn-info">View</a>
          <a href="{{ tenant_route('tenant.students.edit',['student' =>$student->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ tenant_route('tenant.students.destroy',['student' =>$student->id]) }}" method="POST" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete student?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $students->links() }}
</div>
@endsection
