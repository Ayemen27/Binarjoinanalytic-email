@extends('admin.layouts.admin')
@section('title', 'Profile')
@section('content')
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box mb-3">
                            <h5 class="mb-4">{{ __('Profile') }}</h5>
                            <form action="{{ route('admin.settings.profile.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col mb-3">
                                        <label class="form-label">{{ __('Avatar') }}</label>
                                        <div class="upload-image circle">
                                            <input id="uploadImageInput" type="file" accept="image/*" hidden
                                                name="avatar">
                                            <label for="uploadImageInput">{{ __('Click to Upload') }}</label>
                                            <img src="{{ asset($admin->avatar) }}" / class="d-block">
                                        </div>
                                        <x-error name="avatar" />
                                    </div>
                                    <div class="col col-sm-6">
                                        <x-input name='firstname' required label="First Name"
                                            value="{{ $admin->firstname }}" />
                                    </div>
                                    <div class="col col-sm-6">
                                        <x-input name='lastname' required label="Last Name"
                                            value="{{ $admin->lastname }}" />
                                    </div>
                                    <div class="col">
                                        <x-input type="email" name='email' required label="Email"
                                            value="{{ $admin->email }}" />
                                    </div>
                                    <div class="col">
                                        <x-button class="w-100" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box">
                            <h5 class="mb-4">{{ __('Security') }}</h5>
                            <form action="{{ route('admin.settings.password.update') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">

                                    <div class="col">
                                        <label class="form-label"
                                            for="current_password">{{ __('Current Password') }}</label>
                                        <div class="input-group input-button input-password">
                                            <x-input name="current_password" type="password" required />
                                            <button type="button">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label class="form-label" for="password">{{ __('New Password') }}</label>
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
                                            for="password_confirmation">{{ __('Confirm Password') }}</label>
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
