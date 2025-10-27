@extends('admin.layouts.admin')
@section('title', 'Captcha')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('Captcha') }}</h5>
                <div class=" alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">
                    <div class="d-flex">
                        <div>
                            {{ __('Make sure to activate the plugins of your choice') }}
                        </div>
                    </div>
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>

                <form action="{{ route('admin.settings.captcha.update') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <label class="form-label">{{ __('Captcha Provider') }}</label>
                            <select class="select-input" name="captcha">
                                <option {{ getSetting('captcha') == 'none' ? 'selected' : '' }} value="none">
                                    {{ __('None') }}</option>
                                @if (isPluginEnabled('recaptcha'))
                                    <option {{ getSetting('captcha') == 'recaptcha' ? 'selected' : '' }} value="recaptcha">
                                        {{ __('ReCaptcha') }}</option>
                                @else
                                    <option disabled {{ getSetting('captcha') == 'recaptcha' ? 'selected' : '' }}
                                        value="recaptcha">
                                        {{ __('ReCaptcha ( You need to active the plugin)') }}</option>
                                @endif

                                @if (isPluginEnabled('hcaptcha'))
                                    <option {{ getSetting('captcha') == 'hcaptcha' ? 'selected' : '' }} value="hcaptcha">
                                        {{ __('hCaptcha') }}</option>
                                @else
                                    <option disabled {{ getSetting('captcha') == 'hcaptcha' ? 'selected' : '' }}
                                        value="hcaptcha">
                                        {{ __('hCaptcha ( You need to active the plugin)') }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label d-block">{{ __('Enable captcha on') }}: </label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="captcha_login" type="checkbox" id="Login"
                                    value="1" {{ getSetting('captcha_login') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="Login">{{ __('Login page') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="captcha_register" type="checkbox" id="Register"
                                    value="1" {{ getSetting('captcha_register') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="Register">{{ __('Register page') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="captcha_rest_password" type="checkbox" id="Reset"
                                    value="1" {{ getSetting('captcha_rest_password') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="Reset">{{ __('Reset password page') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="captcha_contact" type="checkbox" id="Contact"
                                    value="1" {{ getSetting('captcha_contact') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="Contact">{{ __('Contact page') }}</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="captcha_admin" type="checkbox" id="Admin"
                                    value="1" {{ getSetting('captcha_admin') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="Admin">{{ __('Admin area') }}</label>
                            </div>
                        </div>

                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
