@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Pages' goTo="{{ route('admin.pages.create') }}" />

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
                                    <a href="{{ route('admin.pages.index') }}" class="filters-box-reset btn btn-reset">
                                        <i class="fa fa-times"></i>
                                        <span>{{ __('Reset') }}</span>
                                    </a>
                                </div>
                                <form method="GET" action="{{ route('admin.pages.index') }}">
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
                                            <h6 class="mb-3">{{ __('Order by') }}</h6>
                                            <div class="col">
                                                <select name="order_by" class="form-select ">
                                                    <option value="created_at"
                                                        {{ request('order_by') == 'created_at' ? 'selected' : '' }}>
                                                        {{ __('Date') }}
                                                    </option>
                                                    <option value="title"
                                                        {{ request('order_by') == 'title' ? 'selected' : '' }}>
                                                        {{ __('Title') }}
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
                                                    <option value="250" {{ request('limit') == 250 ? 'selected' : '' }}>
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
                <div class="col">
                    <form action=" {{ route('admin.pages.index') }}" class="select2-sm" method="GET">
                        <select onchange="this.form.submit()" class="select-input" name="lang">
                            <option value="all">{{ __('All Languages') }}</option>
                            @foreach (getAllLanguages() as $lang)
                                <option {{ request()->input('lang') == $lang->code ? 'selected' : '' }}
                                    value="{{ $lang->code }}">
                                    {{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-inner">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('#') }}</th>
                            <th class="text-center">{{ __('Title') }}</th>
                            <th class="text-center">{{ __('Views') }}</th>
                            <th class="text-center">{{ __('Language') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Created At') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse  ($pages as $page)
                            <tr>
                                <td class="text-center">
                                    {{ $page->id }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pages.edit', $page->id) }}">{{ $page->title }}</a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark">{{ $page->views }}</span>
                                </td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('admin.settings.languages.translate', $page->lang) }}">{{ $page->lang }}</a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $page->getStatusColor() }}">
                                        {{ $page->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ ToDate($page->created_at) }}
                                </td>
                                <td class="d-flex justify-content-center">
                                    <div class="d-flex gap-2">

                                        <a target="_blank" href="{{ route('page', $page->slug) }}"
                                            class="btn btn-primary cp-x-2">
                                            <i class="far fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.pages.edit', $page->id) }}"
                                            class="btn btn-success cp-x-2">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $page->id }}"
                                            data-action="{{ route('admin.pages.destroy', $page->id) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-empty title="pages" />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-footer mt-3">
            <div class="row row-cols-auto justify-content-between align-items-center g-3">
                <div class="col">
                    <span>
                        {{ __('Showing') }} {{ $pages->firstItem() }} {{ __('to') }}
                        {{ $pages->lastItem() }} {{ __('of') }}
                        {{ $pages->total() }} {{ __('entries') }}
                    </span>
                </div>
                <div class="col">
                    {{ $pages->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-delete-modal />
@endsection
