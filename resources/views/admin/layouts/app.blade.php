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

  {{-- Google Fonts S --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
  {{-- Google Fonts E --}}

  {{-- Styles S --}}
  <link rel="stylesheet" href="{{ asset('backend/css/app.css') }}">
  @yield('styles')
  {{-- Styles E --}}
</head>

<body class="voyager">
<div id="voyager-loader" style="left: 125px; display: none;">
  <img src="{{ asset('backend/images/logo-icon.png') }}" alt="Admin Loader">
</div>

<?php
if (starts_with(Auth::user()->avatar, 'http://') || starts_with(Auth::user()->avatar, 'https://')) {
  $userAvatar = Auth::user()->avatar;
} else {
  $userAvatar = Admin::image(Auth::user()->avatar);
}
?>

<div class="app-container">
  <div class="fadetoblack visible-xs"></div>
  <div class="row content-container">
    @include('admin.dashboard.navbar')
    @include('admin.dashboard.sidebar')
    <script>
      (function () {
        var appContainer = document.querySelector('.app-container'),
          sidebar = appContainer.querySelector('.side-menu'),
          navbar = appContainer.querySelector('nav.navbar.navbar-top'),
          loader = document.getElementById('voyager-loader'),
          hamburgerMenu = document.querySelector('.hamburger'),
          sidebarTransition = sidebar.style.transition,
          navbarTransition = navbar.style.transition,
          containerTransition = appContainer.style.transition;

        sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
          appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
            navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

        if (window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
          appContainer.className += ' expanded no-animation';
          loader.style.left = (sidebar.clientWidth / 2) + 'px';
          hamburgerMenu.className += ' is-active no-animation';
        }

        navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
        sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
        appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
      })();
    </script>

    <div class="container-fluid">
      <div class="side-body padding-top">
        @yield('page_header')
        <div id="voyager-notifications"></div>
        @yield('content')
      </div>
    </div>
  </div>
</div>

@include('admin.partials.footer')

{{-- Scripts S --}}
<script src="{{ asset('backend/js/app.js') }}"></script>
<script>
  @if(Session::has('alerts'))
    let alerts = {!! json_encode(Session::get('alerts')) !!};
    helpers.displayAlerts(alerts, toastr);
  @endif

  @if(Session::has('message'))
    // TODO: change Controllers to use AlertsMessages trait... then remove this
    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
    var alertMessage = {!! json_encode(Session::get('message')) !!};
    var alerter = toastr[alertType];

    if (alerter) {
      alerter(alertMessage);
    } else {
      toastr.error("toastr alert-type " + alertType + " is unknown");
    }
  @endif
</script>
@yield('scripts')
{{-- Scripts E --}}
</body>

</html>
