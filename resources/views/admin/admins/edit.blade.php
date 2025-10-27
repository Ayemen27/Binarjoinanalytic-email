@extends('admin.layouts.admin')
@section('content')
    <x-breadcrumb title='Edit Admin' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.admins.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
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
                                            value="{{ is_demo() ? 'Hidden in demo' : $admin->email }}" />
                                    </div>
                                    <div class="col">
                                        <x-label name="password" for="password" />
                                        <div class="input-group">
                                            <x-input name='password' :show-errors="false" aria-label="Recipient's username"
                                                aria-describedby="button-addon2" />
                                            <button id="gen_btn" class="btn btn-outline-primary" type="button"
                                                id="button-addon2">{{ __('Generate!') }}</button>
                                        </div>
                                        <x-error name="password" />
                                        <small class="d-block form-text text-muted w-100">
                                            {{ __("leave empty if you don't want to change it") }}
                                        </small>
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
