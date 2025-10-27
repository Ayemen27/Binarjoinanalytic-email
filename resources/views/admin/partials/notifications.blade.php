<div class="drop-down-menu">
    <div class="notifications">
        <div class="notifications-header">
            <h5 class="mb-0">{{ __('Notifications') }}</h5>
        </div>
        <div class="notifications-body">
            @forelse ($notifications as $notification)
                <a href="{{ route('admin.notifications.show', $notification->id) }}"
                    class="notifications-item {{ $notification->is_read ? '' : 'unread' }}">
                    <div class="notifications-item-img">
                        <img src="{{ asset('assets/img/notifications/' . $notification->icon . '.png') }}"
                            alt="notifications" />
                    </div>
                    <div class="notifications-item-info">
                        <h6 class="notifications-item-title">
                            {{ $notification->message }}
                        </h6>
                        <span class="notifications-item-text">
                            {{ toDiffForHumans($notification->created_at) }}
                        </span>
                    </div>
                </a>
            @empty
                <h6 class="text-center p-3">{{ __('No notifications available') }}</h6>
            @endforelse

        </div>
        <div class="notifications-footer">
            <a href="{{ route('admin.notifications.index') }}">
                {{ __('See All') }} ({{ $notifications_count_all }})
            </a>
            <a href="{{ route('admin.notifications.markAllRead') }}">
                {{ __('Mark All as Read') }}
            </a>
        </div>
    </div>
</div>
