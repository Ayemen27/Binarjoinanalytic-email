@extends('admin.layouts.admin')

@section('content')
    <x-breadcrumb title='Add New Domain' col="col-12 col-xl-8 col-xxl-8" backTo="{{ route('admin.domains.index') }}" />
    <div>
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <div class="box">
                            <form action="{{ route('admin.domains.store') }}" method="POST">
                                @csrf
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <x-input name='domain' placeholder="example.com" required label="domain" />
                                    </div>
                                    <small class="d-block form-text text-muted w-100">
                                        {{ __('Add Domain Without "https://" , "/" ') }}
                                    </small>
                                    <div class="col">
                                        <x-label name="Domain Type" />
                                        <select class="select-input" hidden name="type" id="type">
                                            <option {{ old('type') == 0 ? 'selected' : '' }} value="0">
                                                {{ __('Free') }}</option>
                                            <option {{ old('type') == 1 ? 'selected' : '' }} value="1">
                                                {{ __('Premium') }}
                                            </option>
                                            <option {{ old('type') == 2 ? 'selected' : '' }} value="2">
                                                {{ __('Custom') }}
                                            </option>
                                        </select>
                                        <x-error name="type" />
                                    </div>

                                    <div class="col" id="users">
                                        <x-label name="Assign to User" />
                                        <select class="select-input" hidden name="user_id">
                                            <option value="" disabled selected>{{ __('Select User') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->getFullName() }}</option>
                                            @endforeach
                                        </select>
                                        <x-error name="user_id" />
                                    </div>

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
