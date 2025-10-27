<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEOMeta::generate(true) !!}
    {!! OpenGraph::generate(true) !!}
    {!! Twitter::generate(true) !!}
    <link rel="icon" type="image/png" href="{{ asset(getSetting('favicon')) }}" />
    <link rel="apple-touch-icon" href="{{ asset(getSetting('favicon')) }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" />
    @if (getCurrentLangDirection())
        <link rel="preload" href="{{ asset('assets/css/vendor/bootstrap.rtl.min.css') }}" as="style">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=' . env('SITE_VERSION')) }}" />

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

    <!-- Dashboard -->
    <div class="dashboard">
        <!-- Dashboard Sidebar -->
        @include('frontend.user.partials.sidebar')
        <!-- /Dashboard Sidebar -->
        <!-- Dashboard Body -->
        <div class="dashboard-body">
            <!-- Dashboard Nav -->
            @include('frontend.user.partials.nav')
            <!-- /Dashboard Nav -->

            <div class="dashboard-container">
                @yield('content')
            </div>

            <!-- Dashboard Footer -->
            @include('frontend.user.partials.footer')
            <!-- /Dashboard Footer -->
        </div>
        <!-- /Dashboard Body -->
    </div>
    <!-- /Dashboard -->
    <!-- General JS Scripts -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery.nestable.js') }}"></script>


    @stack('libraies')
    <script type="text/javascript">
        "use strict";
        const BASE_URL = "{{ url('/') }}";
    </script>
    <script src="{{ asset('assets/js/main.js?v=' . env('SITE_VERSION')) }}"></script>
    <!-- Template JS File -->
    @stack('scripts')

    @if (!empty($custom_js))
        <script>
            {!! $custom_js !!}
        </script>
    @endif
</body>

</html>
