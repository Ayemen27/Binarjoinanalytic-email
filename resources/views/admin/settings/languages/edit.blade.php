@extends('admin.layouts.admin')
@section('title', 'Edit language')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5>{{ __('Edit language') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.languages.index') }}" class="btn btn-secondary  h-100">
                            <i class="fa-solid fa-arrow-left mx-1"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.settings.languages.update', $language->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="pt-3 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <x-input name='name' required label="Name" value="{{ $language->name }}" />
                            </div>

                            <div class="col">
                                <label for="lang" class="form-label">{{ __('Language Code') }} </label>
                                <select class="select2-img" name="lang" id="lang">
                                    @foreach (\File::files('assets/img/flags') as $path)
                                        <option @if (in_array(pathinfo($path)['filename'], $lang_array)) disabled @endif
                                            {{ $language->code == pathinfo($path)['filename'] ? 'selected' : '' }}
                                            value="{{ pathinfo($path)['filename'] }}"><span>
                                                {{ pathinfo($path)['filename'] }}</span>
                                        </option>
                                    @endforeach
                                </select>
                                <x-error name="lang" />
                            </div>
                            <div class="col">
                                <x-label name="Direction" for="direction" />
                                <select class="select-input" name="direction" id="direction">
                                    <option value="0" {{ $language->direction == '0' ? 'selected' : '' }}>
                                        {{ __('LTR') }}
                                    </option>
                                    <option value="1" {{ $language->direction == '1' ? 'selected' : '' }}>
                                        {{ __('RTL') }}
                                    </option>
                                </select>
                                <x-error name="direction" />
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
