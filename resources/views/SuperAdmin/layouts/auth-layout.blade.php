<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $pageTitle ?? 'Dashboard')</title>
  <meta name="description" content="@yield('description', $pageDescription ?? '')">

  <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
  @vite([
      'resources/css/superadmin-base.css',
      'resources/js/superadmin-base.js'
  ])
  @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>