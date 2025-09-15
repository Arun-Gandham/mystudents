@extends('tenant.layouts.layout1')
@section('title','Admissions')

@section('content')
<div class="container-fluid">
  <h2>Admissions</h2>
  <a href="{{ tenant_route('tenant.admissions.create') }}" class="btn btn-primary mb-3">New Admission</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Admission No</th>
        <th>Student</th>
        <th>Grade</th>
        <th>Status</th>
        <th>Admitted On</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($admissions as $adm)
      <tr>
        <td>{{ $adm->application_no }}</td>
        <td>{{ $adm->student->first_name }} {{ $adm->student->last_name }}</td>
        <td>{{ $adm->grade->name ?? '-' }}</td>
        <td>{{ ucfirst($adm->status) }}</td>
        <td>{{ $adm->admitted_on }}</td>
        <td>
          <a href="{{ tenant_route('tenant.admissions.edit',['admission' => $adm->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ tenant_route('tenant.admissions.destroy',['admission' => $adm->id]) }}" method="POST" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete admission?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $admissions->links() }}
</div>
@endsection
