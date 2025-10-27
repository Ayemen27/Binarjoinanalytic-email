@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='{{ $plugin->name }}' col="col-12 col-xl-8 col-xxl-8">
        <div class="col-auto">
            <a href="{{ route('admin.plugins.index') }}" class="btn btn-secondary h-100">
                <i class="fa-solid fa-arrow-left mx-1"></i>
                {{ __('Back') }}
            </a>
            <a target="_blank" href="{{ $plugin->url }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ $plugin->name }}" class="btn btn-outline-secondary h-100">
                <i class="fa-solid fa-arrow-up-right-from-square "></i>
            </a>
        </div>

    </x-breadcrumb>
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form class="" action="{{ route('admin.plugins.update', $plugin->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    @foreach ($plugin->code as $key => $value)
                                        <div class="col">
                                            <label class="form-label">{{ $value->title }}</label>
                                            @if ($value->type == 'select')
                                                <select class="select-input" hidden name='{{ $key }}'
                                                    {{ isset($value->disabled) ? $value->disabled : '' }}>
                                                    @foreach ($value->options as $option)
                                                        <option
                                                            {{ isset($value->value) && $value->value == $option->value ? 'selected' : '' }}
                                                            value="{{ $option->value }}">
                                                            {{ $option->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif($value->type == 'textarea')
                                                <textarea rows="5" name='{{ $key }}' {{ isset($value->disabled) ? $value->disabled : '' }}
                                                    placeholder="{{ $value->placeholder }}" class="form-control form-control-md">{{ $value->value ?? '' }}</textarea>
                                            @else
                                                @php

                                                    $inputValue = isset($value->value) ? $value->value : '';
                                                    $inputValue = is_demo() ? 'Hidden in demo' : $inputValue;
                                                    if (isset($value->is_route) && $value->is_route) {
                                                        $routeParams = isset($value->route_params)
                                                            ? $value->route_params
                                                            : [];
                                                        $inputValue = route($value->route_name, $routeParams);
                                                    }
                                                @endphp
                                                <input class="form-control form-control-md"
                                                    placeholder="{{ $value->placeholder }}" required
                                                    name='{{ $key }}' value="{{ $inputValue }}"
                                                    @if (isset($value->disabled) && $value->disabled) readonly @endif />
                                            @endif
                                            <x-error name='{{ $key }}' />
                                            @if (isset($value->info))
                                                <small class="form-text text-muted">
                                                    {!! $value->info !!}
                                                </small>
                                            @endif
                                        </div>
                                        @if (isset($value->alert))
                                            <div class="col">
                                                <div class=" alert alert-important alert-{{ $value->alert_type }} alert-dismissible br-dash-2"
                                                    role="alert">
                                                    {!! $value->alert !!}
                                                </div>
                                            </div>
                                        @endif

                                        @if (
                                            ($plugin->unique_name == 'robots' && $plugin->status) ||
                                                ($plugin->unique_name == 'google_adsense' && $plugin->status))
                                            <div class="col">
                                                <div class="alert alert-important alert-warning alert-dismissible br-dash-2"
                                                    role="alert">
                                                    {{ __('The file will be deleted if you uninstall the plugin.') }}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach


                                    @if ($plugin->status)
                                        <div class="col col-sm-9">
                                            <x-button class="w-100 btn-md" />
                                        </div>
                                        <div class="col col-sm-3">
                                            <a class="btn-danger btn-md btn w-100" href="#"
                                                onclick="event.preventDefault(); submitForm();">
                                                {{ __('Uninstall') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="col">
                                            <button class="w-100 btn-success btn-md btn" type="submit">
                                                {{ __('Save & Enable') }}
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            </form>
                            <form id="install" action="{{ route('admin.plugins.uninstall', $plugin->id) }}"
                                method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function submitForm() {
            var form = document.getElementById('install');
            if (form) {
                form.submit();
            } else {
                console.error('Form with ID "install" not found.');
            }
        }
    </script>
@endsection
