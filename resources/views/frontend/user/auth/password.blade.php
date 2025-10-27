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
                    <h3 class="mb-2">{{ translate('Reset Your Password', 'auth') }}</h3>
                    <p class="text-muted mb-0">
                        {{ translate('Please enter your email address and new password.', 'auth') }}
                    </p>
                </div>
                <form action="{{ route('password.update') }}" method="post" autocomplete="off">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <x-input name="email" label="{{ translate('Email Address', 'auth') }}" type="email" required
                                value="{{ $email ?? old('email') }}" />
                        </div>
                        <div class="col">
                            <x-label for="password" name='{{ translate('New Password', 'auth') }}' />
                            <div class="input-group input-button input-password">
                                <x-input name="password" type="password" :show-errors="false" required
                                    autocomplete="new-password" />
                                <button type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <x-error name="password" />
                        </div>

                        <div class="col">
                            <x-label for="password_confirmation" name='{{ translate('Confirm New Password', 'auth') }}' />
                            <div class="input-group input-button input-password">
                                <x-input name="password_confirmation" type="password" required />
                                <button type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-md w-100">
                                {{ translate('Update Password', 'auth') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Registration Page -->
@endsection
