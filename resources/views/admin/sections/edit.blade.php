@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Edit Section' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.sections.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.sections.update', $section->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row row-cols-1 g-3">
                                    <input type="hidden" name="type" value="{{ $section->type }}">
                                    @if ($section->type == 'html')
                                        <div class="col">
                                            <x-input name='title' required label="title" value="{{ $section->title }}" />
                                        </div>
                                        <div class="col">
                                            <x-label name="content" for='editor' />
                                            <textarea class="ckeditor editor" id="editor" name="content">{{ $section->content }}</textarea>
                                            <x-error name="content" />
                                        </div>
                                        @if (countLanguages() > 1)
                                            <div class="col">
                                                <x-label name="Language" />
                                                <select class="select-input" hidden name="lang">
                                                    @foreach (getAllLanguages() as $lang)
                                                        <option {{ $section->lang == $lang->code ? 'selected' : '' }}
                                                            value="{{ $lang->code }}">{{ $lang->name }}</option>
                                                    @endforeach
                                                </select>
                                                <x-error name="lang" />
                                            </div>
                                        @else
                                            <input name='lang' hidden value="{{ $section->lang }}" />
                                        @endif
                                    @endif
                                    <div class="col">
                                        <x-label name="Status" for="status" />
                                        <select class="select-input" name="status" id="status">
                                            <option value="1" {{ $section->status == '1' ? 'selected' : '' }}>
                                                {{ __('Publish') }}
                                            </option>
                                            <option value="0" {{ $section->status == '0' ? 'selected' : '' }}>
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

    <script type="module" src="{{ asset('assets/js/vendor/ckeditor_config.js?v=1.4') }}"></script>
@endpush
