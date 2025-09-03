@extends('tenant.baselayout')

@section('title', 'Timetable')

@section('content')
<div class="container">
    <h2>{{ $timetable->title }} ({{ $timetable->day_name }})</h2>
    <h4>Section: {{ $timetable->section->name }}</h4>

    <hr>
    <h5>Periods</h5>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Period</th>
                <th>Time</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Room</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($timetable->periods as $p)
            <tr>
                <td>{{ $p->period_no }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($p->starts_at)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($p->ends_at)->format('h:i A') }}
                </td>
                <td>{{ $p->subject->name ?? '-' }}</td>
                <td>{{ $p->teacher->full_name ?? '-' }}</td>
                <td>{{ $p->room ?? '-' }}</td>
                <td>{{ $p->note ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ tenant_route('tenant.timetables.periods.destroy', ['school_sub' => current_school_sub(), 'timetable' => $timetable->id, 'period' => $p->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <hr>
    <h5>Add New Period</h5>
    <form method="POST" action="{{ tenant_route('tenant.timetables.periods.store', ['school_sub' => current_school_sub(), 'timetable' => $timetable->id]) }}">
        @csrf
        <div class="row">
            <div class="col">
                <input type="number" name="period_no"
                       class="form-control @error('period_no') is-invalid @enderror"
                       placeholder="Period No"
                       value="{{ old('period_no') }}" required>
                @error('period_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <input type="time" name="starts_at"
                       class="form-control @error('starts_at') is-invalid @enderror"
                       value="{{ old('starts_at') }}" required>
                @error('starts_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <input type="time" name="ends_at"
                       class="form-control @error('ends_at') is-invalid @enderror"
                       value="{{ old('ends_at') }}" required>
                @error('ends_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mt-2">
            <div class="col">
                <select name="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ old('subject_id') == $subj->id ? 'selected' : '' }}>
                            {{ $subj->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <select name="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required>
                    <option value="">-- Select Teacher --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <input type="text" name="room"
                       class="form-control @error('room') is-invalid @enderror"
                       placeholder="Room"
                       value="{{ old('room') }}">
                @error('room')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-2">
            <textarea name="note"
                      class="form-control @error('note') is-invalid @enderror"
                      placeholder="Note">{{ old('note') }}</textarea>
            @error('note')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success mt-2">Add Period</button>
    </form>
</div>
@endsection
