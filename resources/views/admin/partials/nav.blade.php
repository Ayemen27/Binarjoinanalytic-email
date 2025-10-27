<nav class="dashboard-nav">
    <div class="dashboard-nav-btn dashboard-btn dashboard-toggle-btn">
        <i class="fa fa-bars nav-icon"></i>
    </div>

    <a href="{{ url('/') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Go To Site"
        target="_blank"><i class="fa-solid fa-arrow-up-right-from-square  nav-icon"></i> </a>

    @if (request()->routeIs('admin.dashboard'))
        <p class="dashboard-welcome-message">{{ __('Welcome back,' . adminAuth()->firstname . ' 👋') }} </p>
    @endif
    <div class="d-flex align-items-center gap-2 ms-auto">
        @if (isset($broadcastsCount) && $broadcastsCount > 0)
            <div class="drop-down drop-down-lg offcanvasScrolling" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                @if ($newSlugsCount > 0)
                    <span class="badge-noti" id="broadcast_badge">{{ $newSlugsCount }}</span>
                @endif

                <i class="fa-solid fa-scroll nav-icon"></i>
            </div>
        @endif

        <div class="drop-down drop-down-lg" data-dropdown>

            <div class="drop-down-btn dashboard-nav-btn ">
                @if ($notifications_count > 0)
                    <span class="badge-noti ">{{ $notifications_count }}</span>
                @endif

                <i class="fa-regular fa-bell nav-icon"></i>
            </div>
            <!-- Dropdown Menu -->
            @include('admin.partials.notifications')
            <!-- /Dropdown Menu -->
        </div>

        <div class="drop-down user-menu" data-dropdown>
            <div class="drop-down-btn">
                <img src="{{ asset(adminAuth()->avatar) }}" class="user-img" alt="avatar">
            </div>
            <!-- Dropdown Menu -->
            <div class="drop-down-menu">
                <div class="d-flex align-items-center gap-2 pt-2 pb-3">
                    <a href="{{ route('admin.settings.profile') }}">
                        <img src="{{ asset(adminAuth()->avatar) }}" class="user-img" alt="avatar">
                    </a>
                    <div>
                        <a href="{{ route('admin.settings.profile') }}">
                            <h6 class="user-title text-dark mb-1">{{ adminAuth()->getFullName() }}</h6>
                        </a>
                        <p class="user-text mb-0 text-muted">{{ adminAuth()->email }}</p>
                    </div>
                </div>
                <!-- Dropdown Item -->
                <a href="{{ route('admin.settings.profile') }}" class="drop-down-item">
                    <i class="fa fa-cog"></i> {{ __('Account Settings') }}
                </a>
                <!-- /Dropdown Item -->
                <!-- Dropdown Item -->
                <a href="#" class="drop-down-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i> {{ __('Logout') }}
                </a>

                <!-- Hidden form to handle the logout request -->
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <!-- /Dropdown Item -->
            </div>
            <!-- /Dropdown Menu -->
        </div>
    </div>
</nav>
