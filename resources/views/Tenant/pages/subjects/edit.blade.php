@extends('tenant.layouts.layout1')

@section('title', 'Edit Subject')

@section('content')
<div class="container-fluid">
    <h2>Edit Subject</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.subjects.update',['id' => $subject->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code) }}" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ tenant_route('tenant.subjects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
