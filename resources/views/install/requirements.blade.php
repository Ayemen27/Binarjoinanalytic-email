@extends('install.layout')
@section('title', 'Requirements')

@section('content')
    <div class="steps-content">
        <div class="steps-body">
            <div class="col-lg-9 col-xl-8 col-xxl-6 mx-auto">
                <div class="mb-4">
                    <h2 class="fw-light mb-4">{{ __('Requirements') }}</h2>
                    <p class="fw-light text-muted mx-auto mb-0">
                        {{ __('Please ensure that your server meets the following requirements before proceeding with the installation.') }}
                    </p>
                </div>
                <div class="text-start">
                    <div class="table">
                        <div class="table-inner">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Extensions') }}</th>
                                            <th class="text-center">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ __('PHP Version') }}
                                            </td>
                                            <td class="text-center">
                                                @if ($requirements['php_version'])
                                                    <div class="bg-success req-circle">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                @else
                                                    <div class="bg-danger req-circle">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($requirements['extensions'] as $extension => $status)
                                            <tr>
                                                <td>
                                                    {{ ucfirst($extension) }} {{ __('Extension') }}
                                                </td>
                                                <td class="text-end">
                                                    @if ($status)
                                                        <div class="bg-success req-circle">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-danger req-circle">
                                                            <i class="fa fa-times"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    @if ($allRequirementsPass)
                        <form action="{{ route('install.requirements.post') }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-md w-100">{{ __('Continue') }} <i
                                    class="fas fa-arrow-right"></i></button>
                        </form>
                    @else
                        <div class="alert alert-danger" role="alert">
                            {{ __('Some requirements are not met. Please ensure all requirements are met before proceeding.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
