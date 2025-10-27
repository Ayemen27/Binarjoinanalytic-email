@extends('frontend.user.layouts.auth')
@section('title', 'Forgot Password')
@section('content')
    <!-- Start Password Reset Page -->
    <!-- Sign Page -->
    <div class="sign-page">
        <div class="sign-box">
            <div class="box cp-1">
                <a href="{{ url('/') }}" class="logo logo-sm mb-3">
                    <img src="{{ asset(getSetting('logo')) }}" alt="{{ getSetting('site_name') }}" />
                </a>
                <div class="mb-4">
                    <h3 class="mb-2">{{ translate('Reset Your Password', 'auth') }}</h3>
                    <p class="text-muted mb-0">
                        {{ translate('Enter your email address and we will send you a link to reset your password.', 'auth') }}
                    </p>
                </div>

                @if (session('status'))
                    {{ showToastr(session('status')) }}
                @endif

                <form action="{{ route('password.email') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <x-input name="email" label="{{ translate('Email Address', 'auth') }}" type="email"
                                required />
                        </div>
                        @if (getSetting('captcha_rest_password'))
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
                                {{ translate('Send Password Reset Link', 'auth') }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-0 mt-3 small text-muted text-center">
                    {{ translate('Remembered your password?', 'auth') }}
                    <a href="{{ route('login') }}">{{ translate('Log in here', 'auth') }}</a>
                </p>
            </div>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Password Reset Page -->
@endsection
