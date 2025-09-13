{{-- resources/views/tenant/academic_years/edit.blade.php --}}
@extends('tenant.layouts.layout1')

@section('content')
<div class="container-fluid">
    <h1>Edit Academic Year</h1>
    <form action="{{ tenant_route('tenant.academic_years.update', ['academic_year' => $academic_year]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-alert-errors />
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $academic_year->name) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date', $academic_year->start_date->format('Y-m-d')) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date', $academic_year->end_date->format('Y-m-d')) }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ tenant_route('tenant.academic_years.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
