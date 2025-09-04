@extends('tenant.baselayout')
@section('title', 'Grades')

@section('content')
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Grades</h4>
    <a href="{{ tenant_route('tenant.grades.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Add Grade
    </a>
  </div>
  
  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Ordinal</th>
        <th>Status</th>
        <th width="180">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($grades as $grade)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $grade->name }}</td>
          <td>{{ $grade->ordinal }}</td>
          <td>
            @if($grade->is_active)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-secondary">Inactive</span>
            @endif
          </td>
          <td>
            <a href="{{ tenant_route('tenant.grades.edit', ['id' => $grade->id]) }}" class="btn btn-sm btn-warning">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <form action="{{ tenant_route('tenant.grades.destroy', ['id' => $grade->id]) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this grade?')">
                <i class="bi bi-trash"></i> Delete
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No grades found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
