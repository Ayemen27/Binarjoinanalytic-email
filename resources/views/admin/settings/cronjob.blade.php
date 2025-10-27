@extends('admin.layouts.admin')
@section('title', 'Cron Job')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('Cron Job') }}</h5>
                <div class=" alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">
                    {{ __('The cron job command must be set to run every minute') }}
                    <strong>( * * * * * )</strong>.
                </div>
                <form action="{{ route('admin.settings.cronjob.update') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 mt-3">
                        <x-label name="Command" for="test_email" />
                        <div class="input-group mt-0 shortcode-item"
                            data-shortcode="wget -q -O /dev/null {{ route('cronjob', ['key' => is_demo() ? 'Hidden-in-demo' : getSetting('cronjob_key')]) }}">
                            <x-input x-class="dfsfd" :show-errors="false" readonly name='key'
                                value="wget -q -O /dev/null {{ route('cronjob', ['key' => is_demo() ? 'Hidden-in-demo' : getSetting('cronjob_key')]) }}"
                                required aria-label="Api Key" aria-describedby="button-addon2" />
                            <button data-bs-toggle="tooltip" data-bs-placement="top" title="Copy"
                                class="btn btn-outline-primary copy-btn" type="button"><i
                                    class="fa-solid fa-copy"></i></button>
                            <a target="_blank"
                                href="{{ route('cronjob', ['key' => is_demo() ? 'Hidden-in-demo' : getSetting('cronjob_key')]) }}"
                                data-bs-toggle="tooltip" data-bs-placement="left" title="Run manually via the browser"
                                class="btn btn-primary"><i class="fa-solid fa-arrow-up-right-from-square mt-2"></i></a>
                        </div>
                        <small>{{ __('Last Execution:') }} {{ toDate(getSetting('cronjob_last_time'), 'Y M d , h:i A') }}
                        </small>
                        <x-error name="key" />
                    </div>
                </form>
            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
