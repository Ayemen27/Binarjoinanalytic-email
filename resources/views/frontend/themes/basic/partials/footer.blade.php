<!-- Start Go Up -->
<div class="go-up">
    <i class="fa fa-chevron-up"></i>
</div>
@if (getSetting('enable_cookie'))
    <div class="cookies" id="cookieConsent">
        <h5>{{ translate('Do you accept cookies?', 'general') }}</h5>
        <p class="cookies-text my-3">
            {{ translate(
                'We use cookies to enhance your browsing experience. By using this site, you consent to our cookie policy.',
                'general',
            ) }}
        </p>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary w-50 me-3" id="acceptCookie">{{ translate('I Accept', 'general') }}</button>
            <a href="{{ getSetting('cookie_policy') }}" class="btn btn-danger w-50">{{ translate('More', 'general') }}</a>
        </div>
    </div>
@endif

<!-- Start Footer -->
<footer class="footer">
    <!-- Start Footer Upper -->
    <div class="footer-upper">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 align-items-center g-4">
                <div class="col d-flex justify-content-center justify-content-sm-start">
                    <a href="{{ route('index') }}" class="logo">
                        <img src="{{ asset(getSetting('dark_logo')) }}" alt="{{ getSetting('site_name') }}" />
                    </a>
                </div>
                <div class="col d-flex justify-content-center justify-content-sm-end">
                    <!-- Start Footer Links -->
                    <div class="footer-links">
                        @foreach ($menus as $menu)
                            <a @if ($menu->is_external) target="_blank" @endif
                                href="{{ $menu->url }}">{!! $menu->icon !!}
                                {{ $menu->name }}</a>
                        @endforeach
                    </div>
                    <!-- End Footer Links -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Upper -->
    <!-- Start Footer Lower -->
    <div class="footer-lower">
        <div class="container">
            <p class="text-center mb-0 lead">
                {!! replacePlaceholders(translate('copyright', 'general'), [
                    'copyright_year' => '<span data-year></span>',
                    'site_name' => getSetting('site_name'),
                ]) !!}
            </p>
        </div>
    </div>
    <!-- End Footer Lower -->
</footer>
<!-- End Footer -->
