@extends('tenant.baselayout')

@section('title', 'Page Not Found')

@section('content')
<div class="container-fluid d-flex flex-column justify-content-center align-items-center text-center" style="min-height:70vh;">
    <h1 class="display-1 text-danger">404</h1>
    <h3 class="mb-3">Page Not Found</h3>
    <p class="text-muted mb-4">Sorry, the page you are looking for doesn’t exist or may have been moved.</p>

    <div class="d-flex gap-2">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Go Back
        </a>
    </div>
</div>
@endsection
