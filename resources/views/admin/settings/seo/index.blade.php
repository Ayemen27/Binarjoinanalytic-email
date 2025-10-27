@extends('admin.layouts.admin')
@section('title', 'Seo')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box h-300">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-5">
                    <div class="col">
                        <h5 class="mb-4">{{ __('Seo') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.seo.create') }}" class="btn btn-primary  h-100">
                            <i class="fa-solid fa-plus mx-1"></i>
                            {{ __('New') }}
                        </a>
                    </div>
                </div>
                <div class="table-inner  ">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('#') }}</th>
                                    <th class="text-center">{{ __('Title') }}</th>
                                    <th class="text-center">{{ __('Language') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse  ($all_seo as $seo)
                                    <tr>
                                        <td class="text-center">
                                            {{ $seo->id }}
                                        </td>
                                        <td class="text-center">
                                            {{ $seo->title }}
                                        </td>
                                        <td class="text-center">
                                            {{ $seo->lang }}
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.settings.seo.edit', $seo->id) }}"
                                                    class="btn btn-success cp-x-2">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger cp-x-2" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" data-id="{{ $seo->id }}"
                                                    data-action="{{ route('admin.settings.seo.destroy', $seo->id) }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-empty title="seo" />
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Settings Content -->
        </div>
        <x-delete-modal />
    </div>
    <!-- /Settings -->
@endsection
