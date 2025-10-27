@extends('install.layout')
@section('title', 'Database Information')
@section('content')
    <div class="steps-content">
        <div class="steps-body">
            <div class="col-lg-9 col-xl-8 col-xxl-6 mx-auto">
                <div class="mb-4">
                    <h2 class="fw-light mb-4">{{ __('Database Information') }}</h2>
                    <p class="fw-light text-muted mx-auto mb-0">
                        {{ __('Please provide your database connection details to continue the installation.') }}
                    </p>
                </div>
                <div class="text-start">
                    <form action="{{ route('install.databaseInfo.post') }}" method="POST">
                        @csrf
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <x-input label="{{ __('Database Host') }}" name="db_host" value="localhost" required />
                            </div>
                            <div class="col">
                                <x-input label="{{ __('Database Name') }}" name="db_name" required />
                            </div>
                            <div class="col">
                                <x-input label="{{ __('Database Username') }}" name="db_username" />
                            </div>

                            <div class="col">
                                <label class="form-label" for="password">{{ __('Database Password') }}</label>
                                <div class="input-group input-button input-password mb-3">
                                    <x-input name="db_password" type="password" />
                                    <button type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col">
                                <button class="btn btn-primary btn-md w-100">{{ __('Continue') }} <i
                                        class="fas fa-arrow-right"></i></button>
                            </div>
                            @error('error')
                                <div class="col">
                                    <div class="alert alert-danger">
                                        {{ $errors->first('error') }}
                                    </div>
                                </div>
                            @enderror
                        </div>
                    </form>
                    <div class="mt-3">
                        <div class="alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">
                            <strong>{{ __('Note:') }}</strong>
                            {{ __('Do not use these characters `#"\' in any of the database fields.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
