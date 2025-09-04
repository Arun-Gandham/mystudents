@extends('tenant.baselayout')

@section('title', 'Holiday List')

@section('content')
<div class="container-fluid">
    <h2>Holiday List</h2>

    {{-- Academic Year Selector --}}
    <form method="GET" action="{{ tenant_route('tenant.school_holidays.index') }}" class="mb-3">
        <label for="academic_id" class="form-label">Select Academic Year</label>
        <div class="d-flex gap-2">
            <select name="academic_id" id="academic_id" class="form-control" onchange="this.form.submit()">
                @foreach($academics as $academic)
                    <option value="{{ $academic->id }}" 
                        {{ $academicId == $academic->id ? 'selected' : '' }}>
                        {{ $academic->name }}
                    </option>
                @endforeach
            </select>
            <a href="{{ tenant_route('tenant.school_holidays.create') }}" class="btn btn-primary">+ Add Holiday</a>
        </div>
    </form>

    {{-- Holiday Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Full Day?</th>
                <th>Time</th>
                <th>Repeats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holidays as $holiday)
                <tr>
                    <td>{{ $holiday->date->format('d M Y') }}</td>
                    <td>{{ $holiday->name }}</td>
                    <td>{{ $holiday->is_full_day ? 'Yes' : 'No' }}</td>
                    <td>
                        @if(!$holiday->is_full_day)
                            {{ $holiday->starts_at }} - {{ $holiday->ends_at }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $holiday->repeats_annually ? 'Yes' : 'No' }}</td>
                    <td>
                        {{-- Edit --}}
                        <a href="{{ tenant_route('tenant.school_holidays.edit', ['id' => $holiday->id]) }}" 
                           class="btn btn-sm btn-warning">Edit</a>

                        {{-- Delete --}}
                        <form action="{{ tenant_route('tenant.school_holidays.destroy', ['id' => $holiday->id]) }}" 
                              method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this holiday?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No holidays for this academic year.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
