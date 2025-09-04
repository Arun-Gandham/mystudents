@extends('tenant.baselayout')

@section('title', 'Timetables')

@section('content')
<div class="container-fluid">
    <h2 class="mb-3">Section Timetables</h2>

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

    <div class="row">
        {{-- Left side: Sections as tabs --}}
        <div class="col-3">
            <ul class="nav flex-column nav-pills" id="sectionTabs" role="tablist">
                @foreach($timetables as $sectionId => $sectionTimetables)
                    <li class="nav-item mb-2" role="presentation">
                        <button class="nav-link w-100 {{ $loop->first ? 'active' : '' }}" 
                                id="tab-{{ $sectionId }}" 
                                data-bs-toggle="pill" 
                                data-bs-target="#content-{{ $sectionId }}" 
                                type="button" role="tab">
                            {{ $sectionTimetables->first()->section->grade->name }} - 
                            {{ $sectionTimetables->first()->section->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Right side: Timetables --}}
        <div class="col-9">
            <div class="tab-content" id="sectionTabsContent">
                @foreach($timetables as $sectionId => $sectionTimetables)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                         id="content-{{ $sectionId }}" 
                         role="tabpanel" 
                         aria-labelledby="tab-{{ $sectionId }}">
                         
                        <h4>{{ $sectionTimetables->first()->section->grade->name }} - 
                            {{ $sectionTimetables->first()->section->name }}</h4>

                        <table class="table table-bordered mt-3">
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
                                        <a href="{{ tenant_route('tenant.timetables.show', ['id' => $t->id]) }}" 
                                           class="btn btn-sm btn-info">
                                           View
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ tenant_route('tenant.timetables.destroy', ['id' => $t->id]) }}" 
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
