@extends('tenant.layouts.layout1')
@section('title','Applications')

@section('content')
<div class="container-fluid">
  <h2>Student Applications</h2>

  <a href="{{ tenant_route('tenant.applications.create') }}" class="btn btn-primary mb-3">New Application</a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>App No</th>
        <th>Name</th>
        <th>Guardian</th>
        <th>Status</th>
        <th>Preferred Grade</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($applications as $app)
      <tr>
        <td>{{ $app->application_no }}</td>
        <td>{{ $app->first_name }} {{ $app->last_name }}</td>
        <td>{{ $app->guardian_name }}</td>
        <td>{{ ucfirst($app->status) }}</td>
        <td>{{ $app->preferredGrade->name ?? '-' }}</td>
        <td>
          <a href="{{ tenant_route('tenant.applications.show',['application' => $app->id]) }}" class="btn btn-sm btn-info">View</a>
          <a href="{{ tenant_route('tenant.applications.edit',['application' => $app->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ tenant_route('tenant.applications.destroy',['application' => $app->id]) }}" method="POST" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete application?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $applications->links() }}
</div>
@endsection
