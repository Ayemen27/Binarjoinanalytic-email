@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Categories' GoTo="{{ route('admin.blog.categories.create') }}" />

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
                                    <a href="{{ route('admin.blog.categories.index') }}"
                                        class="filters-box-reset btn btn-reset">
                                        <i class="fa fa-times"></i>
                                        <span>{{ __('Reset') }}</span>
                                    </a>
                                </div>
                                <form method="GET" action="{{ route('admin.blog.categories.index') }}">
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
                    <form action=" {{ route('admin.blog.categories.index') }}" method="GET">
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
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Language') }}</th>
                            <th class="text-center">{{ __('Total Posts') }}</th>
                            <th class="text-center">{{ __('Created At') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse  ($categories as $category)
                            <tr>
                                <td class="text-center">
                                    {{ $category->id }}
                                </td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('admin.blog.categories.edit', $category->id) }}">{{ $category->name }}</a>
                                </td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('admin.settings.languages.translate', $category->lang) }}">{{ $category->lang }}</a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark">{{ $category->posts->count() }}</span>
                                </td>
                                <td class="text-center">
                                    {{ ToDate($category->created_at) }}
                                </td>


                                <td class="d-flex justify-content-center">
                                    <div class="d-flex gap-2">

                                        <a target="_blank" href="{{ route('category', $category->slug) }}"
                                            class="btn btn-primary cp-x-2">
                                            <i class="far fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.blog.categories.edit', $category->id) }}"
                                            class="btn btn-success cp-x-2">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $category->id }}"
                                            data-action="{{ route('admin.blog.categories.destroy', $category->id) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-empty title="categories" />
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-footer mt-3">
            <div class="row row-cols-auto justify-content-between align-items-center g-3">
                <div class="col">
                    <span>
                        {{ __('Showing') }} {{ $categories->firstItem() }} {{ __('to') }}
                        {{ $categories->lastItem() }} {{ __('of') }}
                        {{ $categories->total() }} {{ __('entries') }}
                    </span>
                </div>
                <div class="col">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-delete-modal />
@endsection
