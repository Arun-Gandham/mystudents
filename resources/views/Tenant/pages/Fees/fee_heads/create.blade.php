@extends('tenant.layouts.layout1')
@section('title','Create Fee Head')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm p-4">
    <h4 class="mb-3">Add New Fee Head</h4>

    <form method="POST" action="{{ tenant_route('tenant.fees.fee-heads.store') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Fee Head Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code') }}">
        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="d-flex justify-content-end">
        <a href="{{ tenant_route('tenant.fees.fee-heads.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
