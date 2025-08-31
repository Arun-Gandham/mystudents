@extends('auth.auth-layout')

@section('content')
<div style="max-width:420px;margin:60px auto;padding:24px;border:1px solid #eee;border-radius:12px;">
    <h2 style="margin-bottom:6px;">Login — {{ $school->name }}</h2>
    <div style="color:#666;margin-bottom:16px;">Subdomain: <strong>{{ $school->domain }}</strong></div>

    @if ($errors->any())
        <div style="color:#b00020;margin-bottom:12px;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.login.perform') }}">
        @csrf
        <div style="margin-bottom:12px;">
            <label>Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus
                   style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
        </div>
        <div style="margin-bottom:12px;">
            <label>Password</label>
            <input name="password" type="password" required
                   style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
        </div>
        <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <button type="submit" style="width:100%;padding:10px;border:none;border-radius:8px;background:#0d6efd;color:white;">
            Login
        </button>
    </form>
</div>
@endsection
