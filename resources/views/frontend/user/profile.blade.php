@extends('frontend.user.layouts.app')

@section('content')
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box mb-3">
                            <h5 class="mb-4">{{ translate('Profile', 'auth') }}</h5>
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col mb-3">
                                        <label class="form-label">{{ translate('Avatar', 'auth') }}</label>
                                        <div class="upload-image circle">
                                            <input id="uploadImageInput" type="file" accept="image/*" hidden
                                                name="avatar">
                                            <label for="uploadImageInput">{{ translate('Click to Upload', 'auth') }}</label>
                                            <img src="{{ asset($user->avatar) }}" / class="d-block">
                                        </div>
                                        <x-error name="avatar" />
                                    </div>
                                    <div class="col col-sm-6">
                                        <x-input name='firstname' required label="{{ translate('First Name', 'auth') }}"
                                            value="{{ $user->firstname }}" />
                                    </div>
                                    <div class="col col-sm-6">
                                        <x-input name='lastname' required label="{{ translate('Last Name', 'auth') }}"
                                            value="{{ $user->lastname }}" />
                                    </div>
                                    <div class="col">
                                        <x-input type="email" name='email' required
                                            label="{{ translate('Email', 'auth') }}" value="{{ $user->email }}" />
                                    </div>
                                    <div class="col">
                                        <x-button class="w-100" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box">
                            <h5 class="mb-4">{{ translate('Security', 'auth') }}</h5>
                            <form action="{{ route('profile.password.update') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">

                                    <div class="col">
                                        <label class="form-label"
                                            for="current_password">{{ translate('Current Password', 'auth') }}</label>
                                        <div class="input-group input-button input-password">
                                            <x-input name="current_password" type="password" required />
                                            <button type="button">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label class="form-label"
                                            for="password">{{ translate('New Password', 'auth') }}</label>
                                        <div class="input-group input-button input-password">
                                            <x-input :show-errors="false" name="password" type="password" required />
                                            <button type="button">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <x-error name="password" />
                                    </div>
                                    <div class="col">
                                        <label class="form-label"
                                            for="password_confirmation">{{ translate('Confirm Password', 'auth') }}</label>
                                        <div class="input-group input-button input-password">
                                            <x-input name="password_confirmation" type="password" required />
                                            <button type="button">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <x-button class="w-100" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
