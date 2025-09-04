@extends('tenant.baselayout')

@section('title','Staff Details')

@section('content')
<div class="container">
    {{-- Top Profile Section --}}
    <div class="card mb-3">
        <div class="card-body d-flex align-items-center gap-3">
            <div>
                @if($staff->photo)
                    <img src="{{ asset('storage/'.$staff->photo) }}" width="80" class="rounded-circle">
                @else
                    <span class="badge bg-secondary">No Photo</span>
                @endif
            </div>
            <div>
                <h4>{{ $staff->first_name }} {{ $staff->last_name }}</h4>
                <p>{{ $staff->designation ?? 'N/A' }} | {{ $staff->user->email }}</p>
                <p>Experience: {{ $staff->experience_years }} years</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs" id="staffTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dashboard">Dashboard</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#roles">Roles</button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        {{-- Dashboard Tab --}}
        <div class="tab-pane fade show active" id="dashboard">
            <h5>Staff Dashboard</h5>
            <p>Joining Date: {{ $staff->joining_date ? $staff->joining_date->format('d M Y') : '-' }}</p>
            <p>Phone: {{ $staff->phone }}</p>
            <p>Address: {{ $staff->address }}</p>
        </div>

        {{-- Roles Tab --}}
        <div class="tab-pane fade" id="roles">
            <h5>Assigned Roles</h5>
            <ul>
                @foreach($staff->user->roles as $role)
                    <li>{{ $role->name }} @if($role->pivot->is_primary) (Primary) @endif</li>
                @endforeach
            </ul>
            <form method="POST" action="#">
                {{-- future: add role assignment UI --}}
            </form>
        </div>
    </div>
</div>
@endsection
