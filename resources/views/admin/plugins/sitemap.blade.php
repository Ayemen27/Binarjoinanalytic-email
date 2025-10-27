@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='{{ $plugin->name }}' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.plugins.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form class="" action="{{ route('admin.plugins.sitemap') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row row-cols-1 g-3">

                                    <div class="col">
                                        <label class="form-label">{{ __('Uplaod Your SiteMap') }}</label>
                                        <input type="file" class="form-control form-control-md" name="sitemap">
                                        <x-error name='sitemap' />
                                        <small class="form-text text-muted">
                                            {{ __('Upload Your Sitemap.XML') }}
                                        </small>
                                    </div>
                                    <div class="col">
                                        <div class=" alert alert-important alert-info alert-dismissible br-dash-2"
                                            role="alert">
                                            {{ __("To verify that you published your file correctly, check that you successfully see your file's content when you access the sitemap.xml URL (https://example.com/sitemap.xml) in your web browser.") }}
                                        </div>
                                    </div>
                                    @if ($plugin->status)
                                        <div class="col">
                                            <div class="alert alert-important alert-warning alert-dismissible br-dash-2"
                                                role="alert">
                                                {{ __('The file will be deleted if you uninstall the plugin.') }}
                                            </div>
                                        </div>
                                    @endif
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
                            <form id="install" action="{{ route('admin.plugins.uninstall', $plugin->id) }}" method="POST"
                                style="display: none;">
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
