{{-- resources/views/tenant/academic_years/create.blade.php --}}
@extends('tenant.baselayout')

@section('content')
<div class="container">
    <h1>Add Academic Year</h1>
    <form action="{{ tenant_route('tenant.academic_years.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. 2024-2025" required>
        </div>
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ tenant_route('tenant.academic_years.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
