@extends('tenant.baselayout')

@section('title', 'Staff Profile')

@section('content')
<div class="container">
    <h2>Staff Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3" style="max-width: 600px;">
        <div class="card-body text-center">
            @if($staff->photo)
                <img src="{{ asset('storage/'.$staff->photo) }}" 
                     class="rounded-circle mb-3" width="120" height="120" alt="Profile Photo">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" 
                     class="rounded-circle mb-3" width="120" height="120" alt="Default Photo">
            @endif

            <h5 class="card-title">{{ $staff->name }}</h5>
            <p class="mb-1"><strong>Email:</strong> {{ $staff->email }}</p>
            <p class="mb-1"><strong>Phone:</strong> {{ $staff->phone }}</p>
            <p class="mb-1"><strong>Designation:</strong> {{ $staff->designation }}</p>
            <p class="mb-1"><strong>Address:</strong> {{ $staff->address }}</p>

            <a href="{{ tenant_route('tenant.staff.profile.edit') }}" 
               class="btn btn-primary mt-3">Edit Profile {{ auth()->user()?->staff?->id }}</a>
        </div>
    </div>
</div>
@endsection
