@extends('tenant.layouts.auth-layout')

@section('content')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 960px; min-height: 520px;">

    {{-- Left side: school logo / image --}}
    <div class="col-md-6 d-none d-md-flex bg-light align-items-center justify-content-center p-4">
      <div class="text-center">
        @if(!empty($school->logo_url))
          <img src="{{ asset('storage/'.$school->logo_url) }}" 
               alt="{{ $school->name }} Logo" 
               class="img-fluid mb-3" 
               style="max-height: 200px;">
        @else
          <img src="{{ asset('images/default-logo.png') }}" 
               alt="School Logo" 
               class="img-fluid mb-3" 
               style="max-height: 200px;">
        @endif
        <h3 class="fw-bold">{{ $school->name }}</h3>
        <p class="text-muted mb-0">Welcome to the school portal</p>
      </div>
    </div>

    {{-- Right side: login form --}}
    <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">
      <div class="text-center mb-4">
        <h4 class="fw-bold">{{ $school->name }} Login</h4>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger py-2">
          <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ tenant_route('tenant.login.perform') }}">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input name="email" id="email" type="email" value="{{ old('email') }}" required autofocus
                 class="form-control">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input name="password" id="password" type="password" required class="form-control">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
          </div>
          <a href="#" class="text-decoration-none small">
            Forgot password?
          </a>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
@endsection
