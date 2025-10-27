@extends('admin.layouts.auth')
@section('title', 'Reset Password')
@section('content')
    <!-- Start Lobage Sign -->
    <!-- Sign Page -->
    <div class="sign-page">
        <div class="sign-box">
            <div class="box cp-1">
                <a href="{{ url('/') }}" class="logo logo-sm mb-3">
                    <img src="{{ asset(getSetting('logo')) }}" alt="{{ getSetting('site_name') }}" />
                </a>
                <div class="mb-4">
                    <h3 class="mb-2">{{ __('Reset Password') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('Enter your email to reset your password') }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.password.email') }}">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="col">
                            <x-input name="email" label="email" autofocus="autofocus" type="email" required
                                value="" />
                        </div>
                        @if (getSetting('captcha_admin'))
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
                                {{ __('Continue') }}
                            </button>
                        </div>
                        <p class="mb-0 small text-muted text-center">
                            {{ __('You remember your password ?') }}
                            <a href="{{ route('admin.login') }}">{{ __('Login') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Lobage Sign -->
@endsection
