<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- CSRF Token S --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- CSRF Token E --}}

  <title>@yield('title', config('app.name')) - Laravel</title>
  <meta name="description" content="@yield('description', 'Laravel-BBS')">
  <meta name="keyword" content="@yield('keyword', 'Laravel-BBS,社区,论坛,开发者论坛')">
  <link rel="shortcut icon" href="/favicon.png">

  {{-- Styles S --}}
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @yield('styles')
  {{-- Styles E --}}
</head>

<body>
<div id="app" class="{{ route_class() }}-page">
  @include('layouts._header')

  <main class="lbs-main">
    @include('layouts._message')
    @yield('content')
  </main>

  @include('layouts._footer')
</div>

{{-- Scripts S --}}
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
{{-- Scripts E --}}
</body>

</html>
