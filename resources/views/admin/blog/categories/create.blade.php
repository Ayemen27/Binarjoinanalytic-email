@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Add New Category' col="col-12 col-xl-8 col-xxl-8"
        backTo="{{ route('admin.blog.categories.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.blog.categories.store') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='name' id="title" required label="Name" />
                                    </div>
                                    <div class="col">
                                        <x-label name="Slug" for="slug" />
                                        <div class="form-group form-group-md">
                                            <label for="slug"
                                                class="form-group-text slug_label">{{ url('/category') }}/</label>
                                            <x-input name='slug' required :show-errors="false" />
                                        </div>
                                        <x-error name="slug" />
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

@push('scripts')
    <!--SET DYNAMIC VARIABLE IN SCRIPT -->
    <script type="text/javascript">
        var checkslug_title = "{{ route('admin.settings.checkslug', 'categories') }}";
    </script>
@endpush
