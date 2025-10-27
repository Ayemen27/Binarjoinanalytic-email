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
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=' . env('SITE_VERSION')) }}" />

    @stack('styles')
</head>

<body>
    <!-- Dashboard -->
    <div class="dashboard">
        <!-- Dashboard Sidebar -->
        @include('admin.partials.sidebar')
        <!-- /Dashboard Sidebar -->
        <!-- Dashboard Body -->
        <div class="dashboard-body">
            <!-- Dashboard Nav -->
            @include('admin.partials.nav')
            <!-- /Dashboard Nav -->

            <div class="dashboard-container">
                @yield('content')
            </div>

            <!-- Dashboard Footer -->
            @include('admin.partials.footer')

            <!-- /Dashboard Footer -->
            @include('admin.partials.whats-new')

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
        const BASE_URL = "{{ url('/' . env('ADMIN_PATH')) }}";
    </script>
    <script src="{{ asset('assets/js/admin.js?v=' . env('SITE_VERSION')) }}"></script>
    <!-- Template JS File -->
    @stack('scripts')
</body>

</html>
