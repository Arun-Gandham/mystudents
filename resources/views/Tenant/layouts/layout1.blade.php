<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ !empty($school?->favicon_url) ? asset('storage/'.$school->favicon_url) : asset('images/default-favicon.png') }}">
  <title>@yield('title' ?? 'School')</title>
  <meta name="description" content="{{ $pageDescription ?? 'Description' }}">

  {{-- Assets --}}
  @vite([
      'resources/css/tenant-base.css',
      'resources/js/tenant-base.js'
  ])
  @stack('styles')
  <style>
    .custom-toast {
        opacity: 0.95;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .toast.hide {
        opacity: 0;
        transform: translateX(100%);
    }
  </style>
</head>
<body>
  <div class="app-layout">
    {{-- Sidebar left --}}
    <aside class="app-sidebar">
      @include('tenant.partials.sidebar')
    </aside>

    {{-- Right side: header + content --}}
    <div class="app-main">
        @include('tenant.partials.header')

      <main class="app-content">
        @yield('content')
      </main>
    </div>
  </div>
  <x-toast-container />
  @stack('scripts')
  <script>
      document.addEventListener("DOMContentLoaded", function () {
          const toasts = document.querySelectorAll('.toast');
          toasts.forEach(toastEl => {
              const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
              toast.show();
          });
      });
  </script>
</body>
</html>
