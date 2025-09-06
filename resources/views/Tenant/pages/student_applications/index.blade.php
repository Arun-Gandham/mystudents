@extends('tenant.baselayout')
@section('title','Student Applications')

@section('content')
<div class="container-fluid py-3">

  <h4>Student Applications</h4>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="search" class="form-control"
             value="{{ request('search') }}" placeholder="Search...">

      <select name="status" class="form-select">
        <option value="">All Status</option>
        @foreach(['lead','submitted','reviewing','offered','accepted','rejected','no_response','withdrawn'] as $st)
          <option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>
            {{ ucfirst($st) }}
          </option>
        @endforeach
      </select>

      <button class="btn btn-primary">Filter</button>

      {{-- Reset button --}}
      <a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    <a href="{{ tenant_route('tenant.applications.create') }}" class="btn btn-success">+ New</a>
  </div>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Application No</th>
        <th>Child</th>
        <th>Guardian</th>
        <th>Status</th>
        <th>Applied</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($applications as $app)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $app->application_no }}</td>
        <td>{{ $app->child_full_name }}</td>
        <td>{{ $app->guardian_full_name }}<br><small>{{ $app->guardian_phone }}</small></td>
        <td><span class="badge bg-info">{{ ucfirst($app->status) }}</span></td>
        <td>{{ $app->created_at->format('d M Y') }}</td>
        <td>
          <a href="{{ tenant_route('tenant.applications.show',['application' => $app->id]) }}" class="btn btn-sm btn-info">View</a>
          <a href="{{ tenant_route('tenant.applications.edit',['application' => $app->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ tenant_route('tenant.applications.destroy',['application' => $app->id]) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</button>
          </form>
          <a href="{{ tenant_route('tenant.admissions.fromApp.create',['application'=>$app->id]) }}" 
            class="btn btn-sm btn-success">
            Admit
          </a>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center">No applications found.</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $applications->links() }}
</div>
@endsection
