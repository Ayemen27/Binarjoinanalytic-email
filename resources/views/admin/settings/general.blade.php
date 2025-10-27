@extends('admin.layouts.admin')
@section('title', 'General Settings')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('General Settings') }}</h5>
                <form action="{{ route('admin.settings.general.update') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <x-input name='site_name' required label="Site Name" :value="getSetting('site_name')" />
                        </div>
                        <div class="col">
                            <x-input name='site_url' required label="Site URL" :value="getSetting('site_url')" />
                        </div>
                        <div class="col-6">
                            <x-label name="Default Language" for="default_language" />
                            <select class="select-input" name="default_language" id="default_language">
                                @foreach (getAllLanguages() as $lang)
                                    <option {{ getSetting('default_language') == $lang->code ? 'selected' : '' }}
                                        value="{{ $lang->code }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <x-label name="Timezone" for="timezone" />
                            <select class="select-input" name="timezone" id="timezone">
                                <?php $timezone = getSetting('timezone'); ?>
                                @foreach (Config('timezones') as $key => $value)
                                    <option {{ $timezone == $key ? 'selected' : '' }} value="{{ $key }}">
                                        {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="col">
                            <x-input name='privacy_policy' label="Privacy Policy URL" :value="getSetting('privacy_policy')" />
                        </div>
                        <div class="col">
                            <x-input name='terms_of_service' label="Terms and Conditions URL" :value="getSetting('terms_of_service')" />
                        </div>
                        <div class="col">
                            <x-input name='cookie_policy' label="Cookies Policy URL" :value="getSetting('cookie_policy')" />
                        </div>
                        <div class="col">
                            <x-input name='call_to_action' label="Call To Action URL" :value="getSetting('call_to_action')" />
                        </div>
                        <div class="col-6">
                            <x-label name="Registration" for="enable_registration" />
                            <select class="select-input" hidden name="enable_registration" id="enable_registration">
                                <option {{ getSetting('enable_registration') == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ getSetting('enable_registration') == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="enable_registration" />
                        </div>
                        <div class="col-6">
                            <x-label name="Email verification" for="enable_verification" />
                            <select class="select-input" hidden name="enable_verification" id="enable_verification">
                                <option {{ getSetting('enable_verification') == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ getSetting('enable_verification') == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="enable_verification" />
                        </div>
                        <div class="col-6">
                            <x-label name="Https Force" for="https_force" />
                            <select class="select-input" hidden name="https_force" id="https_force">
                                <option {{ getSetting('https_force') == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ getSetting('https_force') == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="https_force" />
                        </div>
                        <div class="col-6">
                            <x-label name="Cookie Pop Up" for="enable_cookie" />
                            <select class="select-input" hidden name="enable_cookie" id="enable_cookie">
                                <option {{ getSetting('enable_cookie') == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ getSetting('enable_cookie') == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="enable_cookie" />
                        </div>
                        <div class="col-6">
                            <x-label name="Hide Default Lang In URL" for="hide_default_lang" />
                            <select class="select-input" hidden name="hide_default_lang" id="hide_default_lang">
                                <option {{ getSetting('hide_default_lang') == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ getSetting('hide_default_lang') == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="hide_default_lang" />
                        </div>

                        <div class="col-6">
                            <x-label name="Preloader" for="enable_preloader" />
                            <select class="select-input" hidden name="enable_preloader" id="enable_preloader">
                                <option {{ getSetting('enable_preloader') == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ getSetting('enable_preloader') == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="enable_preloader" />
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
