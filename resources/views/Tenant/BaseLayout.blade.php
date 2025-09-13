@extends('tenant.layouts.layout1')
@section('favicon')
  @if(!empty($school?->favicon_url))
    <link rel="icon" href="{{ asset('storage/'.$school->favicon_url) }}?v={{ time() }}" type="image/png">
  @else
    <link rel="icon" href="{{ asset('images/default-favicon.png') }}?v={{ time() }}" type="image/png">
  @endif
@endsection
@section('title', $pageTitle ?? "School")
@section('description', $pageDescription ?? "School")

{{-- Main page content --}}
@section('content')
  @yield('content')
@endsection

{{-- Toast container always included, outside content --}}
<x-toast-container />

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toastEl => {
            const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
            toast.show();
        });
    });
</script>
@endpush

@push('styles')
<style>
    .custom-toast {
        opacity: 0.95; /* slightly transparent */
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .toast.hide {
        opacity: 0;
        transform: translateX(100%); /* fade out right */
    }
</style>
@endpush
