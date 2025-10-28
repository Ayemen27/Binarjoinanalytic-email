<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title') {{ config('seotools.meta.defaults.separator') }} {{ config('lobage.script_name') }}
    </title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/install.css?v=' . env('SITE_VERSION')) }}" />


</head>

<body>
    <!-- Steps Page -->
    <div class="steps-page">
        <aside class="steps-sidebar">
            <div class="steps-sidebar-header">

            </div>
            <div class="steps-sidebar-body">
                <div
                    class="steps-sidebar-item {{ $currentStep >= 1 ? 'checked' : '' }} {{ $currentStep == 0 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            1
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('Welcome') }}</h6>
                </div>
                <div
                    class="steps-sidebar-item {{ $currentStep >= 2 ? 'checked' : '' }} {{ $currentStep == 1 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            2
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('Requirements') }}</h6>
                </div>
                <div
                    class="steps-sidebar-item {{ $currentStep >= 3 ? 'checked' : '' }}{{ $currentStep == 2 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            3
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('File Permissions') }}</h6>
                </div>

                <div
                    class="steps-sidebar-item {{ $currentStep >= 4 ? 'checked' : '' }}{{ $currentStep == 3 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            4
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('License') }}</h6>
                </div>
                <div
                    class="steps-sidebar-item {{ $currentStep >= 5 ? 'checked current' : '' }}{{ $currentStep == 4 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            5
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('Database Information') }}</h6>
                </div>
                <div
                    class="steps-sidebar-item {{ $currentStep >= 6 ? 'checked' : '' }}{{ $currentStep == 5 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            6
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('Database Import') }}</h6>
                </div>
                <div
                    class="steps-sidebar-item {{ $currentStep >= 7 ? 'checked' : '' }}{{ $currentStep == 6 ? 'active' : '' }}">
                    <div class="steps-sidebar-item-icon">
                        <div class="steps-sidebar-item-number">
                            7
                        </div>
                        <div class="steps-sidebar-item-checked">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <h6 class="steps-sidebar-item-title">{{ __('Site Information') }}</h6>
                </div>
            </div>
            <div class="steps-sidebar-footer mt-auto d-none d-lg-block">
                <a href="{{ config('lobage.support') }}" target="_blank" class="d-block">
                    <i class="fa-regular fa-life-ring me-1"></i>
                    <span>{{ __('Get help') }}</span>
                </a>
            </div>
        </aside>
        @yield('content')
    </div>

    <!-- /Steps Page -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')

</body>

</html>
