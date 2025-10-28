@extends('admin.layouts.auth')
@section('title', 'Login')
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
                    <h3 class="mb-2">{{ __('Login') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('Continue to ') . getSetting('site_name') }}
                    </p>
                </div>
                <form action="{{ route('admin.login') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <x-input name="email" label="email" autofocus="autofocus" type="email" required
                                value="{{ old('email') }}" />
                        </div>

                        @if (is_demo())
                            <div class="col">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <div class="input-group input-button input-password mb-3">
                                    <x-input name="password" type="password" required value="123456789" />
                                    <button type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="col">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <div class="input-group input-button input-password mb-3">
                                    <x-input name="password" type="password" required />
                                    <button type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="col small">
                            <div class="row row-cols-auto justify-content-between g-2">
                                <div class="col">
                                    <div class="form-check">
                                        <input value="1" class="form-check-input" type="checkbox" name="remember"
                                            id="formCheckChecked" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="formCheckChecked">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <a href="{{ route('admin.password.request') }}">{{ __('Forgot Password?') }}</a>
                                </div>
                            </div>
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
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Lobage Sign -->
@endsection
