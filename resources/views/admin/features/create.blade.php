@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Add New Feature' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.features.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.features.store') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='title' required label="title" />
                                    </div>

                                    <div class="col">
                                        <x-input name='icon' required label="icon"
                                            placeholder="<i class='fas fa-home'></i>" />
                                        <span class="small"> {{ __('Get icon code') }} <a
                                                href="https://fontawesome.com/icons" target="_black">{{ __('Here') }}
                                            </a></span>
                                    </div>
                                    <div class="col">
                                        <x-label name="Description" for='content' />
                                        <textarea required class="form-control" rows="3" id='content' name="content">{{ old('content') }}</textarea>
                                        <x-error name="content" />
                                    </div>
                                    @if (countLanguages() > 1)
                                        <div class="col">
                                            <x-label name="Language" />
                                            <select class="select-input" hidden name="lang">
                                                @foreach (getAllLanguages() as $lang)
                                                    <option {{ old('lang') == $lang->code ? 'selected' : '' }}
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
