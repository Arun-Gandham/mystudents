@extends('tenant.baselayout')

@section('title', 'Add School Holiday')

@section('content')
<div class="container-fluid">
    <h2>Add Holiday</h2>

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.school_holidays.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Academic Year</label>
            <select name="academic_id" class="form-control" required>
                <option value="">Select Academic</option>
                @foreach($academics as $academic)
                    <option value="{{ $academic->id }}" 
                        {{ old('academic_id') == $academic->id ? 'selected' : '' }}>
                        {{ $academic->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Holiday Name</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control"
                   value="{{ old('date') }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_full_day" value="1" class="form-check-input"
                {{ old('is_full_day', 1) ? 'checked' : '' }}>
            <label class="form-check-label">Full Day Holiday</label>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Starts At (if not full day)</label>
                <input type="time" name="starts_at" class="form-control"
                       value="{{ old('starts_at') }}">
            </div>
            <div class="col">
                <label class="form-label">Ends At</label>
                <input type="time" name="ends_at" class="form-control"
                       value="{{ old('ends_at') }}">
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="repeats_annually" value="1" class="form-check-input"
                {{ old('repeats_annually') ? 'checked' : '' }}>
            <label class="form-check-label">Repeat Every Year</label>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ tenant_route('tenant.school_holidays.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
