@extends('admin.layouts.admin')

@section('content')
    <div class="lobage-container">
        <!-- Start Lobage Page Header -->
        <x-breadcrumb title='Settings' />
        <!-- End Lobage Page Header -->
        <!-- Start Lobage Page Body -->
        <div class="lobage-page-body">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-3">
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.settings.general') }}" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-gear"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">General Settings</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Site Name, Default Language ....</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.settings.advanced') }}" class="lobage-card incomplete">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <!-- End Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Advanced Settings</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">IMAP , System settings ... </p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.settings.email') }}" class="lobage-card incomplete">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Email Settings</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Email SMTP settings</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.themes.appearance') }}" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-paint-roller"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Appearance</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">logo , colors ...</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.admins.index') }}" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Admins</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Add, Edit and Delete Admins</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.settings.seo.index') }}" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-ranking-star"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Seo</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Search engine optimization</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.settings.seo.index') }}" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fas fa-language"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Languages</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Add, Edit and Delete languages</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="{{ route('admin.settings.ads.index') }}" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-rectangle-ad"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Ads</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Earn money from advertising</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fas fa-blog"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Blog Settings</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Enable/Disable / Popular posts</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Captcha Settings</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Enhance site security</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>


                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="#" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-route"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Redirects</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Redirect links to new links</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>
                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="#" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-plug"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">API</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Connect applications via API</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="#" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">Cron Job</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Mange your Cron Job</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="#" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">License</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">Manage your License</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="#" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">About Us</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">How To use , Help Center....</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>

                <div class="col">
                    <!-- Start Lobage Card -->
                    <a href="#" class="lobage-card">
                        <!-- Start Lobage Card Body -->
                        <div class="lobage-card-body">
                            <!-- Start Lobage Card Icon -->
                            <div class="lobage-card-icon">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <!-- En Lobage Card Icon -->
                            <!-- Start Lobage Card Title -->
                            <h6 class="lobage-card-title">About Us</h6>
                            <!-- End Lobage Card Title -->
                            <!-- Start Lobage Card Text -->
                            <p class="lobage-card-text">How To use , Help Center....</p>
                            <!-- End Lobage Card Text -->
                        </div>
                        <!-- End Lobage Card Body -->
                    </a>
                    <!-- End Lobage Card -->
                </div>


            </div>
        </div>

        <!-- End Lobage Page Body -->
    </div>
@endsection
