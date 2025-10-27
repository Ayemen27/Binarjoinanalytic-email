@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Themes' />

    <div class="mt-5">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3  g-3">

            @foreach ($themes as $theme)
                <div class="col">
                    <div class="box h-100 p-0">
                        <div class="themes">
                            <div class="ribbon">V {{ $theme->version }}</div>
                            <div class="themes-img">
                                <img src="{{ $theme->image }}" alt="{{ $theme->name }}" />
                                <div class="info">
                                    <h3>{{ $theme->name }}</h3>
                                </div>
                            </div>
                            <div class="themes-footer p-3 row">
                                @if ($theme->status)
                                    <div class="col">
                                        <a href="{{ route('admin.themes.appearance') }}"
                                            class="btn btn-outline-primary btn-md fw-medium w-100"><i
                                                class="fas fa-cog mx-1"></i> {{ __('Customize') }}</a>
                                    </div>
                                @else
                                    <div class="col-12 col-sm-6 mb-2">
                                        <a href="{{ route('admin.themes.active', $theme->unique_name) }}"
                                            class="btn btn-success btn-md fw-medium w-100"><i
                                                class="fas fa-download mx-1"></i>
                                            {{ __('Install') }}</a>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <a target="_blank" href="{{ $theme->demo }}"
                                            class="btn btn-secondary btn-md fw-medium w-100"><i class="fas fa-eye mx-1"></i>
                                            {{ __('Demo') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if (!empty($get_themes) && is_array($get_themes))
                @foreach ($get_themes as $theme)
                    <div class="col">
                        <div class="box h-100 p-0">
                            <div class="themes">
                                <div class="ribbon">V {{ $theme['version'] }}</div>
                                <div class="themes-img">
                                    <img src="{{ $theme['image'] }}" alt="{{ $theme['title'] }}" />
                                    <div class="info">
                                        <h3>{{ $theme['title'] }}</h3>
                                    </div>
                                </div>
                                <div class="themes-footer p-3 row">
                                    <div class="col-12 col-sm-9 mb-2">
                                        <a target="_blank" href="{{ $theme['link'] }}"
                                            class="btn btn-primary btn-md fw-medium w-100">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            {{ __('Buy Now') }} {{ $theme['discount'] }}</span>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                        <a target="_blank" href="{{ $theme['demo'] }}"
                                            class="btn btn-secondary btn-md fw-medium w-100 text-center">
                                            <i class="fas fa-eye mx-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="col">
                <div class="box h-100">
                    <div class="themes">
                        <div class="ask-ddons">
                            <a href="{{ config('lobage.support') }}" target="_blank">
                                <i class="fa-solid fa-plus"></i>
                                <h6>{{ __('Get Your Custom Theme') }}</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
