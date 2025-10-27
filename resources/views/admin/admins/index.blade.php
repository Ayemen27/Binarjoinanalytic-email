@extends('admin.layouts.admin')
@section('title', 'Admins')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box h-300">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5 class="mb-4">{{ __('Admins') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary  h-100">
                            <i class="fa-solid fa-plus mx-1"></i>
                            {{ __('New') }}
                        </a>
                    </div>
                </div>
                <div class="table-inner">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('#') }}</th>
                                    <th class="">{{ __('Full Name') }}</th>
                                    <th class="text-center">{{ __('Create At') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse  ($admins as $admin)
                                    <tr>
                                        <td class="text-center">
                                            {{ $admin->id }}
                                        </td>
                                        <td>
                                            <div class="user">
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="user-img">
                                                    <img src="{{ asset($admin->avatar) }}" alt="avatar" />
                                                </a>
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                                    class="user-name">{{ $admin->getFullName() }}</a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ toDate($admin->created_at, 'Y-m-d') }}
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                                    class="btn btn-success cp-x-2">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" data-id="{{ $admin->id }}"
                                                    data-action="{{ route('admin.admins.destroy', $admin->id) }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-empty title="admins" />
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-footer mt-3">
                    <div class="row row-cols-auto justify-content-between align-items-center g-3">
                        <div class="col">
                            <span>
                                {{ __('Showing') }} {{ $admins->firstItem() }} {{ __('to') }}
                                {{ $admins->lastItem() }} {{ __('of') }}
                                {{ $admins->total() }} {{ __('entries') }}
                            </span>
                        </div>
                        <div class="col">
                            {{ $admins->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Settings Content -->
        </div>
        <x-delete-modal />
    </div>
    <!-- /Settings -->
@endsection
