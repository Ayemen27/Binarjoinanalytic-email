@extends('admin.layouts.admin')
@section('title', 'Instant Translate')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">

            <div class="box">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5>{{ __('Instant Translate') }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.languages.index') }}" class="btn btn-secondary  h-100">
                            <i class="fa-solid fa-arrow-left mx-1"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-3">
                        <div class="col">
                            <div class="box h-100">
                                <div class="counter">
                                    <div class="counter-info">
                                        <p class="counter-amount">
                                            {{ $response['data']['credits'] - $response['data']['used_credits'] }}</p>
                                        <h6 class="counter-title">{{ __('Remaining Credits') }}</h6>
                                    </div>
                                    <div class="counter-icon">
                                        <i class="fa-solid fa-coins"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="box h-100">
                                <div class="counter">
                                    <div class="counter-info">
                                        <p class="counter-amount">{{ $response['data']['used_credits'] }}</p>
                                        <h6 class="counter-title">{{ __('Used Credits') }}</h6>
                                    </div>
                                    <div class="counter-icon">
                                        <i class="fa-solid fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="box h-100">
                                <div class="counter">
                                    <div class="counter-info">
                                        <p class="counter-amount">
                                            {{ $isExpired == true ? 'Expired' : toDate($response['data']['valid_until'], 'Y-m-d') }}
                                        </p>
                                        <h6 class="counter-title">{{ __('Valid Until') }}</h6>
                                    </div>
                                    <div class="counter-icon">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="box h-100">
                                <div class="counter">
                                    <div class="counter-info">
                                        <p class="counter-amount">{{ $response['data']['credits'] }}</p>
                                        <h6 class="counter-title">{{ __('Credits') }}</h6>
                                    </div>
                                    <div class="counter-icon">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <form action="{{ route('admin.settings.languages.instant.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="pt-3 mt-3">
                        <div class="row row-cols-1 g-5">

                            <div class="col">
                                <div class="mb-1">
                                    <label class="form-label d-block fs-20">{{ __('Languages') }}:</label>
                                    <input class="form-check-input" type="checkbox" id="selectAllLanguages">
                                    <label class="form-label"
                                        for="selectAllLanguages"><strong>{{ __('Select All') }}</strong></label>
                                    <span id="totalLanguagesSelected">({{ __('0 Selected') }})</span>
                                </div>

                                @foreach (config('languages') as $key => $lang)
                                    <div class="form-check form-check-inline lang-check">
                                        <input class="form-check-input language-checkbox" name="lang[]"
                                            id="{{ $key }}" type="checkbox" value="{{ $key }}">
                                        <label class="form-check-label d-flex align-items-center"
                                            for="{{ $key }}">
                                            <img src="{{ asset('assets/img/flags/' . $key . '.png') }}" width="20"
                                                class="flag-img me-2">
                                            {{ '(' . $key . ')' . $lang['name'] }}
                                        </label>
                                    </div>
                                @endforeach

                                <x-error name="lang" />

                            </div>



                            <div class="col">
                                <div class="mb-1">
                                    <label class="form-label d-block fs-20">{{ __('Translate Collections') }}:</label>
                                    <input class="form-check-input" type="checkbox" name="all_collections"
                                        id="selectAllOptions">
                                    <label class="form-label"
                                        for="selectAllOptions"><strong>{{ __('Select All') }}</strong></label>
                                    <span id="totalOptionsSelected">({{ __('0 Selected') }})</span>
                                </div>
                                @foreach ($unique_groups as $group)
                                    <div class="form-check form-check-inline lang-check">
                                        <input class="form-check-input option-checkbox" name="collections[]" type="checkbox"
                                            value="{{ $group }}" id="{{ $group }}">
                                        <label class="form-check-label"
                                            for="{{ $group }}">{{ $group }}</label>
                                    </div>
                                @endforeach

                                <x-error name="collections" />
                            </div>


                            <div class="col">
                                <div class="mb-1">
                                    <label class="form-label d-block fs-20">{{ __('More Translate Options') }}:</label>
                                </div>
                                <div class="form-check form-check-inline lang-check">
                                    <input class="form-check-input" id="faqs" name="options[]" type="checkbox"
                                        value="faqs">
                                    <label class="form-check-label" for="faqs">{{ __('FAQs') }}</label>
                                </div>
                                <div class="form-check form-check-inline lang-check">
                                    <input class="form-check-input " id="features" name="options[]" type="checkbox"
                                        value="features">
                                    <label class="form-check-label" for="features">{{ __('Features') }}</label>
                                </div>
                            </div>

                            <div class="col">
                                @if ($isExpired)
                                    <x-button disabled class="w-100">
                                        {{ __('Add New Key') }}
                                    </x-button>
                                @else
                                    <x-button class="w-100">
                                        {{ __('Translate') }}
                                    </x-button>
                                @endif
                            </div>

                            <div class="col mt-3">
                                <div class="alert alert-important alert-info alert-dismissible br-dash-2 mt-3"
                                    role="alert">
                                    <div class="d-flex">
                                        <div>
                                            <strong>{{ __('Note:') }}</strong>
                                            {{ __('This translation is generated by AI to assist you.
                                                                                                                                                                                While we strive for accuracy, some errors may occur. If needed, please
                                                                                                                                                                                double-check important details.') }}
                                        </div>
                                    </div>
                                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                                </div>

                                <div class="alert alert-important alert-warning alert-dismissible br-dash-2"
                                    role="alert">
                                    <div class="d-flex">
                                        <div>
                                            <strong>{{ __('This plugin requires a cron job.') }}</strong>
                                            {{ __('Without a configured cron job, automatic functionality will not work.') }}
                                            <br>
                                            <strong>{{ __('Last cron job run:') }}</strong>
                                            {{ getSetting('cronjob_last_time') != null ? toDiffForHumans(getSetting('cronjob_last_time')) : 'It has never been run' }}
                                        </div>
                                    </div>
                                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>


            </div>

            <div class="box mt-4">
                <div class="row row-cols-auto g-2 justify-content-between align-items-center mb-3">
                    <div class="col">
                        <h5>{{ __('History') }}</h5>
                    </div>

                </div>

                <div class="table-inner ">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('ID') }}</th>
                                    <th class="text-center">{{ __('Total Characters') }}</th>
                                    <th class="text-center">{{ __('Processing') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($translation_jobs as $job)
                                    <tr>



                                        <td class="text-center">{{ $job->job_id }} @if (isset($job->message))
                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $job->message }}" class=""><i
                                                        style="    color: #cf084f;"
                                                        class="fa-solid fa-triangle-exclamation"></i></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $job->total_characters }}</td>
                                        <td class="text-center">
                                            @php
                                                $percentage = ($job->processed_chunks / $job->total_chunks) * 100;
                                            @endphp
                                            @if ($percentage == 100)
                                                <span class="badge bg-green-lt">100%</span>
                                            @else
                                                <span class="badge bg-orange-lt">{{ round($percentage, 2) }}%</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($job->status == 'completed')
                                                <span class="badge bg-green">{{ __('Completed') }}</span>
                                            @elseif($job->status == 'processing')
                                                <span class="badge bg-orange">{{ __('Processing') }}</span>
                                            @else
                                                <span class="badge bg-red">{{ __('Canceling') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($job->status == 'completed' || $job->status == 'canceling')
                                                <button type="button" class="btn btn-primary btn-sm view-details-btn"
                                                    data-job-id="{{ $job->job_id }}">
                                                    <i class="fa-solid fa-eye"></i> {{ __('View Details') }}
                                                </button>
                                            @else
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" data-id="{{ $job->id }}"
                                                    data-action="{{ route('admin.settings.languages.instant.stop', $job->id) }}">
                                                    <i class="fa-solid fa-hand"></i>{{ __(' Stop Process') }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            {{ __('No translation jobs available.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-footer mt-3">
                    <div class="row row-cols-auto justify-content-between align-items-center g-3">
                        <div class="col">
                            <span>
                                {{ __('Showing') }} {{ $translation_jobs->firstItem() }} {{ __('to') }}
                                {{ $translation_jobs->lastItem() }} {{ __('of') }}
                                {{ $translation_jobs->total() }} {{ __('entries') }}
                            </span>
                        </div>
                        <div class="col">
                            {{ $translation_jobs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>


            </div>
            <!-- /Settings Content -->
        </div>


        <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <h4 id="modal-title"></h4>
                            <pre id="modal-body" class="text-start" style="height: 500px">Loading...</pre>
                        </div>
                    </div>
                    <div class="modal-footer
                                justify-content-center border-0">
                        <button type="button" class="btn btn-outline-secondary btn-md"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <x-stop-modal />
    </div>
    <!-- /Settings -->
@endsection
