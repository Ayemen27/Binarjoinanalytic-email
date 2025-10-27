@extends('admin.layouts.admin')
@section('title', 'Edit Ad')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5>{{ __('Edit Ad') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.ads.index') }}" class="btn btn-secondary  h-100">
                            <i class="fa-solid fa-arrow-left mx-1"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.settings.ads.update', $ad->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="seo-form pt-3 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <x-input readonly='readonly' name='name' label="Ad Name" id="name"
                                    value="{{ $ad->name }}" />
                            </div>

                            <div class="col">
                                <label class="form-label">{{ __('Code:') }}</label>
                                <textarea type="text" class="codeeditor" rows="6" name="code">{{ $ad->code }}</textarea>
                                <x-error name="code" />
                            </div>

                            <div class="col">
                                <label class="form-label">{{ __('Status:') }}</label>
                                <select class="select-input @error('status') is-invalid @enderror" required name="status">
                                    <option value="1" {{ $ad->status == '1' ? 'selected' : '' }}>
                                        {{ __('Active') }}
                                    </option>
                                    <option value="0" {{ $ad->status == '0' ? 'selected' : '' }}>
                                        {{ __('Inactive') }}
                                    </option>
                                </select>
                                <x-error name="status" />
                            </div>

                            <div class="col">
                                <x-button class="w-100" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/dracula_theme.css') }}">
@endpush


@push('libraies')
    <script src="{{ asset('assets/js/vendor/codemirror.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/codemirror_javascript.js') }}"></script>
@endpush
