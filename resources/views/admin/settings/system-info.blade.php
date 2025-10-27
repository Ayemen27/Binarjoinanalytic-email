@extends('admin.layouts.admin')
@section('title', 'System Info')
@section('content')
    <div class="settings">
        @include('admin.partials.settings')
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('System Information') }}</h5>

                <ul class="list-group">
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('PHP Version') }}</strong>
                        <span>{{ $phpVersion }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Laravel Version') }}</strong>
                        <span>{{ $laravelVersion }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Server Software') }}</strong>
                        <span>{{ $serverSoftware }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Operating System') }}</strong>
                        <span>{{ $operatingSystem }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Timezone') }}</strong>
                        <span>{{ $timezone }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Memory Usage') }}</strong>
                        <span>{{ $memoryUsage }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Max Upload Size') }}</strong>
                        <span>{{ $maxUploadSize }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Max Execution Time') }}</strong>
                        <span>{{ $maxExecutionTime }} seconds</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Disk Free Space') }}</strong>
                        <span>{{ $diskFreeSpace }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Disk Total Space') }}</strong>
                        <span>{{ $diskTotalSpace }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Database Connection') }}</strong>
                        <span>{{ $dbConnection }}</span>
                    </li>
                    <li class="list-group-item system-list d-flex justify-content-between align-items-center">
                        <strong>{{ __('Database Version') }}</strong>
                        <span>{{ $dbVersion }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
