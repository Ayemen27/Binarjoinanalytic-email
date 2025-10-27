@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Upload New Plugin' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.plugins.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.plugins.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input placeholder="xxxxxxxx-xxxx-xxxx-xxx-xxxxxxxxxxxx" required
                                            name='purchase_code' label="Purchase Code" id="code" />
                                    </div>
                                    <div class="col">
                                        <x-input placeholder="Plugin File" type='file' required name='plugin_zip_file'
                                            label="plugin File" id="file" />
                                        <small>{{ __('Upload your plugin as a .zip file') }}</small>
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
