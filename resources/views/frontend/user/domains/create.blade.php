@extends('frontend.user.layouts.app')

@section('content')
    <x-breadcrumb :nav="false" title="{{ translate('Add New Domain', 'general') }}" col="col-12 col-xl-8 col-xxl-8"
        backTo="{{ route('domains.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box mb-3">
                            <form action="{{ route('domains.store') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='domain' placeholder="example.com" required
                                            label="{{ translate('Domain Name', 'general') }}" />
                                    </div>
                                    <small class="d-block form-text text-muted w-100">
                                        {{ translate('Add Domain Without "https://" , "/" ', 'general') }}
                                    </small>
                                    <div class="col">
                                        <x-button class="w-100">
                                            {{ translate('Add New Domain', 'general') }}
                                        </x-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box mb-3">
                            {!! translate('How To Setup A Custom Domain', 'html') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
