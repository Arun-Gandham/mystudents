@extends('tenant.baselayout')

@section('title', 'Create Timetable')

@section('content')
<div class="container">
    <h2>Create Timetable</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.timetables.store', ['school_sub' => current_school_sub()]) }}">
        @csrf

        <div class="mb-3">
            <label>Section</label>
            <select name="section_id" class="form-control" required>
                <option value="">-- Select Section --</option>
                @foreach($sections as $s)
                    <option value="{{ $s->id }}" {{ old('section_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->grade->name }} - {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Day</label>
            <select name="day" class="form-control" required>
                <option value="">-- Select Day --</option>
                @foreach(\App\Constants\WeekDays::LIST as $num => $name)
                    <option value="{{ $num }}" {{ old('day') == $num ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title') }}" required>
        </div>

        <div class="row">
            <div class="col">
                <label>Effective From</label>
                <input type="date" name="effective_from" class="form-control"
                       value="{{ old('effective_from') }}" required>
            </div>
            <div class="col">
                <label>Effective To</label>
                <input type="date" name="effective_to" class="form-control"
                       value="{{ old('effective_to') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>
@endsection
