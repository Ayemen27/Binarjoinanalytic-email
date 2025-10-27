<nav class="dashboard-nav">
    <div class="dashboard-nav-btn dashboard-btn dashboard-toggle-btn">
        <i class="fa fa-bars"></i>
    </div>
    @if (request()->routeIs('dashboard'))
        <p class="dashboard-welcome-message">{{ $welcomeUser }} </p>
    @endif
    <div class="d-flex align-items-center gap-3 ms-auto">

        <div class="drop-down user-menu" data-dropdown>
            <div class="drop-down-btn">
                <img src="{{ asset(userAuth()->avatar) }}" class="user-img" alt="avatar">
            </div>
            <!-- Dropdown Menu -->
            <div class="drop-down-menu">
                <div class="d-flex align-items-center gap-2 pt-2 pb-3">

                    <a href="{{ route('settings') }}">
                        <img src="{{ asset(userAuth()->avatar) }}" class="user-img" alt="avatar">
                    </a>
                    <div>
                        <a href="{{ route('settings') }}">
                            <h6 class="user-title text-dark mb-1">{{ userAuth()->getFullName() }}</h6>
                        </a>
                        <p class="user-text mb-0 text-muted">{{ userAuth()->email }}</p>
                    </div>
                </div>
                <!-- Dropdown Item -->
                <a href="{{ route('settings') }}" class="drop-down-item">
                    <i class="fa fa-cog"></i> {{ translate('Account Settings') }}
                </a>
                <!-- /Dropdown Item -->
                <!-- Dropdown Item -->
                <a href="#" class="drop-down-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i> {{ translate('Logout') }}
                </a>

                <!-- Hidden form to handle the logout request -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <!-- /Dropdown Item -->
            </div>
            <!-- /Dropdown Menu -->
        </div>
    </div>
</nav>
