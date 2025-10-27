@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Edit Page' backTo="{{ route('admin.pages.index') }}" />
    <div>

        <form id="submission_click_disabled" action="{{ route('admin.pages.update', $page->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3 justify-content-center">
                <div class="row g-3">
                    <div class="col-12 col-lg-8">
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <div class="box">
                                    <h5 class="mb-4">{{ __('Main Content') }}</h5>
                                    <div class="row row-cols-1 g-3">
                                        <div class="col">
                                            <x-input input-placeholder="#seo-title" required name="title" label="Title"
                                                :value="$page->title" />
                                        </div>
                                        <div class="col">
                                            <x-label name="conten" for="editor" />
                                            <textarea class="ckeditor editor" id="editor" name="content">{!! $page->content !!}</textarea>
                                            <div class="mb-3">
                                                <x-error name="content" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="box">
                                    <div class="mb-4">
                                        <div class="row row-cols-auto justify-content-between g-3">
                                            <div class="col">
                                                <h5 class="mb-0">{{ __('Search engine listing preview') }}</h5>
                                            </div>
                                            <div class="col">
                                                <a class="fs-6 fw-medium" data-bs-toggle="collapse"
                                                    href="#collapseExample2">
                                                    {{ __('Edit Website SEO') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="seo-preview d-flex flex-column gap-1">
                                        <h6 id="seo-title" class="seo-title mb-0"></h6>
                                        <div class="d-flex">
                                            <span class="seo-url">{{ url('/page') }}/</span>
                                            <a id="seo-url" data-url="{{ url('/page') }}/" class="seo-url"
                                                href="#"></a>
                                        </div>
                                        <p id="seo-desc" class="seo-desc mb-0"></p>
                                    </div>
                                    <div class="collapse" id="collapseExample2">
                                        <div class="seo-form pt-3 mt-3 border-top">
                                            <div class="row row-cols-1 g-3">
                                                <div class="col">
                                                    <x-input input-target="#seo-title" name="meta_title" label="meta title"
                                                        value="{{ $page->meta_title }}" />
                                                </div>
                                                <div class="col">
                                                    <x-label name="Meta Description" for="meta_description" />
                                                    <textarea name="meta_description" id="meta_description" class="form-control form-control-md" input-target="#seo-desc"
                                                        rows="5">{{ $page->meta_description }}</textarea>
                                                    <x-error name="meta_description" />
                                                </div>
                                                <div class="col">
                                                    <x-label name="Slug" for="slug" />
                                                    <div class="form-group form-group-md">
                                                        <label
                                                            class="form-group-text slug_label">{{ url('/page') }}/</label>
                                                        <input type="text" class="form-control form-control-md"
                                                            id="slug" value="{{ $page->slug }}" required
                                                            name="slug" input-target="#seo-url">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="row row-cols-1 g-3">

                            <div class="col">
                                <div class="box">
                                    <h5 class="mb-4">{{ __('Settings') }}</h5>
                                    <div class="row row-cols-1 g-3">
                                        <div class="col">
                                            <x-label name="Language" for="lang" />
                                            <select class="select-input" name="lang" id="lang">
                                                <option value="" selected disabled>{{ __('Choose') }}</option>
                                                @foreach (getAllLanguages() as $lang)
                                                    <option {{ $page->lang == $lang->code ? 'selected' : '' }}
                                                        value="{{ $lang->code }}">{{ $lang->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-error name="lang" />
                                        </div>

                                        <div class="col">
                                            <x-label name="Status" for="status" />
                                            <select class="select-input" name="status" id="status">
                                                <option value="1" {{ $page->status == '1' ? 'selected' : '' }}>
                                                    {{ __('Publish') }}
                                                </option>
                                                <option value="0" {{ $page->status == '0' ? 'selected' : '' }}>
                                                    {{ __('Draft') }}
                                                </option>
                                            </select>
                                            <x-error name="status" />
                                        </div>
                                        <div class="col">
                                            <x-button class="w-100" />
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/ckeditor5.css') }}" />
@endpush

@push('libraies')
    <script src="{{ asset('assets/js/vendor/uploadAdapterPlugin.js') }}"></script>
    <script type="importmap">
      {
        "imports": {
          "ckeditor5": "{{ asset('assets/js/vendor/ckeditor.js') }}",
          "ckeditor5/": ""
        }
      }
    </script>

    <script type="module" src="{{ asset('assets/js/vendor/ckeditor_config.js') }}"></script>
@endpush
