@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Plugins' />

    <div class="mb-4">
        <div class="row justify-content-between g-3">
            <div class="col-12 col-xxl-8">
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <div class="col">
                        <select id="tagSelector" class="form-select form-select-md">
                            <option value="all">{{ __('All Plugins') }}</option>
                            <option value="addon">{{ __('Paid Plugins') }}</option>
                            <option value="analytics">{{ __('Analytics') }}</option>
                            <option value="support">{{ __('Customer Support') }}</option>
                            <option value="marketing">{{ __('Marketing') }}</option>
                            <option value="auth">{{ __('Authentication') }}</option>
                            <option value="security">{{ __('Security') }}</option>
                            <option value="others">{{ __('Others') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3  g-3">
            @if (!empty($get_plugins) && is_array($get_plugins))
                @foreach ($get_plugins as $plugin)
                    <div class="col plugin" data-tags="addon">
                        <div class="box h-100 p-0">
                            <div class="addons">
                                <div class="ribbon">{{ __('Paid') }}</div>
                                <div class="addons-header">
                                    <div class="addons-image">
                                        <img src="{{ $plugin['image'] }}" alt="{{ $plugin['title'] }}" />
                                    </div>
                                    <div class="addons-info">
                                        <div class="addons-meta">
                                            <h6 class="addons-title">{{ $plugin['title'] }}</h6>
                                            <div class="addons-state">
                                                <div class="addons-rating">
                                                    <i class="fa fa-star"></i>
                                                    <span>{{ $plugin['rating'] }}</span>
                                                    <span class="text-muted">(v {{ $plugin['version'] }})</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($plugin['demo']))
                                            <div class="addons-action">
                                                <a target="_blank" href="{{ $plugin['demo'] }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Demo">
                                                    <i class="fa-solid fa-eye nav-icon"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="addons-body">
                                    <p class="addons-desc">
                                        {{ $plugin['description'] }}
                                    </p>
                                </div>

                                <div class="addons-footer">
                                    <a target="_blank" href="{{ $plugin['link'] }}"
                                        class="btn btn-secondary btn-md fw-medium w-100">
                                        <i class="fa-solid fa-cart-plus"></i>
                                        {{ __('Buy Now') }} {{ $plugin['discount'] }}</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            @foreach ($plugins as $plugin)
                <div class="col plugin" data-tags="{{ $plugin->tag }}">
                    <div class="box h-100 p-0">
                        <div class="addons">
                            <div class="addons-header">
                                <div class="addons-image">
                                    <img src="{{ asset($plugin->logo) }}" alt="{{ $plugin->name }}" />
                                </div>
                                <div class="addons-info">
                                    <div class="addons-meta">
                                        <h6 class="addons-title">{{ $plugin->name }}</h6>
                                        @if ($plugin->status)
                                            <div class="addons-state">
                                                <div class="addons-connected">
                                                    <span>{{ __('Connected') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="addons-state">
                                                <div class="addons-rating">
                                                    <span>v {{ $plugin->version }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="addons-action">
                                        <a target="_blank" href="{{ $plugin->url }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ $plugin->name }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="addons-body">
                                <p class="addons-desc">
                                    {{ $plugin->description }}
                                </p>
                            </div>
                            <div class="addons-footer">
                                @if ($plugin->status)
                                    @if (!empty($plugin->action))
                                        <a href="{{ route($plugin->action) }}"
                                            class="btn btn-outline-primary btn-md fw-medium w-100"><i
                                                class="fas fa-cog mx-1"></i> {{ __('Settings') }}</a>
                                    @else
                                        <a href="{{ route('admin.plugins.settings', $plugin->unique_name) }}"
                                            class="btn btn-outline-primary btn-md fw-medium w-100"><i
                                                class="fas fa-cog mx-1"></i> {{ __('Settings') }}</a>
                                    @endif
                                @else
                                    <a href="{{ route('admin.plugins.settings', $plugin->unique_name) }}"
                                        class="btn btn-primary btn-md fw-medium w-100"><i class="fas fa-download mx-1"></i>
                                        {{ __('Install') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col">
                <div class="box h-100 p-0">
                    <div class="addons">
                        <div class="ask-ddons">
                            <a target="_blank" href="{{ config('lobage.support') }}">
                                <i class="fa-solid fa-plus"></i>
                                <h6>{{ __('Get Your Custom Plugin') }}</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
