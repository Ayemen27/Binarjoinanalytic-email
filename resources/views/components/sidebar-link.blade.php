<div class="dashboard-sidebar-link {{ $current ? 'current' : '' }} ">
    <a href="{{ $href }}" class="dashboard-sidebar-link-title">
        <i class="{{ $icon }}"></i>
        <span>{{ $title }}</span>
        @if (isset($badge))
            <div class="dashboard-sidebar-badge ms-auto">
                {{ $badge }}
            </div>
        @endif
    </a>
</div>
