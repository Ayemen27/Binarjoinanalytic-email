@extends('admin.layouts.admin')
@section('title', 'Cache Management')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('Cache Management') }}</h5>

                <div class=" alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">
                    {{ __('The application uses local file caching to enhance performance by efficiently storing and retrieving
                                                                                                                                                                                                        frequent data') }}
                </div>
                <div class="row row-cols-1 g-3 mt-3">
                    <div class="col">
                        <x-label name="Current cache size" for="cache" />
                        <input id="cache" class="form-control form-control-md m-0 " readonly
                            value="{{ $cacheSize }}">

                    </div>
                    <div class="col">
                        <a href="{{ route('admin.settings.cache.clear') }}"
                            class="w-100 btn btn-md btn-primary ">{{ __('Clear Cache') }}</a>
                    </div>
                </div>

            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
