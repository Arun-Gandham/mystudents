@extends('tenant.layouts.layout1')

@section('title', 'Create Role')

@section('content')
<div class="container-fluid py-3">
    <h3>Add New Role</h3>

    <form action="{{ tenant_route('tenant.roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        {{-- System roles are usually seeded, but allow superadmin to flag --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="is_system" value="1" {{ old('is_system') ? 'checked' : '' }}>
            <label class="form-check-label">Mark as System Role</label>
        </div>

        {{-- Auto-filled from tenant, but keep hidden --}}
        <input type="hidden" name="school_id" value="{{ current_school_id() }}">

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ tenant_route('tenant.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
