<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
    @if($school?->favicon_url)
    <link rel="icon" href="{{ asset('storage/'.$school->favicon_url) }}?v={{ time() }}" type="image/x-icon">
    @else
      <link rel="icon" href="{{ asset('images/default-favicon.png') }}?v={{ time() }}" type="image/png">
    @endif
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
  @yield('content')
</body>
</html>
