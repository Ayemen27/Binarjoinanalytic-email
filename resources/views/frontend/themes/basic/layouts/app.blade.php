<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', getCurrentLang()) }}">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEOMeta::generate(true) !!}
    {!! OpenGraph::generate(true) !!}
    {!! Twitter::generate(true) !!}
    <link rel="icon" type="image/png" href="{{ asset(getSetting('favicon')) }}" />
    <link rel="apple-touch-icon" href="{{ asset(getSetting('favicon')) }}" />
    <!-- Main Stylesheet (Critical for Layout) - Always Load -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/themes/basic/css/style.css?v=' . env('SITE_VERSION')) }}" />
    <!-- Preload Critical CSS Files -->
    <link rel="preload" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" as="style">
    <!-- Asynchronously Load Non-Critical CSS Files -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900&display=swap"
        media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome.min.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/select2.min.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/aos.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/flasher.min.css') }}" media="print"
        onload="this.media='all'">

    <!-- Additional Styles -->
    <link rel="preload" href="{{ asset('assets/themes/basic/css/style.css?v=' . env('SITE_VERSION')) }}"
        as="style">

    <style>
    </style>

    @if (getCurrentLangDirection())
        <link rel="preload" href="{{ asset('assets/css/vendor/bootstrap.rtl.min.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('assets/themes/basic/css/rtl.css?v=' . env('SITE_VERSION')) }}"
            type="text/css">
    @endif

    <link rel="alternate" hreflang="x-default"
        href="{{ Str::replace('/' . getCurrentLang(), '', url()->current()) }}" />
    @foreach (getAllLanguages() as $lang)
        @if (empty(request()->segment(2)))
            @if (getCurrentLang() == 'en' && !getSetting('hide_default_lang'))
                <link rel="alternate" hreflang="{{ $lang->code }}"
                    href="{{ Str::replace('/' . getCurrentLang(), '/' . $lang->code, url()->current()) }}" />
            @else
                <link rel="alternate" hreflang="{{ $lang->code }}"
                    href="{{ Str::replace(env('APP_URL'), env('APP_URL') . '/' . $lang->code, url()->current()) }}" />
            @endif
        @else
            @if (getCurrentLang() == 'en' && !getSetting('hide_default_lang'))
                <link rel="alternate" hreflang="{{ $lang->code }}"
                    href="{{ Str::replace('/' . getCurrentLang() . '/', '/' . $lang->code . '/', url()->current()) }}" />
            @else
                <link rel="alternate" hreflang="{{ $lang->code }}"
                    href="{{ Str::replace(env('APP_URL') . '/', env('APP_URL') . '/' . $lang->code . '/', url()->current()) }}" />
            @endif
        @endif
    @endforeach

    @include('partials.plugins')

    @if (!empty($custom_css))
        <style>
            {!! $custom_css !!}
        </style>
    @endif

</head>

<body>

    @if (getSetting('enable_preloader'))
        <div class="preloader">
            <div class="spinner"></div>
        </div>
    @endif

    @include('partials.body')
    @yield('header')
    @yield('content')
    @include('frontend.themes.basic.partials.footer')


    @if ($ad = ad('sticky_ad'))
        <div class='sticky-adlobage' id='sticky-adlobage'>
            <div class='sticky-adlobage-close'
                onclick='document.getElementById("sticky-adlobage").style.display="none"'>
                <svg viewbox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'>
                    <path
                        d='M278.6 256l68.2-68.2c6.2-6.2 6.2-16.4 0-22.6-6.2-6.2-16.4-6.2-22.6 0L256 233.4l-68.2-68.2c-6.2-6.2-16.4-6.2-22.6 0-3.1 3.1-4.7 7.2-4.7 11.3 0 4.1 1.6 8.2 4.7 11.3l68.2 68.2-68.2 68.2c-3.1 3.1-4.7 7.2-4.7 11.3 0 4.1 1.6 8.2 4.7 11.3 6.2 6.2 16.4 6.2 22.6 0l68.2-68.2 68.2 68.2c6.2 6.2 16.4 6.2 22.6 0 6.2-6.2 6.2-16.4 0-22.6L278.6 256z' />
                </svg>
            </div>
            <div class='sticky-adlobage-content'>
                {!! $ad !!}
            </div>
        </div>
    @endif


    <!-- jQuery - Essential for most scripts, load first -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap Bundle - Essential for layout, load immediately -->
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>

    <!-- Load Non-Critical Scripts Asynchronously -->
    <script src="{{ asset('assets/js/vendor/lodash.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/clipboard.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/select2.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/moment.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/qrcode.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/axios.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/aos.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/progress.js') }}" defer></script>
    <!-- Flasher Library - Load at the end -->
    <script src="{{ asset('assets/js/vendor/flasher.min.js') }}" defer></script>
    <!-- Main Script - Typically responsible for page interaction -->
    <script src="{{ asset('assets/themes/basic/js/main.js?v=10' . env('SITE_VERSION')) }}" defer></script>
    <script>
        var BASE_PATH = "{{ url('/') }}/";
        var landing = "{{ trim(translate('landing')) }}",
            search_history_message = "{{ trim(translate('Not found emails with')) }}",
            history_is_empty_message = "{{ trim(translate('History is empty')) }}",
            active_message = "{{ trim(translate('Active')) }}",
            current_message = "{{ trim(translate('Current')) }}",
            choose_message = "{{ trim(translate('Choose')) }}",
            email_changed_message = "{{ trim(translate('The Email has been successfully updated', 'alerts')) }}",
            email_deleted_message = "{{ trim(translate('The Email has been removed', 'alerts')) }}",
            favorited = "{{ trim(translate('Remove from favorites')) }}",
            not_favorited = "{{ trim(translate('Add to favorites')) }}",
            please_wait = "{{ trim(translate('Please Wait')) }}",
            new_message = "{{ trim(translate('New')) }}",
            icon_path = "{{ asset(getSetting('favicon')) }}",
            fetch_time = "{{ getSetting('fetch_messages') }}",
            limit_error = "{{ trim(translate('Too many requests, Please slow down', 'alerts')) }}",
            flasher_error = "{{ trim(translate('Error')) }}",
            flasher_success = "{{ trim(translate('Success')) }}";
    </script>
    <script src="{{ asset('assets/themes/basic/js/app.js?v=1' . env('SITE_VERSION')) }}" defer></script>
    @if (!empty($custom_js))
        <script>
            {!! $custom_js !!}
        </script>
    @endif
    @stack('scripts')
</body>

</html>
