@extends('superadmin.layouts.layout1')

@section('title', $pageTitle ?? "Super Admin")
@section('description', $pageDescription ?? "Super Admin")
@section('content')
  @yield('content')
  @stack('scripts')
  @stack('modals')
  <x-toast-container />

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toastEl => {
                const toast = new bootstrap.Toast(toastEl, { delay: 5000 }); // 5s
                toast.show();
            });
        });
    </script>

    <style>
        .custom-toast {
            opacity: 0.95; /* transparent */
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .toast.hide {
            opacity: 0;
            transform: translateX(100%); /* slide out to right */
        }
    </style>
@endsection

