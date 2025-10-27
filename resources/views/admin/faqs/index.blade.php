@extends('admin.layouts.admin')

@section('content')
    <!-- Start Lobage Container -->
    <x-breadcrumb col="col-12 col-xl-8 col-xxl-8" title='Faqs' goTo="{{ route('admin.faqs.create') }}">
        <div class="col-auto">
            <form action="{{ route('admin.faqs.index') }}" class="select2-sm" method="GET">
                <select onchange="this.form.submit()" class="select-input ms" hidden name="lang">
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
                    <div class="dd" id="nestable2">
                        <ol class="dd-list">
                            @foreach ($faqs as $faq)
                                <li class="dd-item" data-id="{{ $faq->id }}">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="handle row align-items-center">
                                        <div class="col text-truncate">
                                            <a href="#" class="fw-500 text-reset d-block">
                                                {{ $faq->title }}</a>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                                class="btn btn-success cp-x-2">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $faq->id }}"
                                                data-action="{{ route('admin.faqs.destroy', $faq->id) }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
                @if ($faqs->count() > 0)
                    <form action="{{ route('admin.faqs.update_position') }}" method="POST">
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
                        <x-empty title="faqs" type="true" />
                    </div>
                @endif
            </div>
        </div>
        <!-- End Lobage Page Body -->
    </div>
    <x-delete-modal />
    <!-- End Lobage Container -->
@endsection
