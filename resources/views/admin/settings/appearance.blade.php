@extends('admin.layouts.admin')
@section('title', 'Panel Appearance')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <form action="{{ route('admin.settings.appearance.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="box">
                    <h5 class="mb-4">{{ __('َPanel Appearance') }}</h5>
                    <div class="row row-cols-1 g-3">
                        @php
                            $colors = json_decode(getSetting('colors'), true); // Decoding JSON string into an array
                        @endphp
                        @foreach ($colors as $key => $value)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }} </label>
                                <div class="input-group colorPicker" data-color="{{ $value }}">
                                    <input name="{{ $key }}" type="text" class="form-control form-control-md"
                                        value="{{ $value }}">
                                    <button class="btn btn-default" type="button">&nbsp;</button>
                                </div>
                                <x-error name="{{ $key }}" />
                            </div>
                        @endforeach
                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/izoColorPicker.css') }}">
@endpush


@push('libraies')
    <script src="{{ asset('assets/js/vendor/izoColorPicker.js') }}"></script>
@endpush
