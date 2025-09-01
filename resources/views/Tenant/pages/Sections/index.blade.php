@extends('tenant.baselayout')
@section('title', 'Sections')

@section('content')
<div class="container py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Sections</h4>
    <a href="{{ tenant_route('tenant.sections.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Add Section
    </a>
  </div>

  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Grade</th>
        <th>Class Teacher</th>
        <th width="180">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($sections as $section)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $section->name }}</td>
          <td>{{ $section->grade?->name }}</td>
          <td>{{ $section->teacher?->name ?? '-' }}</td>
          <td>
            <a href="{{ tenant_route('tenant.sections.edit', ['id' => $section->id]) }}" class="btn btn-sm btn-warning">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <form action="{{ tenant_route('tenant.sections.destroy', ['id' => $section->id]) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this section?')">
                <i class="bi bi-trash"></i> Delete
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No sections found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
