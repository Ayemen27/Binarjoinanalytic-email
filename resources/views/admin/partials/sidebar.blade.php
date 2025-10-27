<aside class="dashboard-sidebar">
    <div class="overlay"></div>
    <div class="dashboard-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-sm">
            <img src="{{ asset(getSetting('logo')) }}" alt="Dashboard">
        </a>
    </div>
    <div class="dashboard-sidebar-inner">
        <div class="dashboard-sidebar-content">
            <!-- Dashboard Sidebar Body -->
            <div class="dashboard-sidebar-body">
                <!-- Dashboard Sidebar Links -->
                <div class="dashboard-sidebar-links">
                    <p class="dashboard-sidebar-links-title">{{ __('Admin') }}</p>

                    <div
                        class="dashboard-sidebar-link {{ request()->segment(2) == '' || request()->segment(2) == 'dashboard' ? 'current' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-house-chimney"></i>
                            <span>{{ __('Dashboard') }}</span>
                        </a>
                    </div>
                    <div class="dashboard-sidebar-link {{ request()->segment(2) == 'domains' ? 'current' : '' }}">
                        <a href="{{ route('admin.domains.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fa-solid fa-globe"></i>
                            <span>{{ __('Domains') }}</span>
                        </a>
                    </div>
                    <div class="dashboard-sidebar-link {{ request()->segment(2) == 'users' ? 'current' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-users"></i>
                            <span>{{ __('Users') }}</span>
                        </a>
                    </div>

                    <div class="dashboard-sidebar-link {{ request()->segment(2) == 'pages' ? 'current' : '' }}">
                        <a href="{{ route('admin.pages.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-file-alt"></i>
                            <span>{{ __('Pages') }}</span>
                        </a>
                    </div>

                    <div class="dashboard-sidebar-link {{ request()->segment(2) == 'plugins' ? 'current' : '' }}">
                        <a href="{{ route('admin.plugins.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-puzzle-piece"></i>
                            <span>{{ __('Plugins') }}</span>
                        </a>
                    </div>

                    <div class="dashboard-sidebar-link {{ request()->segment(2) == 'themes' ? 'current' : '' }}">
                        <a href="{{ route('admin.themes.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-swatchbook"></i>
                            <span>{{ __('Themes') }}</span>
                        </a>
                    </div>

                    <div class="dashboard-sidebar-link dashboard-toggle
                    @if (request()->segment(2) == 'features' ||
                            request()->segment(2) == 'faqs' ||
                            request()->segment(2) == 'menus' ||
                            request()->segment(2) == 'sections') active animated @endif "
                        data-toggle>
                        <!-- Dashboard Sidebar Link Title -->
                        <div class="dashboard-sidebar-link-title toggle-title">
                            <i class="fa-regular fa-rectangle-list"></i>
                            <span>{{ __('Sections') }}</span>
                        </div>
                        <!-- /Dashboard Sidebar Link Title -->
                        <!-- Dashboard Sidebar Link Menu -->
                        <div class="dashboard-sidebar-link-menu">

                            <div
                                class="dashboard-sidebar-link {{ request()->segment(2) == 'features' ? 'current' : '' }}">
                                <a href="{{ route('admin.features.index') }}" class="dashboard-sidebar-link-title">
                                    <i class="fas fa-lightbulb"></i>
                                    <span>{{ __('Features') }}</span>
                                </a>
                            </div>

                            <div class="dashboard-sidebar-link {{ request()->segment(2) == 'faqs' ? 'current' : '' }}">
                                <a href="{{ route('admin.faqs.index') }}" class="dashboard-sidebar-link-title">
                                    <i class="far fa-question-circle"></i>
                                    <span>{{ __('Faqs') }}</span>
                                </a>
                            </div>

                            <div
                                class="dashboard-sidebar-link {{ request()->segment(3) == 'header' ? 'current' : '' }}">
                                <a href="{{ route('admin.menus.header') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-square-caret-up"></i>
                                    <span>{{ __('Header Menus') }}</span>
                                </a>
                            </div>

                            <div
                                class="dashboard-sidebar-link {{ request()->segment(3) == 'footer' ? 'current' : '' }}">
                                <a href="{{ route('admin.menus.footer') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-square-caret-down"></i>
                                    <span>{{ __('Footer Menus') }}</span>
                                </a>
                            </div>

                            <div
                                class="dashboard-sidebar-link {{ request()->segment(3) == 'sidebar' ? 'current' : '' }}">
                                <a href="{{ route('admin.menus.sidebar') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-bars-staggered"></i>
                                    <span>{{ __('Sidebar Menus') }}</span>
                                </a>
                            </div>

                            <div
                                class="dashboard-sidebar-link {{ request()->segment(2) == 'sections' ? 'current' : '' }}">
                                <a href="{{ route('admin.sections.index') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>{{ __('Sections') }}</span>
                                </a>
                            </div>
                        </div>
                        <!-- /Dashboard Sidebar Link Menu -->
                    </div>
                    <p class="dashboard-sidebar-links-title">{{ __('Blog') }}</p>
                    <div class="dashboard-sidebar-link {{ request()->segment(3) == 'categories' ? 'current' : '' }}">
                        <a href="{{ route('admin.blog.categories.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-th-large"></i>
                            <span>{{ __('Categories') }}</span>
                        </a>
                    </div>
                    <div class="dashboard-sidebar-link {{ request()->segment(3) == 'posts' ? 'current' : '' }}">
                        <a href="{{ route('admin.blog.posts.index') }}" class="dashboard-sidebar-link-title">
                            <i class="fas fa-newspaper"></i>
                            <span>{{ __('Posts') }}</span>
                        </a>
                    </div>
                </div>
                <!-- /Dashboard Sidebar Links -->
            </div>
            <!-- /Dashboard Sidebar Body -->
            <!-- Dashboard Sidebar Footer -->
            <div class="dashboard-sidebar-footer mt-auto">
                <!-- Dashboard Sidebar Links -->
                <div class="dashboard-sidebar-links p-0">
                    <div class="dashboard-sidebar-link {{ request()->segment(2) == 'settings' ? 'current' : '' }}">
                        <!-- Dashboard Sidebar Link Title -->
                        <a href="{{ route('admin.settings.general') }}" class="dashboard-sidebar-link-title ">
                            <i class="fa-solid fa-gear"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                        <!-- /Dashboard Sidebar Link Title -->
                    </div>
                    <div class="dashboard-sidebar-link">
                        <!-- Dashboard Sidebar Link Title -->
                        <a href="{{ config('lobage.support') }}" class="dashboard-sidebar-link-title" target="_black">
                            <i class="fa-regular fa-life-ring"></i>
                            <span>{{ __('Support') }}</span>
                            @if (getSetting('is_support_expired'))
                                <div class="dashboard-sidebar-badge ms-auto bg-red text-color-white">
                                    {{ __('Expired') }}
                                </div>
                            @endif
                        </a>
                        <!-- /Dashboard Sidebar Link Title -->
                    </div>
                </div>
                <!-- /Dashboard Sidebar Links -->
            </div>
            <!-- /Dashboard Sidebar Footer -->
        </div>
    </div>
</aside>
