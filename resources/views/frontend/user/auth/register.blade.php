@extends('frontend.user.layouts.auth')
@section('title', 'Register')
@section('content')
    <!-- Start Registration Page -->
    <!-- Sign Page -->
    <div class="sign-page">
        <div class="sign-box">
            <div class="box cp-1">
                <a href="{{ url('/') }}" class="logo logo-sm mb-3">
                    <img src="{{ asset(getSetting('logo')) }}" alt="{{ getSetting('site_name') }}" />
                </a>
                <div class="mb-4">
                    <h3 class="mb-2">{{ translate('Create Your Account', 'auth') }}</h3>
                    <p class="text-muted mb-0">
                        {{ translate('Please fill in the details below to create your account.', 'auth') }}
                    </p>
                </div>
                <form action="{{ route('register') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        <div class="col col-sm-6">
                            <x-input name="firstname" label="{{ translate('First Name', 'auth') }}" autofocus="autofocus" />
                        </div>

                        <div class="col col-sm-6">
                            <x-input name="lastname" label="{{ translate('Last Name', 'auth') }}" />
                        </div>

                        <div class="col">
                            <x-input name="email" label="{{ translate('Email Address', 'auth') }}" type="email"
                                required />
                        </div>

                        <div class="col">
                            <label class="form-label" for="password">{{ translate('Password', 'auth') }}</label>
                            <div class="input-group input-button input-password">
                                <x-input name="password" type="password" required autocomplete="new-password" />
                                <button type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        @if (getSetting('captcha_register'))
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
                                {{ translate('Register', 'auth') }}
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
                            <span>{{ translate('Or sign up with', 'auth') }}</span>
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
                <p class="mb-0  mt-3 small text-muted text-center">
                    {{ translate('Already have an account?', 'auth') }}
                    <a href="{{ route('login') }}">{{ translate('Log in here', 'auth') }}</a>
                </p>
            </div>
            <div class="terms">
                <p class="mb-5 mt-3 small text-muted text-center">
                    {!! replacePlaceholders(translate('sign up agreement', 'auth'), [
                        'terms' => '<a href="' . getSetting('terms_of_service') . '">' . translate('Terms of Service', 'auth') . '</a>',
                        'privacy' => '<a href="' . getSetting('privacy_policy') . '">' . translate('Privacy Policy', 'auth') . '</a>',
                    ]) !!}
                </p>
            </div>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Registration Page -->
@endsection
