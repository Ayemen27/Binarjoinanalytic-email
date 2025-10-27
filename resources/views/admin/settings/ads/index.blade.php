@extends('admin.layouts.admin')
@section('title', 'Ads')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box h-300">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5 class="mb-4">{{ __('Ads') }}</h5>
                    </div>
                </div>
                <div class="table-inner">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('#') }}</th>
                                    <th class="text-center">{{ __('Ad Name') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ads as $ad)
                                    <tr>
                                        <td class="text-center">
                                            {{ $ad->id }}
                                        </td>
                                        <td class="text-center">
                                            {{ $ad->name }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $ad->getStatusColor() }}">
                                                {{ $ad->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.settings.ads.edit', $ad->id) }}"
                                                    class="btn btn-success cp-x-2">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
