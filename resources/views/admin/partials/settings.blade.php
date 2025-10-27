<div class="d-xl-none">
    <select class="form-select form-select-md" id="goToValue">
        <option value="{{ route('admin.settings.general') }}" {{ request()->segment(3) == 'general' ? 'selected' : '' }}>
            {{ __('General Settings') }}
        </option>
        <option value="{{ route('admin.settings.advanced') }}"
            {{ request()->segment(3) == 'advanced' ? 'selected' : '' }}>
            {{ __('Advanced Settings') }}
        </option>
        <option value="{{ route('admin.settings.smtp') }}" {{ request()->segment(3) == 'smtp' ? 'selected' : '' }}>
            {{ __('SMTP Settings') }}
        </option>
        <option value="{{ route('admin.settings.appearance') }}"
            {{ request()->segment(3) == 'appearance' ? 'selected' : '' }}>
            {{ __('Appearance') }}
        </option>
        <option value="{{ route('admin.settings.plans') }}" {{ request()->segment(3) == 'plans' ? 'selected' : '' }}>
            {{ __('User and Guest Settings') }}
        </option>
        <option value="{{ route('admin.admins.index') }}" {{ request()->segment(2) == 'admins' ? 'selected' : '' }}>
            {{ __('Admins') }}
        </option>
        <option value="{{ route('admin.settings.seo.index') }}" {{ request()->segment(3) == 'seo' ? 'selected' : '' }}>
            {{ __('SEO') }}
        </option>
        <option value="{{ route('admin.settings.blog') }}" {{ request()->segment(3) == 'blog' ? 'selected' : '' }}>
            {{ __('Blog Settings') }}
        </option>
        <option value="{{ route('admin.settings.emails.index') }}"
            {{ request()->segment(3) == 'emails' ? 'selected' : '' }}>
            {{ __('Email Templates') }}
        </option>
        <option value="{{ route('admin.settings.languages.index') }}"
            {{ request()->segment(3) == 'languages' ? 'selected' : '' }}>
            {{ __('Languages') }}
        </option>
        <option value="{{ route('admin.settings.ads.index') }}"
            {{ request()->segment(3) == 'ads' ? 'selected' : '' }}>
            {{ __('Ads') }}
        </option>
        <option value="{{ route('admin.settings.captcha') }}"
            {{ request()->segment(3) == 'captcha' ? 'selected' : '' }}>
            {{ __('Captcha Settings') }}
        </option>
        <option value="{{ route('admin.settings.api') }}" {{ request()->segment(3) == 'api' ? 'selected' : '' }}>
            {{ __('API') }}
        </option>
        <option value="{{ route('admin.settings.cronjob') }}"
            {{ request()->segment(3) == 'cron-job' ? 'selected' : '' }}>
            {{ __('Cron Job') }}
        </option>
        <option value="{{ route('admin.settings.cache') }}" {{ request()->segment(3) == 'cache' ? 'selected' : '' }}>
            {{ __('Cache') }}
        </option>
        <option value="{{ route('admin.settings.license') }}"
            {{ request()->segment(3) == 'license' ? 'selected' : '' }}>
            {{ __('License') }}
        </option>
        <option value="{{ route('admin.settings.maintenance') }}"
            {{ request()->segment(3) == 'maintenance' ? 'selected' : '' }}>
            {{ __('Maintenance Mode') }}
        </option>
        <option value="{{ route('admin.settings.system') }}"
            {{ request()->segment(3) == 'system-info' ? 'selected' : '' }}>
            {{ __('System Info') }}
        </option>
    </select>
</div>
<!-- Settings Side -->
<div class="settings-side d-none d-xl-block">
    <div class="box p-0 overflow-hidden">
        <a href="{{ route('admin.settings.general') }}"
            class="settings-link {{ request()->segment(3) == 'general' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa fa-cog"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('General Settings') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.advanced') }}"
            class="settings-link {{ request()->segment(3) == 'advanced' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Advanced Settings') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.smtp') }}"
            class="settings-link {{ request()->segment(3) == 'smtp' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('SMTP Settings') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.appearance') }}"
            class="settings-link {{ request()->segment(3) == 'appearance' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-paint-roller"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Appearance') }}</h6>
            </div>
        </a>


        <a href="{{ route('admin.settings.plans') }}"
            class="settings-link {{ request()->segment(3) == 'plans' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('User and Guest Settings') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.admins.index') }}"
            class="settings-link {{ request()->segment(2) == 'admins' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Admins') }}</h6>
            </div>
        </a>


        <a href="{{ route('admin.settings.seo.index') }}"
            class="settings-link {{ request()->segment(3) == 'seo' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-ranking-star"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Seo') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.blog') }}"
            class="settings-link {{ request()->segment(3) == 'blog' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-square-rss"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Blog Settings') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.emails.index') }}"
            class="settings-link {{ request()->segment(3) == 'emails' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Email Templates') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.languages.index') }}"
            class="settings-link {{ request()->segment(3) == 'languages' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fas fa-language"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Languages') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.ads.index') }}"
            class="settings-link {{ request()->segment(3) == 'ads' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-rectangle-ad"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Ads') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.captcha') }}"
            class="settings-link {{ request()->segment(3) == 'captcha' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Captcha Settings') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.api') }}"
            class="settings-link {{ request()->segment(3) == 'api' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-plug"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('API') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.cronjob') }}"
            class="settings-link {{ request()->segment(3) == 'cron-job' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Cron Job') }}</h6>
            </div>
        </a>


        <a href="{{ route('admin.settings.cache') }}"
            class="settings-link {{ request()->segment(3) == 'cache' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-database"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Cache') }}</h6>
            </div>
        </a>


        <a href="{{ route('admin.settings.license') }}"
            class="settings-link {{ request()->segment(3) == 'license' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-key"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('License') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.maintenance') }}"
            class="settings-link {{ request()->segment(3) == 'maintenance' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-hammer"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('Maintenance Mode') }}</h6>
            </div>
        </a>

        <a href="{{ route('admin.settings.system') }}"
            class="settings-link {{ request()->segment(3) == 'system-info' ? 'current' : '' }}">
            <div class="settings-link-icon">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="settings-link-info">
                <h6 class="settings-link-title">{{ __('System Info') }}</h6>
            </div>
        </a>
    </div>
</div>
<!-- /Settings Side -->
