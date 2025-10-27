@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Upload New Theme' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.themes.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.themes.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input placeholder="xxxxxxxx-xxxx-xxxx-xxx-xxxxxxxxxxxx" required
                                            name='purchase_code' label="Purchase Code" id="code" />
                                    </div>
                                    <div class="col">
                                        <x-input placeholder="Theme File" type='file' required name='plugin_zip_file'
                                            label="Theme File" id="file" />
                                        <small>{{ __('Upload your theme as a .zip file') }}</small>
                                    </div>
                                    <div class="col">
                                        <x-button class="w-100">
                                            {{ __('Upload') }}
                                        </x-button>
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
