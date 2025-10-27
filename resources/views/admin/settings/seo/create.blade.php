@extends('admin.layouts.admin')
@section('title', 'Add New Seo')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-5">
                    <div class="col">
                        <h5>{{ __('Create New Seo') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.seo.index') }}" class="btn btn-secondary  h-100">
                            <i class="fa-solid fa-arrow-left mx-1"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.settings.seo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="seo-form pt-3 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <label class="form-label">{{ __('Language') }} </label>
                                <select class="select-input" hidden name="lang">
                                    <option value="" selected disabled>{{ __('Choose') }}</option>
                                    @foreach (getAllLanguages() as $lang)
                                        @if (in_array($lang->code, $lang_array))
                                            <option disabled value="{{ $lang->code }}">{{ $lang->name }}</option>
                                        @else
                                            <option {{ old('lang') == $lang->code ? 'selected' : '' }}
                                                value="{{ $lang->code }}">{{ $lang->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-error name="lang" />
                            </div>

                            <div class="col">
                                <x-input name='title' required label="Title" id="title" />
                            </div>

                            <div class="col">
                                <label class="form-label">{{ __('Description') }}</label>
                                <textarea type="text" class="form-control" rows="5" name="description">{{ old('description') }}</textarea>
                                <x-error name="description" />
                            </div>

                            <div class="col">
                                <div class="tagsinput tagsinput-md">
                                    <x-input data-role="tagsinput" required name="keyword" value="{{ old('keyword') }}"
                                        label="keywords" />
                                </div>
                            </div>

                            <div class="col">
                                <x-input name='author' label="Author Name" />
                            </div>

                            <div class="col">
                                <label class="form-label">{{ __('Image Open Graph Image') }}</label>
                                <div class="upload-image">
                                    <input id="uploadImageInput" type="file" hidden name="image">
                                    <label for="uploadImageInput">{{ __('Click to Upload') }}</label>
                                    <img src="">
                                </div>
                                <x-error name="image" />
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
