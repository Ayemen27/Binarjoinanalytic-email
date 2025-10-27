@extends('frontend.user.layouts.auth')
@section('title', 'Verify Email')
@section('content')
    <!-- Start Email Verification Page -->
    <!-- Sign Page -->
    <div class="sign-page">
        <div class="sign-box">
            <div class="box cp-1 text-center">
                <div class="mb-3 verify-img">
                    <img class="img-width-170" src="{{ asset('assets/img/verify.svg') }}" />
                </div>
                <h3>{{ translate('Email Verification Required', 'auth') }}</h3>
                <p class="text-muted mb-3">
                    {{ translate('Please verify your email address to continue.', 'auth') }}
                </p>
                @if (session('resent'))
                    {{ showToastr(translate('A fresh verification link has been sent to your email address.', 'auth')) }}
                @endif
                <hr>
                <p class="text-muted mb-3">
                    {{ translate('Didn’t receive the email?', 'auth') }}
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-md w-100">
                        {{ translate('request another', 'auth') }}
                    </button>
                </form>
                </p>
            </div>
            <p class="mb-0 mt-3 small text-muted text-center">
                {{ translate('You want to ', 'auth') }}
                <a href="{{ route('logout') }}">{{ translate('logout?', 'auth') }}</a>
            </p>
        </div>
    </div>
    <!-- /Sign Page -->
    <!-- End Email Verification Page -->
@endsection
