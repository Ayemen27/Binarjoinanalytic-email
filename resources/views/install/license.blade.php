@extends('install.layout')
@section('title', 'License')
@section('content')
    <div class="steps-content">
        <div class="steps-body">
            <div class="col-lg-9 col-xl-8 col-xxl-6 mx-auto">
                <div class="mb-4">
                    <h2 class="fw-light mb-4">{{ __('License') }}</h2>
                    <p class="fw-light text-muted mx-auto mb-0">
                        {{ __('Please enter your purchase code to proceed with the installation.') }}
                    </p>
                </div>
                <div class="text-start">
                    <form action="{{ route('install.license.post') }}" method="POST">
                        @csrf
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <x-input label="{{ __('Purchase Code') }}" name="purchase_code"
                                    placeholder="{{ __('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx') }}" />
                            </div>

                            <div class="col">
                                <button class="btn btn-primary btn-md w-100">{{ __('Continue') }} <i
                                        class="fas fa-arrow-right"></i></button>
                            </div>
                            @error('message')
                                <div class="col">
                                    <div class="alert alert-danger">
                                        {!! $errors->first('message') !!}
                                        @if (session('action'))
                                            <br>{{ __('Change Your Domain ') }} <a href="{{ config('lobage.support') }}"
                                                target="_blank">{{ __('Here') }} </a>
                                        @endif
                                    </div>
                                </div>
                            @enderror
                        </div>
                    </form>
                    <div class="mt-3">
                        @if(env('LICENSE_MOCK_MODE', false) === true || env('LICENSE_MOCK_MODE', false) === 'true')
                        <div class="alert alert-info mt-3" role="alert">
                            <strong><i class="fas fa-code"></i> {{ __('وضع المحاكاة مُفعّل') }}</strong><br>
                            <p class="mb-0">أنت تستخدم وضع المحاكاة للتطوير. يمكنك إدخال أي رمز شراء وسيتم قبوله تلقائياً.</p>
                        </div>
                        @endif
                        <div class="alert alert-warning mt-3" role="alert">
                            <strong>{{ __('Notice:') }}</strong><br>
                            <ul>
                                <li>{{ __('Your license is valid for one domain, including all associated subdomains.') }}
                                </li>
                                <li>{{ __('By submitting, you agree to') }} <a
                                        href="https://codecanyon.net/licenses/terms/regular"
                                        target="_blank">{{ __('the license policy') }}</a>.</li>
                            </ul>
                        </div>
                        <h5>{{ __('Helpful Links') }}</h5>
                        <ul>
                            <li>
                                <a target="_blank"
                                    href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code">{{ __('Where Is My Purchase Code?') }}</a>
                            </li>
                            <li>
                                <a target="_blank"
                                    href="https://help.market.envato.com/hc/en-us/articles/208191263-What-is-Item-Support">{{ __('What is Item Support?') }}
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="{{ config('lobage.support') }}">{{ __('Chnage Your Domain') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
