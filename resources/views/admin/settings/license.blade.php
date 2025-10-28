@extends('admin.layouts.admin')
@section('title', 'License Management')
@section('content')
    <div class="container-fluid">
        @if(env('LICENSE_MOCK_MODE', false) === true || env('LICENSE_MOCK_MODE', false) === 'true')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-code"></i> وضع المحاكاة مُفعّل</strong>
            <p class="mb-0">أنت تستخدم وضع المحاكاة للتطوير. لتفعيل التحقق الحقيقي من الترخيص، قم بتعيين <code>LICENSE_MOCK_MODE=false</code> في ملف <code>.env</code></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <!-- Settings -->
                <div class="settings">
                    @include('admin.partials.settings')
                    <!-- Settings Content -->
                    <div class="settings-content w-100">
                        <div class="box">
                            <h5 class="mb-4">{{ __('License Management') }}</h5>


                            @if ($isSupportExpired)
                                <div class="alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">
                                    {{ __(' Your support has expired. Please renew your support to get assistance.') }}
                                </div>
                            @endif

                            <div class="row row-cols-1 g-3 mt-3">
                                <div class="col">
                                    <x-input name='key' readonly label="License Key" value="{{ $jsonData['license'] ?? 'N/A' }}" />
                                </div>
                                <div class="col">
                                    <x-input name='license_type' readonly label="License Type"
                                        value="{{ $jsonData['type'] ?? 'N/A' }}" />
                                </div>
                                <div class="col">
                                    <x-input name='support' readonly label="Support Until"
                                        value="{{ $jsonData['support'] ?? 'N/A' }}" />
                                </div>

                                <form action="{{ route('admin.settings.license.update') }}" method="POST">
                                    @csrf
                                    <div class="col mb-3">
                                        <x-input name='purchase_code' placeholder="{{ __('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx') }}"
                                            label="New License Key (Purchase Code)" />
                                        <small>{{ __("Leave empty if you don't want to change it.") }}</small>
                                    </div>
                                    @error('message')
                                        <div class="col">
                                            <div class="alert alert-danger">
                                                {!! $errors->first('message') !!}
                                                @if (session('action'))
                                                    <br>{{ __('Change Your License ') }} <a href="{{ config('lobage.support') }}"
                                                        target="_blank">{{ __('Here') }} </a>
                                                @endif
                                            </div>
                                        </div>
                                    @enderror

                                    <div class="col">
                                        <x-button class="btn-md w-100">{{ __('Update License') }}</x-button>
                                    </div>
                                </form>


                            </div>
                        </div>
                        <!-- /Settings Content -->
                    </div>
                </div>
                <!-- /Settings -->
            </div>
        </div>
    </div>
@endsection