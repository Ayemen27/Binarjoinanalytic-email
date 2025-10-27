@extends('admin.layouts.admin')
@section('title', 'Languages')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box h-300">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5 class="mb-4">{{ __('Languages') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.languages.instant') }}" class="btn btn-info h-100">
                            <i class="fa-solid fa-language"></i>
                            {{ __('Instant Translate') }}
                        </a>
                        <a href="{{ route('admin.settings.languages.create') }}" class="btn btn-primary  h-100">
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
                                    <th class="text-center">{{ __('Lang') }}</th>
                                    <th class="text-center">{{ __('Name') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse  ($languages as $lang)
                                    <tr>
                                        <td class="text-center">
                                            <img width="30" title="{{ $lang->code }}" alt='{{ $lang->code }}'
                                                src="{{ asset('assets/img/flags/' . $lang->code . '.png') }}" />
                                        </td>
                                        <td class="text-center">
                                            {{ $lang->name }}
                                        </td>
                                        <td class="text-center">
                                            @if ($lang->completionPercentage == 100)
                                                <span class="badge bg-green">{{ __('Complete') }}</span>
                                            @else
                                                <span class="badge bg-yellow">
                                                    {{ round($lang->completionPercentage, 2) }}{{ __('%Complete') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="d-flex justify-content-center">
                                            <div class="dropdown custom-dropdown">
                                                <button class="btn btn-theme cp-x-2" type="button" id="dropdownMenuButton"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a href="{{ route('admin.settings.languages.edit', $lang->id) }}"
                                                            class="dropdown-item">
                                                            <i class="far fa-edit"></i>{{ __('Edit language') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.settings.languages.translate', $lang->code) }}"
                                                            class="dropdown-item">
                                                            <i class="far fa-edit"></i>{{ __('Edit Translation') }}
                                                        </a>
                                                    </li>

                                                    @if (getSetting('default_language') != $lang->code)
                                                        <hr>
                                                        <li>
                                                            <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" data-id="{{ $lang->id }}"
                                                                data-action="{{ route('admin.settings.languages.destroy', $lang->id) }}">
                                                                <i class="fas fa-trash-alt"></i>{{ __('Delete') }}
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>


                                    </tr>
                                @empty
                                    <x-empty title="languages" />
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
