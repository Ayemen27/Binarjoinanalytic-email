<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') {{ config('seotools.meta.defaults.separator') }} {{ getSetting('site_name') }} </title>
    <link rel="icon" type="image/png" href="{{ asset(getSetting('favicon')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=' . env('SITE_VERSION')) }}" />

    @stack('styles')

</head>

<body>
    @yield('content')
    <!-- General JS Scripts -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js?v=' . env('SITE_VERSION')) }}"></script>
    @stack('scripts')
</body>

</html>
