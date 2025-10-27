<aside class="dashboard-sidebar">
    <div class="overlay"></div>
    <div class="dashboard-sidebar-header">
        <a href="{{ route('index') }}" class="logo logo-sm">
            <img src="{{ asset(getSetting('logo')) }}" alt="Dashboard">
        </a>
    </div>
    <div class="dashboard-sidebar-inner">
        <div class="dashboard-sidebar-content">
            <!-- Dashboard Sidebar Body -->
            <div class="dashboard-sidebar-body">
                <!-- Dashboard Sidebar Links -->
                <div class="dashboard-sidebar-links">
                    <p class="dashboard-sidebar-links-title">{{ translate('Dashboard') }}
                        {{ request()->is('dashboard*') }}</p>
                    <!-- Dashboard Sidebar Link -->
                    <div
                        class="dashboard-sidebar-link {{ request()->segment(1) == 'dashboard' || request()->segment(2) == 'dashboard' ? 'current' : '' }}">
                        <a href="{{ route('dashboard') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-house-chimney"></i>
                            <span>{{ translate('Dashboard') }}</span>
                        </a>
                    </div>

                    <div
                        class="dashboard-sidebar-link {{ request()->segment(1) == 'messages' || request()->segment(2) == 'messages' ? 'current' : '' }}">
                        <a href="{{ route('messages.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fa-solid fa-heart"></i>
                            <span>{{ translate('Favorite Messages') }}</span>
                        </a>
                    </div>

                    <div
                        class="dashboard-sidebar-link {{ request()->segment(1) == 'domains' || request()->segment(2) == 'domains' ? 'current' : '' }}">
                        <a href="{{ route('domains.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fa-solid fa-globe"></i>
                            <span>{{ translate('Domains') }}</span>
                        </a>
                    </div>

                    <div class="dashboard-sidebar-link">
                        <a href="{{ route('index') }}" class="dashboard-sidebar-link-title">
                            <i class="fa-solid fa-inbox"></i>
                            <span>{{ translate('My Inbox') }}</span>
                        </a>
                    </div>
                </div>



                <!-- /Dashboard Sidebar Links -->
            </div>
            <!-- /Dashboard Sidebar Body -->
            <!-- Dashboard Sidebar Footer -->
            <div class="dashboard-sidebar-footer mt-auto">
                <!-- Dashboard Sidebar Links -->
                <div class="dashboard-sidebar-links p-0 ">

                    @foreach ($menus as $menu)
                        <!-- Dashboard Sidebar Link -->
                        <div class="dashboard-sidebar-link">
                            <!-- Dashboard Sidebar Link Title -->
                            <a href="{{ $menu->url }}" @if ($menu->is_external) target="_blank" @endif
                                class="dashboard-sidebar-link-title">
                                {!! $menu->icon !!}
                                <span>{{ $menu->name }}</span>
                            </a>
                            <!-- /Dashboard Sidebar Link Title -->
                        </div>
                        <!-- /Dashboard Sidebar Link -->
                    @endforeach
                    <!-- Dashboard Sidebar Link -->
                    <div
                        class="dashboard-sidebar-link {{ request()->segment(1) == 'settings' || request()->segment(2) == 'settings' ? 'current' : '' }}">
                        <!-- Dashboard Sidebar Link Title -->
                        <a href="{{ route('settings') }}" class="dashboard-sidebar-link-title">
                            <i class="fa-solid fa-gear"></i>
                            <span>{{ translate('Settings') }}</span>
                        </a>
                        <!-- /Dashboard Sidebar Link Title -->
                    </div>
                    <!-- /Dashboard Sidebar Link -->

                </div>




                <!-- /Dashboard Sidebar Links -->

            </div>
            <!-- /Dashboard Sidebar Footer -->
        </div>
    </div>
</aside>
