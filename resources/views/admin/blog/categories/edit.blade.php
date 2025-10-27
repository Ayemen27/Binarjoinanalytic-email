@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Edit Category' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.blog.categories.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.blog.categories.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='name' id="title" required label="Name" :value="$category->name" />
                                    </div>
                                    <div class="col">
                                        <x-label name="Slug" for="slug" />
                                        <div class="form-group form-group-md">
                                            <label for="slug"
                                                class="form-group-text slug_label">{{ url('/category') }}/</label>
                                            <x-input name='slug' required :show-errors="false" :value="$category->slug" />
                                        </div>
                                        <x-error name="slug" />
                                    </div>
                                    @if (countLanguages() > 1)
                                        <div class="col">
                                            <x-label name="Language" />
                                            <select class="select-input form-select-md" hidden name="lang">
                                                @foreach (getAllLanguages() as $lang)
                                                    <option {{ $category->lang == $lang->code ? 'selected' : '' }}
                                                        value="{{ $lang->code }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-error name="lang" />
                                        </div>
                                    @else
                                        <input name='lang' hidden value="{{ $category->lang }}" />
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
