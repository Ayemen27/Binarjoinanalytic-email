@extends('install.layout')
@section('title', 'File Permissions')
@section('content')
    <div class="steps-content">
        <div class="steps-body">
            <div class="col-lg-9 col-xl-8 col-xxl-6 mx-auto">
                <div class="mb-4">
                    <h2 class="fw-light mb-4">{{ __('File Permissions') }}</h2>
                    <p class="fw-light text-muted mx-auto mb-0">
                        {{ __('Please ensure the necessary file permissions are set correctly before proceeding with the installation.') }}
                    </p>
                </div>
                <div class="text-start">
                    <div class="table">
                        <div class="table-inner">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Directory') }}</th>
                                            <th class="text-center">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($permissions as $directory => $isWritable)
                                            <tr>
                                                <td>
                                                    {{ $directory }}
                                                </td>
                                                <td class="text-end">
                                                    @if ($isWritable)
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
                    @if ($allPermissionsPass)
                        <form action="{{ route('install.filePermissions.post') }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-md w-100">{{ __('Continue') }} <i
                                    class="fas fa-arrow-right"></i></button>
                        </form>
                    @else
                        <div class="alert alert-danger" role="alert">
                            {{ __('Some file permissions are not set correctly. Please adjust the permissions and try again.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
