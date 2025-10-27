@extends('frontend.themes.basic.layouts.app')

@section('content')
    @include('frontend.themes.basic.partials.header', ['title' => translate('Contact Us', 'general')])
    <!-- Start Section -->
    <section class="section">
        <div class="container">
            <div class="section-inner">

                @if ($ad = ad('mailbox_top'))
                    <div class="ad ad-h mx-auto mb-4">
                        {!! $ad !!}
                    </div>
                @endif
                <div class="viewbox-container">
                    @if ($ad = ad('mailbox_left'))
                        <div class="ad ad-v me-lg-4 mb-4 mb-xl-0">
                            {!! $ad !!}
                        </div>
                    @endif

                    <div class="box-content">
                        <div class="viewbox">
                            <!-- Start Viewbox Header -->
                            <div class="viewbox-header p-3">
                                <h5 class="m-0">{{ translate('Contact Us', 'general') }}</h5>
                            </div>
                            <!-- End Viewbox Header -->
                            <!-- Start Viewbox Body -->
                            <div class="viewbox-body v2 p-4">

                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif

                                @if (isPluginEnabled('contact') &&
                                        plugin('contact')->type->value == 'default' &&
                                        !empty(getSetting('mail_from_address')) &&
                                        !empty(getSetting('mail_to_address')))
                                    <form action="{{ route('contact.store') }}" method="post">
                                        @csrf
                                        <div class="row row-cols-1 row-cols-sm-2 g-3 mb-3">
                                            <div class="col">
                                                <label class="form-label">{{ translate('Full Name', 'general') }}</label>
                                                <input class="form-control form-control-md " type="text" name="fullname"
                                                    required>
                                                <x-error name="fullname" />
                                            </div>
                                            <div class="col">
                                                <label class="form-label">{{ translate('Email', 'general') }}</label>
                                                <input class="form-control form-control-md" type="email" name="email"
                                                    required>
                                                <x-error name="email" />
                                            </div>
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-1 g-3 mb-3">
                                            <div class="col">
                                                <label class="form-label">{{ translate('Subject', 'general') }}</label>
                                                <input class="form-control form-control-md" type="text" name="subject"
                                                    required>
                                                <x-error name="subject" />
                                            </div>
                                            <div class="col">
                                                <label class="form-label">{{ translate('Message', 'general') }}</label>
                                                <textarea class="form-control form-control-md" rows="7" name="message"></textarea>
                                                <x-error name="message" />
                                            </div>
                                        </div>
                                        @if (getSetting('captcha_contact'))
                                            @if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha'))
                                                <div class="mb-3">
                                                    {!! HCaptcha::script() !!}
                                                    {!! HCaptcha::display() !!}
                                                    <x-error name="h-captcha-response" class="text-start" />
                                                </div>
                                            @elseif(getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha'))
                                                <div class="mb-3">
                                                    {!! NoCaptcha::renderJs() !!}
                                                    {!! NoCaptcha::display() !!}
                                                    <x-error name="g-recaptcha-response" class="text-start" />
                                                </div>
                                            @endif
                                        @endif
                                        <button
                                            class="btn btn-primary btn-md w-100">{{ translate('Send Your Message', 'general') }}</button>
                                    </form>
                                @elseif(isPluginEnabled('contact') &&
                                        plugin('contact')->type->value == 'iframe' &&
                                        !empty(plugin('contact')->iframe->value))
                                    {!! plugin('contact')->iframe->value !!}
                                @else
                                    {!! translate('Contact Us Content', 'html') !!}
                                @endif
                            </div>
                        </div>
                        @if ($ad = ad('mailbox_bottom'))
                            <div class="ad ad-h mx-auto mt-3">
                                {!! $ad !!}
                            </div>
                        @endif
                    </div>
                    @if ($ad = ad('mailbox_right'))
                        <div class="ad ad-v ms-lg-4 mt-4 mt-xl-0">
                            {!! $ad !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->
@endsection
