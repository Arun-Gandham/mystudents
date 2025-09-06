<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $pageTitle ?? 'Dashboard')</title>
  <meta name="description" content="@yield('description', $pageDescription ?? '')">
  @if($school?->favicon_url)
    <link rel="icon" 
          href="{{ asset('storage/'.$school->favicon_url) }}?v={{ time() }}" 
          type="image/x-icon">
  @else
      <link rel="icon" href="{{ asset('images/default-favicon.png') }}?v={{ time() }}" type="image/png">
  @endif
  <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
  @vite([
      'resources/css/tenant-base.css',
      'resources/js/tenant-base.js'
  ])
  @stack('styles')
</head>
<body>
@include('tenant.partials.header')

<div id="appContainer" class="app-shell">
  @include('tenant.partials.sidebar')
  <main class="content-scroll">
    @yield('content')
  </main>
</div>

@stack('scripts')
</body>
</html>