@extends('tenant.baselayout')
@section('title','Edit Section')

@section('content')
<div class="container py-3">
  <h4>Edit Section</h4>

  <form action="{{ tenant_route('tenant.sections.update',[ 'id' => $section->id]) }}" method="POST" class="mt-3">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Grade</label>
      <select name="grade_id" class="form-select" required>
        <option value="">-- Select Grade --</option>
        @foreach($grades as $grade)
          <option value="{{ $grade->id }}" {{ old('grade_id', $section->grade_id) == $grade->id ? 'selected' : '' }}>
            {{ $grade->name }}
          </option>
        @endforeach
      </select>
      @error('grade_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Class Teacher</label>
      <select name="teacher_id" class="form-select">
        <option value="">-- Select Teacher --</option>
        @foreach($teachers as $teacher)
          <option value="{{ $teacher->id }}" {{ old('teacher_id', $section->teacher_id) == $teacher->id ? 'selected' : '' }}>
            {{ $teacher->name }}
          </option>
        @endforeach
      </select>
      @error('teacher_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ tenant_route('tenant.sections.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
