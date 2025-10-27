@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Edit Menu' col="col-12 col-xl-8 col-xxl-8"
        backTo="{{ $menu->type == 1 ? route('admin.menus.footer') : route('admin.menus.header') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <input type="hidden" name="type" value="{{ $menu->type }}" />
                                        <x-input name='name' required label="name" value="{{ $menu->name }}" />
                                    </div>
                                    <div class="col">
                                        <x-input value="{!! $menu->icon !!}" name='icon' label="icon ( Optional )"
                                            placeholder="<i class='fas fa-home'></i>" />
                                        <span class="small"> {{ __('Get icon code') }} <a
                                                href="https://fontawesome.com/icons.15/icons"
                                                target="_black">{{ __('Here') }}
                                            </a></span>
                                    </div>
                                    <div class="col">
                                        <x-input name='url' value="{{ $menu->url }}" type="url" required
                                            label="URL" />
                                    </div>
                                    <div class="col">
                                        <label class="form-label">{{ __('Open In New Tab:') }} <span
                                                class="red">*</span></label>
                                        <select class="select-input" name="is_external">
                                            <option value="0" {{ $menu->is_external == '0' ? 'selected' : '' }}>
                                                {{ __('No') }}
                                            </option>
                                            <option value="1" {{ $menu->is_external == '1' ? 'selected' : '' }}>
                                                {{ __('Yes') }}
                                            </option>
                                        </select>
                                        <x-error name="is_external" />
                                    </div>
                                    @if (countLanguages() > 1)
                                        <div class="col">
                                            <x-label name="Language" />
                                            <select class="select-input" hidden name="lang">
                                                @foreach (getAllLanguages() as $lang)
                                                    <option {{ $menu->lang == $lang->code ? 'selected' : '' }}
                                                        value="{{ $lang->code }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-error name="lang" />
                                        </div>
                                    @else
                                        <input name='lang' hidden value="{{ getSetting('default_language') }}" />
                                    @endif
                                    <div class="col">
                                        <x-button class="w-100" />
                                    </div>
                                </div>
                            </form>

                            <div class="col mt-3">
                                <div class="shortcodes">

                                    <h6>{{ __('Helpful links:') }}</h6>
                                    <ul>
                                        @foreach (config('lobage.shortcodes') as $shortcode => $description)
                                            <li class="shortcode-item" data-shortcode="{{ url($shortcode) }}">
                                                {{ $description }}
                                                <button type="button" class="copy-btn copy-btn-style"
                                                    title="Copy shortcode">
                                                    <i class="fa fa-copy"></i> <!-- FontAwesome copy icon -->
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
