@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Add New Section' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.sections.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.sections.store') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='title' required label="title" />
                                    </div>
                                    <div class="col">
                                        <x-label name="content" for="editor" />
                                        <textarea class="ckeditor editor" id="editor" name="content">{{ old('content') }}</textarea>
                                        <div class="mb-3">
                                            <x-error name="content" />
                                        </div>
                                    </div>
                                    @if (countLanguages() > 1)
                                        <div class="col">
                                            <x-label name="Language" />
                                            <select class="select-input" hidden name="lang">
                                                @foreach (getAllLanguages() as $lang)
                                                    <option {{ old('lang') == $lang->code ? 'selected' : '' }}
                                                        value="{{ $lang->code }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-error name="lang" />
                                        </div>
                                    @else
                                        <input name='lang' hidden value="{{ getSetting('default_language') }}" />
                                    @endif

                                    <div class="col">
                                        <x-label name="Status" for="status" />
                                        <select class="select-input" name="status" id="status">
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                                {{ __('Publish') }}
                                            </option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                                {{ __('Disable') }}
                                            </option>
                                        </select>
                                        <x-error name="status" />
                                    </div>

                                    <div class="col">
                                        <x-button class="w-100" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/ckeditor5.css') }}" />
@endpush

@push('scripts')
    <script type="importmap">
      {
        "imports": {
          "ckeditor5": "{{ asset('assets/js/vendor/ckeditor.js') }}",
          "ckeditor5/": ""
        }
      }
    </script>

    <script type="module" src="{{ asset('assets/js/vendor/ckeditor_config.js?v=' . env('SITE_VERSION')) }}"></script>
@endpush
