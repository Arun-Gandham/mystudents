@extends('tenant.layouts.layout1')
@section('title','Edit Student')

@section('content')
<div class="container-fluid py-4">
  <h4>Edit Student</h4>
  @include('components.alert-errors')

  <form method="POST" enctype="multipart/form-data"
        action="{{ tenant_route('tenant.students.update',['student'=>$student->id]) }}">
    @csrf @method('PUT')

    <div class="card p-3 mb-3">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input type="text" name="full_name" value="{{ old('full_name',$student->full_name) }}" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">DOB</label>
          <input type="date" name="dob" value="{{ old('dob',$student->dob) }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">-- Select --</option>
            @foreach(['Male','Female','Other'] as $g)
              <option value="{{ $g }}" {{ $student->gender==$g?'selected':'' }}>{{ $g }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Profile Photo</label>
          <input type="file" name="photo" class="form-control">
          @if($student->photo)
            <img src="{{ asset('storage/'.$student->photo) }}" class="mt-2 rounded" width="80">
          @endif
        </div>
      </div>
    </div>

    <div class="text-end">
      <a href="{{ tenant_route('tenant.students.show',['student'=>$student->id]) }}" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-success">Update</button>
    </div>
  </form>
</div>
@endsection
