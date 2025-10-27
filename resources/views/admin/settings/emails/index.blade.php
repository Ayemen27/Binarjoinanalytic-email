@extends('admin.layouts.admin')
@section('title', 'Email Templates')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box h-300">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-5">
                    <div class="col">
                        <h5 class="mb-4">{{ __('Email Templates') }}</h5>
                    </div>
                </div>
                <div class="table-inner  ">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('Template') }}</th>
                                    <th class="text-start">{{ __('Subject') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse  ($emails as $email)
                                    <tr>
                                        <td class="text-start">
                                            {{ str_replace('_', ' ', $email->alias) }}
                                        </td>
                                        <td class="text-start">
                                            {{ $email->subject }}
                                        </td>
                                        <td class="text-center">
                                            @if ($email->status)
                                                <span class="badge bg-green">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-red">
                                                    {{ __('Inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.settings.emails.edit', $email->id) }}"
                                                    class="btn btn-success cp-x-2">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-empty title="email template" />
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
