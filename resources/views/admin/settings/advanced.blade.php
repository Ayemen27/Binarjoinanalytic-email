@extends('admin.layouts.admin')
@section('title', 'Advanced Settings')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <form action="{{ route('admin.settings.advanced.update') }}" method="POST">
                @csrf
                <div class="box mb-3">
                    <h5 class="mb-4">{{ __('Advanced Settings') }}</h5>
                    <div class="row row-cols-1 g-3">
                        <div class="col col-sm-6">
                            <x-input name='imap_host' placeholder="Host Ip or Url" required label="IMAP Host"
                                value="{{ is_demo() ? 'Hidden in demo' : $imap->host }}" />
                        </div>
                        <div class="col col-sm-6">

                            <x-input name='imap_port' placeholder="993 or 143" required label="IMAP Port" type="number"
                                value="{{ is_demo() ? 'Hidden in demo' : $imap->port }}" />
                        </div>

                        <div class="col col-sm-6">
                            <x-input name='imap_user' placeholder="Username or Email" required label="IMAP Username"
                                value="{{ is_demo() ? 'Hidden in demo' : $imap->username }}" />
                        </div>

                        <div class="col col-sm-6">
                            <x-input name='imap_pass' type="password" placeholder="*********" required label="IMAP Password"
                                value="{{ is_demo() ? 'Hidden in demo' : $imap->password }}" />
                        </div>

                        <div class="col-6">
                            <x-label name="IMAP Encryption" for="imap_encryption" />
                            <select class="select-input" hidden name="imap_encryption" id="imap_encryption">
                                <option {{ $imap->encryption == 'tls' ? 'selected' : '' }} value="tls">
                                    {{ __('TLS') }}
                                </option>
                                <option {{ $imap->encryption == 'ssl' ? 'selected' : '' }} value="ssl">
                                    {{ __('SSL') }}
                                </option>
                            </select>
                            <x-error name="imap_encryption" />
                        </div>

                        <div class="col-6">
                            <x-label name="Validate Certificates" for="validate_certificates" />
                            <select class="select-input" hidden name="validate_certificates" id="validate_certificates">
                                <option {{ $imap->validate_certificates == '0' ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                                <option {{ $imap->validate_certificates == '1' ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                            </select>
                            <x-error name="validate_certificates" />
                        </div>

                        <div class="col-6">
                            <button type="button"
                                class="check_imap w-100 btn btn-md btn-secondary">{{ __('Manual check') }}</button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="checkIMAP"
                                class=" w-100 btn btn-md btn-secondary">{{ __('Auto Check & Fill') }}</button>
                        </div>

                        <div class="log mt-4">
                            <pre id='log_info'></pre>
                        </div>
                    </div>
                </div>



                <div class="box">
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <div class="tagsinput tagsinput-md">
                                <x-input data-role="tagsinput" name="forbidden_ids"
                                    value="{{ getSetting('forbidden_ids') }}" label="Forbidden IDs" />
                            </div>
                            <small class="form-text text-muted">
                                {{ __('Enter specific IDs (e.g., user => user@mydomain.com) to block from generation.') }}
                            </small>
                        </div>

                        <div class="col">
                            <div class="tagsinput tagsinput-md">
                                <x-input data-role="tagsinput" name="allowed_files"
                                    value="{{ getSetting('allowed_files') }}" label="Allowed files" />
                            </div>
                            <small class="form-text text-muted">
                                {{ __('Specify the file types allowed for attachments (e.g., .jpg, .png, .pdf).') }}
                            </small>
                        </div>


                        <div class="col col-sm-6">
                            <x-input type="number" min="5" name='fetch_messages' required
                                label="Fetch Messages Every (Seconds)" value="{{ getSetting('fetch_messages') }}" />
                            <small class="form-text text-muted">
                                {{ __('The number of seconds between fetching new messages for the main mailbox.') }}
                            </small>
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="5" name='email_length' required
                                label="The number of characters in the email" value="{{ getSetting('email_length') }}" />
                            <small class="form-text text-muted">
                                {{ __('Set the number of characters for the generated email address.') }}
                            </small>
                        </div>



                        <div class="col">
                            <x-label for="emailLifetime" name="Email Lifetime" />
                            <div class="form-group">
                                <div class="input-group">
                                    <input value="{{ getSetting('email_lifetime') }}" type="number" id="emailLifetime"
                                        name="email_lifetime" class="form-control form-control-md" min="1"
                                        value="10" required>
                                    <select id="timeUnit" name="time_unit" class="form-control form-control-md">
                                        <option {{ getSetting('time_unit') == 'minute' ? 'selected' : '' }}
                                            value="minute">{{ __('Minutes') }}</option>
                                        <option {{ getSetting('time_unit') == 'hour' ? 'selected' : '' }} value="hour">
                                            {{ __('Hours') }}</option>
                                        <option {{ getSetting('time_unit') == 'day' ? 'selected' : '' }} value="day">
                                            {{ __('Days') }}</option>
                                    </select>
                                </div>
                            </div>
                            <small
                                class="form-text text-muted">{{ __('Set the lifetime duration for the temporary emails.') }}</small>
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="0" name='history_retention_days'
                                label="Email History Retention Period (for guests)"
                                value="{{ getSetting('history_retention_days') }}" />
                            <small class="form-text text-muted">
                                {{ __('Specify the number of days to retain email history for guests before it is automatically deleted.') }}
                            </small>
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="0" name='imap_retention_days'
                                label="Mail Server Message Retention Period"
                                value="{{ getSetting('imap_retention_days') }}" />
                            <small class="form-text text-muted">
                                {{ __('Specify the number of days to retain messages in Mail Server before they are automatically deleted.') }}
                            </small>
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="0" name='fake_emails' label="Fake Emails Created"
                                value="{{ getSetting('fake_emails') }}" />
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="0" name='fake_messages' label="Fake Messages Received"
                                value="{{ getSetting('fake_messages') }}" />
                        </div>
                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection

@push('scripts')
    <!--SET DYNAMIC VARIABLE IN SCRIPT -->
    <script>
        "use strict";
        var check_link = "{{ route('admin.settings.check.imap') }}";
        var check_link_test = "{{ route('admin.settings.check.imap2') }}";
    </script>
@endpush
