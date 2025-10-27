<!-- Start Nav -->
<nav class="nav">
    <div class="container">
        <!-- Start Nav Inner -->
        <div class="nav-inner">
            <!-- Start Logo -->
            <a href="{{ route('index') }}" class="logo ">
                <img class="white-logo" src="{{ asset(getSetting('dark_logo')) }}" alt="{{ getSetting('site_name') }}" />
                <img class="dark-logo" src="{{ asset(getSetting('logo')) }}" alt="{{ getSetting('site_name') }}" />
            </a>
            <!-- End Logo -->
            <!-- Start Nav Actions SM -->
            <div class="nav-actions-sm">
                @if (getAllLanguages()->count() > 1)

                    <!-- Start Language -->
                    <div class="language me-4" dropdown>
                        <!-- Start Language Button -->
                        <div class="language-button">
                            <i class="fa-solid fa-language"></i>
                        </div>

                        <!-- End Language Button -->
                        <div class="language-menu" data-simplebar>
                            @foreach (getAllLanguages() as $language)
                                <a class="@if (getCurrentLang() == $language->code) active @endif" rel="alternate"
                                    hreflang="{{ $language->code }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($language->code, null, [], true) }}">
                                    <div class="language-img">
                                        <img alt="{{ $language->code }}"
                                            src="{{ asset('assets/img/flags/' . $language->code . '.png') }}">
                                    </div>
                                    <span class="language">{{ $language->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- End Language -->
                @endif
                <!-- Start Nav Menu Button -->
                <div class="nav-menu-button">
                    <i class="fa fa-bars fa-lg"></i>
                </div>
                <!-- End Nav Menu Button -->
            </div>
            <!-- End Nav Actions SM -->
            <!-- Start Nav Menu -->
            <div class="nav-menu">
                <div class="overlay"></div>
                <div class="nav-menu-scroller">
                    <div class="nav-menu-body">
                        <div class="row row-cols-auto justify-content-between flex-nowrap mb-5 d-xl-none">

                            <div class="col col ms-auto">
                                <div class="nav-menu-close">
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                        </div>

                        <div class="nav-actions">
                            @if (getAllLanguages()->count() > 1)
                                <!-- Start Language -->
                                <div class="language d-none d-xl-flex me-2" dropdown>
                                    <!-- Start Language Button -->
                                    <div class="language-button">
                                        <i class="fa-solid fa-language"></i>
                                    </div>
                                    <!-- End Language Button -->
                                    <div class="language-menu" data-simplebar>
                                        @foreach (getAllLanguages() as $language)
                                            <a class="@if (getCurrentLang() == $language->code) active @endif" rel="alternate"
                                                hreflang="{{ $language->code }}"
                                                href="{{ LaravelLocalization::getLocalizedURL($language->code, null, [], true) }}">
                                                <div class="language-img">
                                                    <img alt="{{ $language->code }}"
                                                        src="{{ asset('assets/img/flags/' . $language->code . '.png') }}">
                                                </div>
                                                <span class="language">{{ $language->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>

                                </div>
                                <!-- End Language -->
                            @endif

                            <div class="nav-links">
                                @foreach ($menus as $menu)
                                    @if (SubMenus($menu->id)->count() == 0)
                                        <a @if ($menu->is_external) target="_blank" @endif
                                            href="{{ $menu->url }}" class="nav-link">{!! $menu->icon !!}
                                            {{ $menu->name }}</a>
                                    @else
                                        <div class="nav-drop" dropdown>
                                            <div class="nav-drop-btn nav-link">
                                                {{ $menu->name }}
                                                <i class="fas fa-chevron-down fa-sm ms-2 me-2 me-xl-0"></i>
                                            </div>
                                            <div class="nav-drop-menu mt-2">
                                                @foreach (SubMenus($menu->id) as $submenu)
                                                    <a @if ($submenu->is_external) target="_blank" @endif
                                                        href="{{ $submenu->url }}"
                                                        class="nav-link ">{!! $submenu->icon !!}
                                                        {{ $submenu->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>

                            @auth
                                <a class="btn btn-secondary btn-md" href="{{ route('dashboard') }}">
                                    {{ translate('Dashboard', 'general') }}
                                </a>
                            @endauth
                            @guest
                                @if (getSetting('enable_registration'))
                                    <a class="btn btn-secondary btn-md" href="{{ route('register') }}">
                                        {{ translate('Register', 'general') }}
                                    </a>
                                    <a class="btn btn-outline-light btn-md" href="{{ route('login') }}">
                                        {{ translate('Login', 'general') }}
                                    </a>
                                @else
                                    <a class="btn btn-secondary btn-md" href="{{ route('login') }}">
                                        {{ translate('Login', 'general') }}
                                    </a>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Nav Menu -->
        </div>
        <!-- End Nav Inner -->
    </div>
</nav>
<!-- End Nav -->
