@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Notifications' col="col-12 col-xl-10 col-xxl-10">
        <div class="col-auto">
            <a href="{{ route('admin.notifications.markAllRead') }}" class="btn btn-success h-100">
                <i class="fa-solid fa-check-double"></i>
                {{ __('Mark All as Read') }}
            </a>
            <button class="btn btn-danger h-100" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id=""
                data-action="{{ route('admin.notifications.delete') }}">
                <i class="fas fa-trash-alt"></i> {{ __('Delete All') }}
            </button>

        </div>
    </x-breadcrumb>

    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="notifications-all">
                            @forelse ($all_notifications as $notification)
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


                            <div class="row row-cols-auto justify-content-between align-items-center g-3 mt-3">
                                <div class="col">
                                    <span>
                                        {{ __('Showing') }} {{ $all_notifications->firstItem() }} {{ __('to') }}
                                        {{ $all_notifications->lastItem() }} {{ __('of') }}
                                        {{ $all_notifications->total() }} {{ __('entries') }}
                                    </span>
                                </div>
                                <div class="col">
                                    {{ $all_notifications->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-delete-modal />
@endsection
