@extends('tenant.baselayout')

@section('title', 'Server Error')

@section('content')
<div class="container-fluid d-flex flex-column justify-content-center align-items-center text-center" style="min-height:70vh;">
    <h1 class="display-1 text-warning">500</h1>
    <h3 class="mb-3">Oops! Something went wrong</h3>
    <p class="text-muted mb-4">
        An unexpected error occurred on the server.  
        Please try again later or contact your administrator if the problem continues.
    </p>

</div>
@endsection
