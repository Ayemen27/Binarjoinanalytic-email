@extends('admin.layouts.admin')
@section('title', 'Dashboard')

@section('content')

    <div>
        <div class="mb-3">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $emails }}</p>
                                <h6 class="counter-title">{{ __('Total Emails') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-at"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $messages }}</p>
                                <h6 class="counter-title">{{ __('Total Messages') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $fav_messages }}</p>
                                <h6 class="counter-title">{{ __('Favorite Messages') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-star"></i>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $domains }}</p>
                                <h6 class="counter-title">{{ __('Total Domains') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $users }}</p>
                                <h6 class="counter-title">{{ __('Total Users') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $posts }}</p>
                                <h6 class="counter-title">{{ __('Total Posts') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $pages }}</p>
                                <h6 class="counter-title">{{ __('Total Pages') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $notifications }}</p>
                                <h6 class="counter-title">{{ __('Total Notifications') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-regular fa-bell "></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="row g-3">
                <div class="col-12 col-xxl-8">
                    <div class="box h-100">
                        <div class="box-header mb-4">
                            <h5 class="mb-0">{{ __('User Statistics in the Last Month') }}</h5>
                        </div>
                        <div class="box-body">
                            <div class="dashboard-chart">
                                <canvas id="users-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xxl-4">
                    <div class="box h-100 box-custom">
                        <div class="box-header">
                            <h5 class="mb-0">{{ __('New Users') }}</h5>
                        </div>
                        <div class="box-body">
                            <div class="items">
                                <!-- List Item -->
                                @foreach ($last_users as $user)
                                    <div class="item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="item-img me-3 ">
                                                <img src="{{ asset($user->avatar) }}" alt="{{ $user->getFullName() }}">
                                            </a>
                                            <div>
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="item-title d-block fw-500 mb-1">
                                                    {{ $user->getFullName() }}
                                                </a>
                                                <p class="item-text text-muted mb-0">
                                                    {{ toDiffForHumans($user->created_at) }}</p>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-primary cp-x-2">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- /List Item -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xxl-6">
                    <div class="box h-100">
                        <div class="box-header mb-4">
                            <h5 class="mb-0">{{ __('Emails Created in the Last 2 Weeks') }}</h5>
                        </div>
                        <div class="box-body">
                            <div class="dashboard-chart">
                                <canvas id="email-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xxl-6">
                    <div class="box h-100">
                        <div class="box-header mb-4">
                            <h5 class="mb-0">{{ __('Messages Received in the Last 2 Weeks') }}</h5>
                        </div>
                        <div class="box-body">
                            <div class="dashboard-chart">
                                <canvas id="message-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/vendor/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.admin.js') }}"></script>
@endpush
