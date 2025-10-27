@extends('admin.layouts.admin')
@section('title', 'Edit ' . $language->name . ' Translate')

@section('content')
    <x-breadcrumb title='Edit {{ $language->name }} Translate' backTo="{{ route('admin.settings.languages.index') }}" />

    <div class="mb-4">
        <div class="row justify-content-between g-3">
            <form class="d-flex" action="{{ route('admin.settings.languages.translate', $language->code) }}" method="GET">
                <div class="ms-2">
                    <select class="form-select" onchange="this.form.submit()" name="group">
                        <option {{ request()->input('all') == 'all' ? 'selected' : '' }} value="all">{{ __('All') }}
                        </option>
                        @foreach ($collections as $collection)
                            <option {{ request()->input('group') == $collection ? 'selected' : '' }}
                                value="{{ $collection }}">{{ $collection }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ms-2">
                    <select class="form-select" onchange="this.form.submit()" name="status">
                        <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>
                            {{ __('All Translations') }}</option>
                        <option value="0" {{ request()->input('status') == '0' ? 'selected' : '' }}>
                            {{ __('Missing Translations') }}
                        </option>
                    </select>
                </div>
                <div class="ms-2">
                    <input type="text" class="form-control" value="{{ request()->input('q') ?? '' }}" name="q"
                        placeholder="Search ...">
                </div>
                <div class="ms-2">
                    <button class="btn btn-primary h-100" type="submit"><i
                            class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class=" alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">

        <strong>{{ __('Attention:') }}</strong>
        {{ __('Please do not translate text inside double curly braces, e.g.,') }}
        <code>{{ '{any word}' }}</code>.
        {{ __('Also, avoid translating words that start with a colon, e.g.,') }}
        <code>{{ __(':date') }}</code>, <code>{{ __(':attribute') }}</code>.
    </div>


    <div class="box p-3">

        <div class="p-3 flex-wrap">
            <form class="form-horizontal"
                action="{{ route('admin.settings.languages.update_translate', $language->code) }}" method="POST">
                <div class="lobage-card-body text-start row p-0">
                    <div class="table-responsive">
                        @csrf
                        <div class="card-body">
                            @forelse ($translates as $translate)
                                <div class="row mb-2">
                                    <div class="col-12 col-sm-5 m-auto">
                                        <input type="text" disabled class="form-control" value="{{ $translate->key }}">
                                    </div>
                                    <div class="col-0 m-auto d-none d-sm-block col-sm-1 text-center l-height-40">
                                        <i class="fa-solid fa-right-long"></i>
                                    </div>
                                    @if (request()->input('group') == 'html')
                                        <div class="col-12 col-sm-6">
                                            <textarea id="editor" name="values[{{ $translate->id }}]" class="editor form-control autosize" rows="1">{{ $translate->value }}</textarea>
                                        </div>
                                    @else
                                        <div class="col-12 col-sm-6">
                                            <textarea name="values[{{ $translate->id }}]" class="form-control autosize" rows="1">{{ $translate->value }}</textarea>
                                        </div>
                                    @endif
                                </div>
                            @empty

                                <div class="row mb-2">
                                    <x-empty title="translations" type="true" />
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @if ($translates->isNotEmpty())
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary w-100" type="submit">{{ __('Save Changes') }}</button>
                    </div>
                @endif
            </form>
        </div>


        <div class="row row-cols-auto justify-content-between align-items-center g-3">
            <div class="col">
                <span>
                    {{ __('Showing') }} {{ $translates->firstItem() }} {{ __('to') }}
                    {{ $translates->lastItem() }} {{ __('of') }}
                    {{ $translates->total() }} {{ __('entries') }}
                </span>
            </div>
            <div class="col">
                {{ $translates->appends(request()->query())->links() }}
            </div>
        </div>


    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/ckeditor5.css') }}" />
    <style>
        .ck-content {
            height: 300px !important;

        }
    </style>
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
