@extends('admin.layouts.admin')
@section('title', 'About Theme')
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

            @csrf
            <div class="box">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <img class="w-100" src="{{ $theme->image }}" />
                    </div>

                    <div class="col">
                        <div class="text-center">
                            <h2>{{ $theme->name }}</h2>
                            <p>{{ $theme->description }}</p>

                            <p>version : {{ $theme->version }}</p>

                        </div>
                    </div>

                    <div class="col">
                        <a href="{{ route('admin.plugins.settings', $theme->unique_name) }}"
                            class="btn btn-secondary btn-md fw-medium w-100"><i class="fas fa-eye mx-1"></i>
                            {{ __('Demo') }}</a>
                    </div>

                </div>
            </div>

            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
