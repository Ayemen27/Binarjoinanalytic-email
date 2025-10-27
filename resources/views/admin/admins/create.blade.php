@extends('admin.layouts.admin')
@section('content')
    <x-breadcrumb title='Add New Admin' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.admins.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <div class="input-group mb-3">
                                            <x-input :show-errors="false" name='password' required
                                                aria-label="Recipient's username" aria-describedby="button-addon2" />
                                            <button id="gen_btn" class="btn btn-outline-primary" type="button"
                                                id="button-addon2">{{ __('Generate!') }}</button>
                                        </div>
                                        <x-error name="password" />
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
