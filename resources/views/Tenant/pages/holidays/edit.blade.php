@extends('tenant.baselayout')

@section('title', 'Edit School Holiday')

@section('content')
<div class="container-fluid">
    <h2>Edit Holiday</h2>

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

    <form method="POST" action="{{ tenant_route('tenant.school_holidays.update', ['id' => $holiday->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Academic Year</label>
            <select name="academic_id" class="form-control" required>
                <option value="">Select Academic</option>
                @foreach($academics as $academic)
                    <option value="{{ $academic->id }}" 
                        {{ old('academic_id', $holiday->academic_id) == $academic->id ? 'selected' : '' }}>
                        {{ $academic->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Holiday Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $holiday->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control"
                   value="{{ old('date', $holiday->date->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_full_day" value="1" class="form-check-input"
                {{ old('is_full_day', $holiday->is_full_day) ? 'checked' : '' }}>
            <label class="form-check-label">Full Day Holiday</label>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Starts At (if not full day)</label>
                <input type="time" name="starts_at" class="form-control"
                       value="{{ old('starts_at', $holiday->starts_at) }}">
            </div>
            <div class="col">
                <label class="form-label">Ends At</label>
                <input type="time" name="ends_at" class="form-control"
                       value="{{ old('ends_at', $holiday->ends_at) }}">
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="repeats_annually" value="1" class="form-check-input"
                {{ old('repeats_annually', $holiday->repeats_annually) ? 'checked' : '' }}>
            <label class="form-check-label">Repeat Every Year</label>
        </div>

        <button type="submit" class="btn btn-success">Update Holiday</button>
        <a href="{{ tenant_route('tenant.school_holidays.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
