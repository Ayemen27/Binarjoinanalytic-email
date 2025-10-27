@extends('admin.layouts.admin')

@section('content')
    <!-- Start Lobage Container -->
    <x-breadcrumb col="col-12 col-xl-8 col-xxl-8" title='Header Menus' goTo="{{ route('admin.menus.header.create') }}">
        <div class="col-auto">
            <form action=" {{ route('admin.menus.header') }}" class="select2-sm" method="GET">
                <select onchange="this.form.submit()" class="select-input" hidden name="lang">
                    @foreach (getAllLanguages() as $lang)
                        <option {{ request()->input('lang') == $lang->code ? 'selected' : '' }} value="{{ $lang->code }}">
                            {{ $lang->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-breadcrumb>
    <!-- Start Lobage Page Body -->
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="row row-cols-1 g-3">
                <div class="col">
                    <div class="dd" id="nestable1">
                        <ol class="dd-list">
                            @foreach ($menus as $menu)
                                <li class="dd-item" data-id="{{ $menu->id }}">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="handle row align-items-center">
                                        <div class="col text-truncate">
                                            <a href="#" class="fw-500 text-reset d-block">
                                                {!! $menu->icon !!} {{ $menu->name }}</a>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('admin.menus.header.edit', $menu->id) }}"
                                                class="btn btn-success cp-x-2">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $menu->id }}"
                                                data-action="{{ route('admin.menus.destroy', $menu->id) }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if ($menu->children->count() > 0)
                                        <ol class="dd-list">
                                            @foreach ($menu->children as $children)
                                                <li class="dd-item" data-id="{{ $children->id }}">
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="handle row align-items-center">
                                                        <div class="col text-truncate">
                                                            <a href="#" class="fw-500 text-reset d-block">
                                                                {!! $children->icon !!} {{ $children->name }}</a>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="{{ route('admin.menus.header.edit', $children->id) }}"
                                                                class="btn btn-success cp-x-2">
                                                                <i class="far fa-edit"></i>
                                                            </a>
                                                            <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" data-id="{{ $children->id }}"
                                                                data-action="{{ route('admin.menus.destroy', $children->id) }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
                @if ($menus->count() > 0)
                    <form action="{{ route('admin.menus.update_position') }}" method="POST">
                        @csrf
                        <input hidden name="position" id="nestable-output">
                        <div class="col-12 mb-3">
                            <button class="d-block ms-auto btn btn-primary " type="submit">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="col-12 mb-3">
                        <x-empty title="menus" type="true" />
                    </div>
                @endif
            </div>
        </div>
        <!-- End Lobage Page Body -->
    </div>
    <x-delete-modal />
    <!-- End Lobage Container -->
@endsection
