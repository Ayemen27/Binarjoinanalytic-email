@extends('frontend.user.layouts.auth')
@section('title', 'Login')
@section('content')
    <!-- Start Login Page -->
    <!-- Sign Page -->
    <div class="sign-page">



        <div class="sign-box">
            @if (is_demo())
                <div class="box cp-1 mb-3">
                    <div class="table-inner">
                        <div class="table-responsive">
                            <p class="text-center">
                                {{ __('You can create your own account or log in with the demo account.') }}</p>
                            <table class="table table-hover align-middle table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ __('Email') }}</th>
                                        <th class="text-center">{{ __('Password') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            {{ __('user@demo.com') }}
                                        </td>
                                        <td class="text-center">
                                            {{ __('123456789') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="box cp-1">

                <a href="{{ url('/') }}" class="logo logo-sm mb-3">
                    <img src="{{ asset(getSetting('logo')) }}" alt="{{ getSetting('site_name') }}" />
                </a>
                <div class="mb-4">
                    <h3 class="mb-2">{{ translate('Welcome Back!', 'auth') }}</h3>
                    <p class="text-muted mb-0">
                        {{ translate('Please log in to your account to continue.', 'auth') }}
                    </p>
                </div>
                @if ($errors->has('ban'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('ban') }}
                    </div>
                @endif
                <form action="{{ route('login') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <x-input name="email" label="{{ translate('Email Address', 'auth') }}" autofocus="autofocus"
                                type="email" required value="{{ old('username') }}" />
                        </div>
                        <div class="col">
                            <label class="form-label" for="password">{{ translate('Password', 'auth') }}</label>
                            <div class="input-group input-button input-password mb-3">
                                <x-input name="password" type="password" required />
                                <button type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col small">
                            <div class="row row-cols-auto justify-content-between g-2">
                                <div class="col">
                                    <div class="form-check">
                                        <input value="1" class="form-check-input" type="checkbox" name="remember"
                                            id="formCheckChecked" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="formCheckChecked">{{ translate('Remember Me', 'auth') }}</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <a
                                        href="{{ route('password.request') }}">{{ translate('Forgot Password?', 'auth') }}</a>
                                </div>
                            </div>
                        </div>

                        @if (getSetting('captcha_login'))
                            @if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha'))
                                <div class="mb-3">
                                    {!! HCaptcha::script() !!}
                                    {!! HCaptcha::display() !!}
                                    <x-error name="h-captcha-response" class="text-start" />
                                </div>
                            @elseif(getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha'))
                                <div class="mb-3">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    <x-error name="g-recaptcha-response" class="text-start" />
                                </div>
                            @endif
                        @endif
                        <div class="col">
                            <button class="btn btn-primary btn-md w-100">
                                {{ translate('Log In', 'auth') }}
                            </button>
                        </div>
                    </div>
                </form>
                @php
                    $facebookLoginEnabled = isPluginEnabled('facebook_login');
                    $googleLoginEnabled = isPluginEnabled('google_login');
                @endphp
                @if ($facebookLoginEnabled || $googleLoginEnabled)
                    <div class="sign-with mt-3">
                        <div class="sign-with-divider">
                            <span>{{ translate('Or log in with', 'auth') }}</span>
                        </div>
                        @if ($facebookLoginEnabled)
                            <a href="{{ route('social.login', 'facebook') }}" class="btn btn-facebook btn-md w-100 mb-2">
                                <i class="fab fa-facebook me-2"></i>
                                {{ translate('Continue with Facebook', 'auth') }}
                            </a>
                        @endif
                        @if ($googleLoginEnabled)
                            <a href="{{ route('social.login', 'google') }}" class="btn btn-google btn-md w-100">
                                <i class="fab fa-google me-2"></i>
                                {{ translate('Continue with Google', 'auth') }}
                            </a>
                        @endif
                    </div>
                @endif
                <p class="mb-0 mt-3 small text-muted text-center">
                    {{ translate('Don’t have an account?', 'auth') }}
                    <a href="{{ route('register') }}">{{ translate('Sign up now', 'auth') }}</a>
                </p>
            </div>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Login Page -->
@endsection
