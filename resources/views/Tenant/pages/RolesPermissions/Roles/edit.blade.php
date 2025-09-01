@extends('tenant.baselayout')

@section('title', 'Edit Role')

@section('content')
<div class="container py-3">
    <h3>Edit Role</h3>

    <form action="{{ tenant_route('tenant.roles.update', ['role_id' => $role->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="is_system" value="1"
                {{ old('is_system', $role->is_system) ? 'checked' : '' }}>
            <label class="form-check-label">Mark as System Role</label>
        </div>

        <input type="hidden" name="school_id" value="{{ $role->school_id ?? current_school_id() }}">

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ tenant_route('tenant.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
