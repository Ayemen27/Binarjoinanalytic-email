@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Edit Faq' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.faqs.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='title' required label="title" value="{{ $faq->title }}" />
                                    </div>
                                    <div class="col">
                                        <x-label name="Description" for='content' />
                                        <textarea required class="form-control" rows="3" id='content' name="content">{{ $faq->content }}</textarea>
                                        <x-error name="content" />
                                    </div>
                                    @if (countLanguages() > 1)
                                        <div class="col">
                                            <x-label name="Language" />
                                            <select class="select-input" hidden name="lang">
                                                @foreach (getAllLanguages() as $lang)
                                                    <option {{ $faq->lang == $lang->code ? 'selected' : '' }}
                                                        value="{{ $lang->code }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-error name="lang" />
                                        </div>
                                    @else
                                        <input name='lang' hidden value="{{ $faq->lang }}" />
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
