@extends('tenant.layouts.layout1')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <h2>My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4">
        <!-- Profile Image -->
        <div class="text-center mb-4">
            <img src="{{ $user->staff->photo ? asset('storage/'.$user->staff->photo) : asset('images/default-avatar.png') }}"
                 class="rounded-circle mb-2" width="120" height="120" alt="Profile Photo">
            <h4>{{ $user->name }}</h4>
            <p class="text-muted mb-0">{{ $user->designation ?? 'No designation set' }}</p>
        </div>

        <hr>

        <!-- Profile Info -->
        <dl class="row">
            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9">{{ $user->email }}</dd>

            <dt class="col-sm-3">Phone</dt>
            <dd class="col-sm-9">{{ $user->phone }}</dd>

            <dt class="col-sm-3">Address</dt>
            <dd class="col-sm-9">{{ $user->address ?? 'Not provided' }}</dd>
        </dl>

        <div class="text-end">
            <a href="{{ tenant_route('tenant.profile.edit') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection
