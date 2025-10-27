<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset(getSetting('favicon')) }}" />
    <link rel="apple-touch-icon" href="{{ asset(getSetting('favicon')) }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" />
    @if (getCurrentLangDirection())
        <link rel="preload" href="{{ asset('assets/css/vendor/bootstrap.rtl.min.css') }}" as="style">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    @if (getCurrentLangDirection())
        <link rel="stylesheet" href="{{ asset('assets/css/style.rtl.css?v=' . env('SITE_VERSION')) }}" type="text/css">
    @endif
    @stack('styles')

    @include('partials.plugins')

    @if (!empty($custom_css))
        <style>
            {!! $custom_css !!}
        </style>
    @endif

</head>

<body>
    @include('partials.body')
    @yield('content')
    <!-- General JS Scripts -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js?v=' . env('SITE_VERSION')) }}"></script>
    @stack('scripts')

    @if (!empty($custom_js))
        <script>
            {!! $custom_js !!}
        </script>
    @endif

</body>

</html>
