@extends('tenant.layouts.layout1')

@section('title', 'Error')

@section('content')
<div class="container-fluid d-flex flex-column justify-content-center align-items-center text-center" style="min-height:70vh;">
    <h1 class="display-1 text-danger">@yield('code')</h1>
    <h3 class="mb-3">@yield('message')</h3>
    <p class="text-muted mb-4">If this problem repeats, please contact the administrator.</p>

    <a href="{{ tenant_route('tenant.dashboard') }}" class="btn btn-primary">
        <i class="bi bi-house"></i> Dashboard
    </a>
</div>
@endsection
