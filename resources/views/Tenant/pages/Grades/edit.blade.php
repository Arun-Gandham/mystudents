@extends('tenant.layouts.layout1')
@section('title','Edit Grade')

@section('content')
<div class="container-fluid py-3">
  <h4>Edit Grade</h4>

  <form action="{{ tenant_route('tenant.grades.update', ['id' => $grade->id]) }}" method="POST" class="mt-3">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $grade->name) }}" required>
      @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Ordinal</label>
      <input type="number" name="ordinal" class="form-control" value="{{ old('ordinal', $grade->ordinal) }}" required>
      @error('ordinal') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $grade->is_active ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ tenant_route('tenant.grades.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
