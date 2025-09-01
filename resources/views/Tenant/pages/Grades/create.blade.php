@extends('tenant.baselayout')
@section('title','Create Grade')

@section('content')
<div class="container py-3">
  <h4>Create Grade</h4>

  <form action="{{ tenant_route('tenant.grades.store') }}" method="POST" class="mt-3">
    @csrf

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
      @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Ordinal</label>
      <input type="number" name="ordinal" class="form-control" value="{{ old('ordinal') }}" required>
      @error('ordinal') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
      <label class="form-check-label">Active</label>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ tenant_route('tenant.grades.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
