@extends('tenant.baselayout')

@section('title', 'Timetables')

@section('content')
<div class="container">
    <h2>Section Timetables</h2>

    <div class="mb-3 d-flex gap-2">
        {{-- Create New Timetable --}}
        <a href="{{ tenant_route('tenant.timetables.create', ['school_sub' => current_school_sub()]) }}" 
           class="btn btn-primary">
            Create New
        </a>

        {{-- Copy From Previous --}}
        <a href="{{ tenant_route('tenant.timetables.copyForm', ['school_sub' => current_school_sub()]) }}" 
           class="btn btn-warning">
            Copy From Previous
        </a>
    </div>

    @foreach($timetables as $sectionId => $sectionTimetables)
        <h4 class="mt-4">
            Section: {{ $sectionTimetables->first()->section->grade->name }} - 
            {{ $sectionTimetables->first()->section->name }}
        </h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Day</th>
                    <th>Effective From</th>
                    <th>Effective To</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sectionTimetables as $t)
                <tr>
                    <td>{{ $t->title }}</td>
                    <td>{{ \App\Constants\WeekDays::LIST[$t->day] ?? $t->day }}</td>
                    <td>{{ $t->effective_from->format('d-M-Y') }}</td>
                    <td>{{ $t->effective_to ? $t->effective_to->format('d-M-Y') : '-' }}</td>
                    <td>{{ $t->is_active ? 'Yes' : 'No' }}</td>
                    <td class="d-flex gap-1">

                        {{-- View --}}
                        <a href="{{ tenant_route('tenant.timetables.show', [
                                'id'  => $t->id
                            ]) }}" 
                           class="btn btn-sm btn-info">
                            View
                        </a>

                        {{-- Delete --}}
                        <form action="{{ tenant_route('tenant.timetables.destroy', [
                                    'id'  => $t->id
                                ]) }}" 
                              method="POST" style="display:inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this timetable?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
</div>
@endsection
