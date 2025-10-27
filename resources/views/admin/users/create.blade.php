@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Add New User' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.users.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col mb-3">
                                        <label class="form-label">{{ __('Avatar') }}</label>
                                        <div class="upload-image circle">
                                            <input id="uploadImageInput" type="file" accept="image/*" hidden
                                                name="avatar">
                                            <label for="uploadImageInput">{{ __('Click to Upload') }}</label>
                                            <img src="">
                                        </div>
                                        <x-error name="avatar" />
                                    </div>
                                    <div class="col col-sm-6">
                                        <x-input name='firstname' required label="First Name" />
                                    </div>
                                    <div class="col col-sm-6">
                                        <x-input name='lastname' required label="Last Name" />
                                    </div>
                                    <div class="col">
                                        <x-input type="email" name='email' required label="Email" />
                                    </div>
                                    <div class="col">
                                        <x-label name="password" for="password" />
                                        <div class="input-group">
                                            <x-input :show-errors="false" name='password' required
                                                aria-label="Recipient's username" aria-describedby="button-addon2" />
                                            <button id="gen_btn" class="btn btn-outline-primary" type="button"
                                                id="button-addon2">{{ __('Generate!') }}</button>
                                        </div>
                                        <x-error name="password" />
                                    </div>

                                    <div class="col col-sm-6">
                                        <x-label name="Account Status" for="account_status" />
                                        <select class="select-input" name="account_status" id="account_status">
                                            <option value="1" {{ old('account_status') == '1' ? 'selected' : '' }}>
                                                {{ __('Active') }}
                                            </option>
                                            <option value="0" {{ old('account_status') == '0' ? 'selected' : '' }}>
                                                {{ __('Banned') }}
                                            </option>
                                        </select>
                                        <x-error name="account_status" />
                                    </div>

                                    <div class="col col-sm-6">
                                        <x-label name="Email Status" for="email_status" />
                                        <select class="select-input" name="email_status" id="email_status">
                                            <option value="1" {{ old('email_status') == '1' ? 'selected' : '' }}>
                                                {{ __('Verified') }}
                                            </option>
                                            <option value="0" {{ old('email_status') == '0' ? 'selected' : '' }}>
                                                {{ __('Unverified') }}
                                            </option>
                                        </select>
                                        <x-error name="email_status" />
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
