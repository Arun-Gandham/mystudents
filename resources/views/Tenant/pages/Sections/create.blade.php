@extends('tenant.baselayout')
@section('title','Create Section')

@section('content')
<div class="container-fluid py-3">
  <h4>Create Section</h4>
  @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  <form action="{{ tenant_route('tenant.sections.store') }}" method="POST" class="mt-3">
    @csrf

    <div class="mb-3">
      <label class="form-label">Section Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
      @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Grade</label>
      <select name="grade_id" class="form-select" required>
        <option value="">-- Select Grade --</option>
        @foreach($grades as $grade)
          <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
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
          <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
            {{ $teacher->full_name }}
          </option>
        @endforeach
      </select>
      @error('teacher_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ tenant_route('tenant.sections.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
