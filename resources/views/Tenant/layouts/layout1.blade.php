<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $pageTitle ?? 'School')</title>
  <meta name="description" content="@yield('description', $pageDescription ?? '')">

  {{-- Favicon --}}
  @if($school?->favicon_url)
    <link rel="icon" href="{{ asset('storage/'.$school->favicon_url) }}?v={{ time() }}" type="image/x-icon">
  @else
    <link rel="icon" href="{{ asset('images/default-favicon.png') }}?v={{ time() }}" type="image/png">
  @endif

  {{-- Assets --}}
  @vite([
      'resources/css/tenant-base.css',
      'resources/js/tenant-base.js'
  ])
  @stack('styles')
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

  @stack('scripts')
</body>
</html>
