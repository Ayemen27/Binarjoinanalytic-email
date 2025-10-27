@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Users' goTo="{{ route('admin.users.create') }}" />

    <div class="mb-3 row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
        <div class="col">
            <div class="box h-100">
                <div class="counter">
                    <div class="counter-info">
                        <p class="counter-amount">{{ $get_all_users }}</p>
                        <h6 class="counter-title">All Users</h6>
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
                        <p class="counter-amount">{{ $get_active_users }}</p>
                        <h6 class="counter-title">Active Users</h6>
                    </div>
                    <div class="counter-icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="box h-100">
                <div class="counter">
                    <div class="counter-info">
                        <p class="counter-amount">{{ $get_inactive_users }}</p>
                        <h6 class="counter-title">Inactive Users</h6>
                    </div>
                    <div class="counter-icon">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="box h-100">
                <div class="counter">
                    <div class="counter-info">
                        <p class="counter-amount">{{ $get_banned_users }}</p>
                        <h6 class="counter-title">Banned Users</h6>
                    </div>
                    <div class="counter-icon">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="box p-3">
        <div class="table-header mb-3">
            <div class="row row-cols-auto flex-row-reverse align-items-center  g-2">
                <div class="col">
                    <div class="drop-down drop-down-md filters" data-dropdown="" data-dropdown-propagation="">
                        <div class="drop-down-btn btn btn-theme btn-md cp-x-2">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                        <!-- Dropdown Menu -->
                        <div class="drop-down-menu">
                            <div class="filters-box">
                                <div class="filters-box-header d-flex justify-content-between gap-2">
                                    <p class="filters-box-title mb-0">
                                        {{ __('Filters') }}
                                    </p>
                                    <a href="{{ route('admin.users.index') }}" class="filters-box-reset btn btn-reset">
                                        <i class="fa fa-times"></i>
                                        <span>{{ __('Reset') }}</span>
                                    </a>
                                </div>
                                <form method="GET" action="{{ route('admin.users.index') }}">
                                    <div class="filters-box-body">
                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Search') }}</h6>
                                            <div class="col">
                                                <input type="text" name="q" class="form-control "
                                                    placeholder="Type your query here..." value="{{ request('q') }}">
                                            </div>
                                        </div>

                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Account Status') }}</h6>
                                            <div class="col">
                                                <select name="status" class="form-select ">
                                                    <option value="all"> {{ __('All') }}</option>
                                                    <option value="1"
                                                        {{ request('status') == '1' ? 'selected' : '' }}>
                                                        {{ __('Active') }}
                                                    </option>
                                                    <option value="0"
                                                        {{ request('status') == '0' ? 'selected' : '' }}>
                                                        {{ __('Banned') }}
                                                    </option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Email Status') }}</h6>
                                            <div class="col">
                                                <select name="email_status" class="form-select ">
                                                    <option value="all"> {{ __('All') }}</option>
                                                    <option value="1"
                                                        {{ request('email_status') == '1' ? 'selected' : '' }}>
                                                        {{ __('Verified') }}
                                                    </option>
                                                    <option value="0"
                                                        {{ request('email_status') == '0' ? 'selected' : '' }}>
                                                        {{ __('Unverified') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Order by') }}</h6>
                                            <div class="col">
                                                <select name="order_by" class="form-select ">
                                                    <option value="created_at"
                                                        {{ request('order_by') == 'created_at' ? 'selected' : '' }}>
                                                        {{ __('Date') }}
                                                    </option>
                                                    <option value="name"
                                                        {{ request('order_by') == 'name' ? 'selected' : '' }}>
                                                        {{ __('Name') }}
                                                    </option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>
                                        </div>


                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Order type') }}</h6>
                                            <div class="col">
                                                <select name="order_type" class="form-select ">
                                                    <option value="desc"
                                                        {{ request('order_type') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('Descending') }}
                                                    </option>
                                                    <option value="asc"
                                                        {{ request('order_type') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('Ascending') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Results per page') }}</h6>
                                            <div class="col">
                                                <select name="limit" class="form-select">
                                                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>
                                                        25</option>
                                                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>
                                                        50</option>
                                                    <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>
                                                        100</option>
                                                    <option value="250"
                                                        {{ request('limit') == 250 ? 'selected' : '' }}>
                                                        250</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="filters-box-header d-flex justify-content-end gap-2">
                                            <button type="reset" class="btn btn-outline-danger w-50"
                                                data-dropdown-close="">{{ __('Cancel') }}</button>
                                            <button type="submit"
                                                class="btn btn-primary w-50">{{ __('Apply') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /Dropdown Menu -->
                    </div>
                </div>

            </div>
        </div>

        <div class="table-inner">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('#') }}</th>
                            <th class="">{{ __('User') }}</th>
                            <th class="text-center">{{ __('Email Status') }}</th>
                            <th class="text-center">{{ __('Account Status') }}</th>
                            <th class="text-center">{{ __('Registered Date') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse  ($users as $user)
                            <tr>
                                <td class="text-center">
                                    {{ $user->id }}
                                </td>
                                <td>
                                    <div class="user">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="user-img">
                                            <img src="{{ asset($user->avatar) }}" alt="avatar" />
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="user-name">{{ $user->getFullName() }}</a>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-green-lt">{{ __('Verified') }}</span>
                                    @else
                                        <span class="badge bg-red-lt">{{ __('Unverified') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($user->status)
                                        <span class="badge bg-green">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-red">{{ __('Banned') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ toDate($user->created_at) }}
                                </td>
                                <td class="d-flex justify-content-center">
                                    <div class="d-flex gap-2">

                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-success cp-x-2">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $user->id }}"
                                            data-action="{{ route('admin.users.destroy', $user->id) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-empty title="users" />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-footer mt-3">
            <div class="row row-cols-auto justify-content-between align-items-center g-3">
                <div class="col">
                    <span>
                        {{ __('Showing') }} {{ $users->firstItem() }} {{ __('to') }}
                        {{ $users->lastItem() }} {{ __('of') }}
                        {{ $users->total() }} {{ __('entries') }}
                    </span>
                </div>
                <div class="col">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-delete-modal />
@endsection
