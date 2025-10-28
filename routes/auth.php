<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::namespace('Frontend')->middleware('maintenance')->group(function () {
    // Use Auth::routes but disable the ones we'll define manually
    Auth::routes(['verify' => true, 'login' => false, 'register' => false, 'reset' => false, 'confirm' => false, 'logout' => false]);

    // Authentication Routes
    Route::namespace('Auth')->middleware(['mcamara', 'checkBan'])->prefix(LaravelLocalization::setLocale())->group(function () {
        Route::middleware(['guest'])->group(function () {
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login')->middleware('trustip');
            Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email')->middleware('demo');
            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
        });

        // Email Verification Routes
        Route::middleware(['auth:web', 'demo'])->group(function () {
            Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
            Route::post('email/verify/email/change', 'VerificationController@changeEmail')->name('change.email');
            Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
            Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
        });

        // Registration Routes
        Route::middleware(['checkRegister'])->group(function () {
            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register')->middleware('trustip');
        });
        
        // Logout Route
        Route::post('logout', 'LoginController@logout')->name('logout');
    });

    // Social Authentication Routes
    Route::namespace('Auth')->group(function () {
        Route::get('auth/{provider}', 'SocialiteController@redirectToProvider')->name('social.login')->middleware(['demo', 'trustip']);
        Route::get('auth/{provider}/callback', 'SocialiteController@handleProviderCallback')->name('social.callback');
    });

    Route::middleware(['mcamara', 'checkBan', 'auth:web', 'verified', 'demo'])->prefix(LaravelLocalization::setLocale())->group(function () {
        Route::get('/profile', "ProfileController@index")->name('profile');
        Route::post('/profile/update', "ProfileController@update")->name('profile.update');
        Route::post('/profile/password/update', "ProfileController@changePassword")->name('profile.password.update');
    });
});
