@extends('admin.layouts.admin')
@section('title', 'Maintenance Mode')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('Maintenance Mode') }}</h5>


                <form action="{{ route('admin.settings.maintenance.update') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 mt-3">

                        <div class="col col-sm-6">
                            <x-label name="Maintenance Mode" for="enable_maintenance" />
                            <select class="select-input" hidden name="enable_maintenance" id="enable_maintenance">
                                <option {{ env('MAINTENANCE_MODE') == true ? 'selected' : '' }} value="true">
                                    {{ __('Enabled') }}
                                </option>
                                <option {{ env('MAINTENANCE_MODE') == false ? 'selected' : '' }} value="false">
                                    {{ __('Disabled') }}
                                </option>
                            </select>
                            <x-error name="enable_blog" />
                        </div>

                        <div class="col col-sm-6">
                            <x-label name="App Debug" for="app_debug" />
                            <select class="select-input" hidden name="app_debug" id="app_debug">
                                <option {{ env('APP_DEBUG') == true ? 'selected' : '' }} value="true">
                                    {{ __('Enabled') }}
                                </option>
                                <option {{ env('APP_DEBUG') == false ? 'selected' : '' }} value="false">
                                    {{ __('Disabled') }}
                                </option>
                            </select>
                            <x-error name="app_debug" />
                        </div>

                        <div class="col ">
                            <x-input name='title' label="Title" value="{{ getSetting('maintenance_title') }}" />
                        </div>

                        <div class="col">
                            <x-label name="Message" for='message' />
                            <textarea required class="form-control" rows="3" id='message' name="message">{{ getSetting('maintenance_message') }}</textarea>
                            <x-error name="message" />
                        </div>

                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
