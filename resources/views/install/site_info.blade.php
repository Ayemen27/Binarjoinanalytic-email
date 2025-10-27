@extends('install.layout')
@section('title', 'Site Information')

@section('content')
    <div class="steps-content">
        <div class="steps-body">
            <div class="col-lg-9 col-xl-8 col-xxl-6 mx-auto">
                <div class="mb-4">
                    <h2 class="fw-light mb-4">{{ __('Site Information') }}</h2>
                    <p class="fw-light text-muted mx-auto mb-0">
                        {{ __('Please enter the details for your site and admin account.') }}
                    </p>
                </div>
                <div class="text-start">
                    <form action="{{ route('install.siteInfo.post') }}" method="POST">
                        @csrf
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <x-input label="{{ __('Site Name') }}" name="site_name" required />
                            </div>
                            <div class="col">
                                <x-input label="{{ __('Site URL') }}" name="site_url" value="{{ url('/') }}"
                                    required />
                            </div>
                            <div class="col">
                                <x-label name="Admin Path" for="slug" />
                                <div class="form-group form-group-md">
                                    <label class="form-group-text slug_label">{{ url('/') }}/</label>
                                    <input type="text" class="form-control form-control-md" id="slug" value="admin"
                                        required name="admin_path">

                                </div>
                                <x-error name="admin_path" />
                            </div>

                            <div class="col">
                                <x-input label="{{ __('Admin Email') }}" name="admin_email" type="email" required />
                            </div>

                            <div class="col">
                                <label class="form-label" for="password">{{ __('Admin Password') }}</label>
                                <div class="input-group input-button input-password mb-3">
                                    <x-input name="admin_password" type="password" required />
                                    <button type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <button class="btn btn-primary btn-md w-100">{{ __('Continue') }} <i
                                        class="fas fa-arrow-right"></i></button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
