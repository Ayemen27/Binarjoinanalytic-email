<div class="header-wrapper">
    <div class="container-fluid">
        <!-- Start Header Title -->
        <h1 class="header-title">
            {!! replacePlaceholders(translate('Homepage Title'), [
                'site_name' => getSetting('site_name'),
            ]) !!}
        </h1>
        <!-- End Header Title -->
        <!-- Start Header Text -->
        <p class="header-text lead">
            {!! replacePlaceholders(translate('Homepage first description'), [
                'site_name' => getSetting('site_name'),
            ]) !!}

        </p>
        <!-- End Header Text -->
        <!-- Start Mail Wrapper -->
        <div class="mail-wrapper">
            @if ($ad = ad('header_right'))
                <div class="ad ad-250x250">
                    {!! $ad !!}
                </div>
            @endif

            <!-- Start Mail Selection -->
            <div class="mail-selection mb-3">
                <!-- Start Mail Select -->
                <div class="mail-select" data-clipboard-target="#mainEmail">
                    <!-- Start Mail Input -->
                    <div class="mail-input">
                        <input id="mainEmail" type="text" value="{{ translate('landing') }}" readonly
                            aria-label="{{ translate('landing') }}" />
                        <input id="email_token" hidden value="" />
                        <!-- Start Mail Input Copy -->
                        <button class="mail-input-copy btn btn-secondary disable-button btn-copy" disabled
                            data-clipboard-target="#mainEmail" aria-label="Copy email address">
                            <i class="far fa-clone" id="copyIcon"></i>
                        </button>
                        <!-- End Mail Input Copy -->
                    </div>
                    <!-- End Mail Input -->
                </div>
                <!-- End Mail Select -->
                <!-- Start Mail Actions -->
                <div class="mail-actions">
                    <!-- Start Mail Action -->
                    <button id="refresh" class="mail-action btn btn-light btn-md"
                        aria-label="{{ translate('Refresh') }}">
                        <i class="fas fa-redo-alt"></i> <span class="mail-action-text">{{ translate('Refresh') }}</span>
                    </button>
                    <button disabled class="mail-action btn btn-light btn-md kill request-button disable-button"
                        id='delete' aria-label="{{ translate('Delete') }}">
                        <i class="fa fa-trash"></i> <span class="mail-action-text">{{ translate('Delete') }}</span>
                    </button>
                    <!-- End Mail Action -->
                    <!-- Start Mail Action -->
                    <button disabled class="mail-action btn btn-light btn-md disable-button" id="show_qr_code"
                        aria-label="{{ translate('QR Code') }}">
                        <i class="fa-solid fa-qrcode"></i> <span
                            class="mail-action-text">{{ translate('QR Code') }}</span>
                    </button>
                    <!-- End Mail Action -->
                    <!-- Start Mail Action -->
                    <button disabled class="mail-action btn btn-light btn-md disable-button" id="show_history"
                        aria-label="{{ translate('History') }}">
                        <i class="fa-solid fa-clock-rotate-left"></i> <span
                            class="mail-action-text">{{ translate('History') }}</span>
                    </button>
                    <!-- End Mail Action -->
                    <!-- Start Mail Action -->
                    <button disabled class="mail-action btn btn-light btn-md disable-button" data-bs-toggle="modal"
                        data-bs-target="#changeEmail" id="change_email_btn" aria-label="{{ translate('Change') }}">
                        <i class="fa-solid fa-pen-to-square"></i> <span
                            class="mail-action-text">{{ translate('Change') }}</span>
                    </button>
                    <!-- End Mail Action -->
                </div>
                <!-- End Mail Actions -->
            </div>
            <!-- End Mail Selection -->
            @if ($ad = ad('header_right'))
                <div class="ad ad-250x250">
                    {!! $ad !!}
                </div>
            @endif

        </div>
        <!--  ads('header_bottom', 'ad-h mx-auto my-4') -->
        @if ($ad = ad('header_bottom'))
            <div class="ad ad-h mx-auto mb-4">
                {!! $ad !!}
            </div>
        @endif

        <p class="header-text lead">
            {{ translate('Homepage second description') }}
        </p>
        <!-- Start Header Counters -->
        <div class="header-counters mt-4">
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <div class="col">
                    <!-- Start Header Counter -->
                    <div class="header-counter">
                        <!-- Start Header Counter Info -->
                        <div class="header-counter-info">
                            <!-- Start Header Counter Number -->
                            <p class="header-counter-number">{{ $emails_created }}</p>
                            <!-- End Header Counter Number -->
                            <!-- Start Header Counter Title -->
                            <p class="header-counter-title">{{ translate('Emails Created') }}</p>
                            <!-- End Header Counter Title -->
                        </div>
                        <!-- End Header Counter Info -->
                        <!-- Start Header Counter Icon -->
                        <div class="header-counter-icon">
                            <i class="fa-solid fa-at"></i>
                        </div>
                        <!-- End Header Counter Icon -->
                    </div>
                    <!-- End Header Counter -->
                </div>
                <div class="col">
                    <!-- Start Header Counter -->
                    <div class="header-counter">
                        <!-- Start Header Counter Info -->
                        <div class="header-counter-info">
                            <!-- Start Header Counter Number -->
                            <p class="header-counter-number">{{ $messages_created }}</p>
                            <!-- End Header Counter Number -->
                            <!-- Start Header Counter Title -->
                            <p class="header-counter-title">{{ translate('Messages Received') }}</p>
                            <!-- End Header Counter Title -->
                        </div>
                        <!-- End Header Counter Info -->
                        <!-- Start Header Counter Icon -->
                        <div class="header-counter-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <!-- End Header Counter Icon -->
                    </div>
                    <!-- End Header Counter -->
                </div>
            </div>
            <!-- End Header Counters -->
        </div>
    </div>
</div>

<!-- ShareThis BEGIN -->

@include('frontend.themes.basic.sections.qrcode')
@include('frontend.themes.basic.sections.change')
@include('frontend.themes.basic.sections.history')

@if (isPluginEnabled('recaptcha_invisible'))
    <div id='recaptcha' class="g-recaptcha" data-tabindex="100" data-sitekey="{{ env('ROCAPTCHA_SITEKEY_INVISIBLE') }}"
        data-callback="myCallback" data-size="invisible">
    </div>
    <input type="hidden" id="captcha-response" name="captcha-response" />

    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>

        <script>
            window.check_recaptcha = false;
            var onloadCallback = function() {
                grecaptcha.execute();
            };
        </script>
    @endpush
@else
    @push('scripts')
        <script>
            window.check_recaptcha = true;
        </script>
    @endpush
@endif
