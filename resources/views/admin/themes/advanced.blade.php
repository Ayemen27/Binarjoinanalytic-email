@extends('admin.layouts.admin')
@section('title', 'Custom Css & Js')
@section('content')
    <!-- Settings -->
    <div class="settings">
        <div class="d-xl-none">
            <select class="form-select form-select-md" id="goToValue">
                <option value="{{ route('admin.themes.appearance') }}"
                    {{ request()->segment(3) == 'appearance' ? 'selected' : '' }}>{{ __('Appearance') }}</option>
                <option value="{{ route('admin.themes.advanced') }}"
                    {{ request()->segment(3) == 'advanced' ? 'selected' : '' }}>{{ __('Custom Css & Js') }}</option>
                <option value="{{ route('admin.themes.about') }}" {{ request()->segment(3) == 'about' ? 'selected' : '' }}>
                    {{ __('About Theme') }}</option>
            </select>
        </div>
        <!-- Settings Side -->
        <div class="settings-side d-none d-xl-block">
            <div class="box p-0 overflow-hidden">
                <a href="{{ route('admin.themes.appearance') }}"
                    class="settings-link {{ request()->segment(3) == 'appearance' ? 'current' : '' }}">
                    <div class="settings-link-icon">
                        <i class="fa-solid fa-paint-roller"></i>
                    </div>
                    <div class="settings-link-info">
                        <h6 class="settings-link-title">{{ __('Appearance') }}</h6>
                    </div>
                </a>

                <a href="{{ route('admin.themes.advanced') }}"
                    class="settings-link {{ request()->segment(3) == 'advanced' ? 'current' : '' }}">
                    <div class="settings-link-icon">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div class="settings-link-info">
                        <h6 class="settings-link-title">{{ __('Custom Css & Js') }}</h6>
                    </div>
                </a>

                <a href="{{ route('admin.themes.about') }}"
                    class="settings-link {{ request()->segment(3) == 'about' ? 'current' : '' }}">
                    <div class="settings-link-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="settings-link-info">
                        <h6 class="settings-link-title">{{ __('About Theme') }}</h6>
                    </div>
                </a>
            </div>
        </div>
        <!-- /Settings Side -->
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <form action="{{ route('admin.themes.advanced.update') }}" method="POST">
                @csrf
                <div class="box">
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <label class="form-label">{{ __('Custom Css') }}</label>
                            <textarea type="text" class="codeeditor" rows="6" name="custom_css">{{ $theme->custom_css }}</textarea>
                            <x-error name="code" />
                        </div>

                        <div class="col">
                            <label class="form-label">{{ __('Custom Js') }}</label>
                            <textarea type="text" class="codeeditor" rows="6" name="custom_js">{{ $theme->custom_js }}</textarea>
                            <x-error name="code" />
                        </div>

                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/dracula_theme.css') }}">
@endpush


@push('libraies')
    <script src="{{ asset('assets/js/vendor/codemirror.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/codemirror_javascript.js') }}"></script>
@endpush
