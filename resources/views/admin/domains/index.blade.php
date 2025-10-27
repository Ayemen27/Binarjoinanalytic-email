@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Domains' GoTo="{{ route('admin.domains.create') }}" />
    <div class="mb-3 row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
        <div class="col">
            <div class="box h-100">
                <div class="counter">
                    <div class="counter-info">
                        <p class="counter-amount">{{ $all_domains }}</p>
                        <h6 class="counter-title">{{ __('ALL Domains') }}</h6>
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
                        <p class="counter-amount">{{ $free_domains }}</p>
                        <h6 class="counter-title">{{ __('Free Domains') }}</h6>
                    </div>
                    <div class="counter-icon">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="box h-100">
                <div class="counter">
                    <div class="counter-info">
                        <p class="counter-amount">{{ $premium_domains }}</p>
                        <h6 class="counter-title">{{ __('Premium Domains') }}</h6>
                    </div>
                    <div class="counter-icon">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="box h-100">
                <div class="counter">
                    <div class="counter-info">
                        <p class="counter-amount">{{ $custom_domains }}</p>
                        <h6 class="counter-title">{{ __('Custom Domains') }}</h6>
                    </div>
                    <div class="counter-icon">
                        <i class="fa-solid fa-gem"></i>
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
                                    <a href="{{ route('admin.domains.index') }}" class="filters-box-reset btn btn-reset">
                                        <i class="fa fa-times"></i>
                                        <span>{{ __('Reset') }}</span>
                                    </a>
                                </div>
                                <form method="GET" action="{{ route('admin.domains.index') }}">
                                    @if (request('lang'))
                                        <input type="hidden" name="lang" value="{{ request('lang') }}">
                                    @endif
                                    <div class="filters-box-body">
                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Search') }}</h6>
                                            <div class="col">
                                                <input type="text" name="q" class="form-control "
                                                    placeholder="Type your query here..." value="{{ request('q') }}">
                                            </div>
                                        </div>

                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Status') }}</h6>
                                            <div class="col">
                                                <select name="status" class="form-select ">
                                                    <option value="all"
                                                        {{ request('status') == 'all' ? 'selected' : '' }}>
                                                        {{ __('All') }}
                                                    </option>
                                                    <option value="0"
                                                        {{ request('status') == '0' ? 'selected' : '' }}>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="1"
                                                        {{ request('status') == '1' ? 'selected' : '' }}>
                                                        {{ __('Approved') }}
                                                    </option>
                                                    <option value="2"
                                                        {{ request('status') == '2' ? 'selected' : '' }}>
                                                        {{ __('Canceled') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="filters-box-items">
                                            <h6 class="mb-3">{{ __('Type') }}</h6>
                                            <div class="col">
                                                <select name="type" class="form-select ">
                                                    <option value="all"
                                                        {{ request('type') == 'all' ? 'selected' : '' }}>
                                                        {{ __('All') }}
                                                    </option>
                                                    <option value="0" {{ request('type') == '0' ? 'selected' : '' }}>
                                                        {{ __('Free') }}
                                                    </option>
                                                    <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>
                                                        {{ __('Premium') }}
                                                    </option>
                                                    <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>
                                                        {{ __('Custom') }}
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
                                                    <option value="domain"
                                                        {{ request('order_by') == 'domain' ? 'selected' : '' }}>
                                                        {{ __('Domain') }}
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
                                                    <option value="100"
                                                        {{ request('limit') == 100 ? 'selected' : '' }}>
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
                            <th class="text-center">{{ __('Domain Name') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Created at') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse  ($domains as $domain)
                            <tr>
                                <td class="text-center">
                                    {{ $domain->id }}
                                </td>
                                <td class="text-center">
                                    {{ $domain->domain }}
                                </td>
                                <td class="text-center">
                                    {{ toDate($domain->created_at) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $domain->getStatusColor() }}">
                                        {{ $domain->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $domain->getTypeColor() }}-lt">
                                        {{ $domain->getTypeLabel() }}
                                    </span>
                                </td>
                                <td class="d-flex justify-content-center">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.domains.edit', $domain->id) }}"
                                            class="btn btn-success cp-x-2">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $domain->id }}"
                                            data-action="{{ route('admin.domains.destroy', $domain->id) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-empty title="domains" />
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-footer mt-3">
            <div class="row row-cols-auto justify-content-between align-items-center g-3">
                <div class="col">
                    <span>
                        {{ __('Showing') }} {{ $domains->firstItem() }} {{ __('to') }}
                        {{ $domains->lastItem() }} {{ __('of') }}
                        {{ $domains->total() }} {{ __('entries') }}
                    </span>
                </div>
                <div class="col">
                    {{ $domains->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    <x-delete-modal />
@endsection
