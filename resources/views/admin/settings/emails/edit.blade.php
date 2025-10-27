@extends('admin.layouts.admin')
@section('title', 'Edit Email Template')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100 settings-content-xl-size">
            <div class="box">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-5">
                    <div class="col">
                        <h5>{{ __('Edit ' . $email->subject . ' Template') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.emails.index') }}" class="btn btn-secondary  h-100">
                            <i class="fa-solid fa-arrow-left mx-1"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.settings.emails.update', $email->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="email-form pt-3 mt-3">
                        <div class="row row-cols-1 g-3">

                            <div class="col">
                                <x-input name='subject' required label="Subject" value="{{ $email->subject }}" />
                            </div>

                            <div class="col">
                                <label class="form-label">{{ __('Body') }}</label>
                                <textarea type="text" id="editor" class="editor form-control autosize" name="body">{{ $email->body }}</textarea>
                                <x-error name="body" />
                            </div>

                            <div class="col">
                                <label class="form-label">{{ __('Status:') }}</label>
                                <select class="select-input @error('status') is-invalid @enderror" required name="status">
                                    <option value="1" {{ $email->status == '1' ? 'selected' : '' }}>
                                        {{ __('Active') }}
                                    </option>
                                    <option value="0" {{ $email->status == '0' ? 'selected' : '' }}>
                                        {{ __('Inactive') }}
                                    </option>
                                </select>
                                <x-error name="status" />
                            </div>

                            <div class="col ">
                                <div class="shortcodes">
                                    @if ($email->shortcodes)
                                        <h5>{{ __('Available Shortcodes:') }}</h5>
                                        <ul>
                                            @foreach (json_decode($email->shortcodes, true) as $shortcode => $description)
                                                <li class="shortcode-item" data-shortcode="{{ $shortcode }}">
                                                    {{ $shortcode }} : {{ $description }}
                                                    <button type="button" class="copy-btn copy-btn-style"
                                                        title="Copy shortcode">
                                                        <i class="fa fa-copy"></i> <!-- FontAwesome copy icon -->
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
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
