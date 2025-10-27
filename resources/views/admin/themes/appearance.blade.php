@extends('admin.layouts.admin')
@section('title', 'Appearance')
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
            <form action="{{ route('admin.themes.appearance.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="box">
                    <div class="row row-cols-1 g-3">
                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">{{ __('Main Logo') }}</label>
                            <div class="upload-image">
                                <input id="uploadImageInput1" type="file" hidden name="logo" accept="image/*">
                                <label for="uploadImageInput1">{{ __('Click to Upload') }}</label>
                                <img src="{{ asset($theme->logo) }}" class="d-block">
                            </div>
                            <x-error name="logo" />
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">{{ __('Dark Logo') }}</label>
                            <div class="upload-image dark_logo">
                                <input id="uploadImageInput2" type="file" hidden name="dark_logo" accept="image/*">
                                <label for="uploadImageInput2">{{ __('Click to Upload') }}</label>
                                <img src="{{ asset($theme->dark_logo) }}" class="d-block">
                            </div>
                            <x-error name="dark_logo" />
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">{{ __('Favicon') }}</label>
                            <div class="upload-image">
                                <input id="uploadImageInput3" type="file" hidden name="favicon" accept="image/*">
                                <label for="uploadImageInput3">{{ __('Click to Upload') }}</label>
                                <img src="{{ asset($theme->favicon) }}" class="d-block">
                            </div>
                            <x-error name="favicon" />
                        </div>
                        @foreach ($theme->colors as $key => $value)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }} </label>
                                <div class="input-group colorPicker" data-color="{{ $value }}">
                                    <input name="{{ $key }}" type="text" class="form-control form-control-md"
                                        value="{{ $value }}">
                                    <button class="btn btn-default" type="button">&nbsp;</button>
                                </div>
                                <x-error name="{{ $key }}" />
                            </div>
                        @endforeach
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
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/izoColorPicker.css') }}">
@endpush


@push('libraies')
    <script src="{{ asset('assets/js/vendor/izoColorPicker.js') }}"></script>
@endpush
