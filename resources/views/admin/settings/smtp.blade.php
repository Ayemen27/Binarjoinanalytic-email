@extends('admin.layouts.admin')
@section('title', 'SMTP Settings')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">

            <form class="" action="{{ route('admin.settings.smtp.update') }}" method="POST">
                @csrf
                <div class="box mb-3">
                    <h5 class="mb-4">{{ __('SMTP Settings') }}</h5>
                    <div class="row row-cols-1 g-3">
                        <div class="col">
                            <x-label name="{{ __('Mail Mailer') }}" for="mailer" />
                            <select class="select-input" hidden name="mail_mailer" id="mailer">
                                <option {{ getSetting('mail_mailer') == 'sendmail' ? 'selected' : '' }} value="sendmail">
                                    {{ __('Sendmail') }}
                                </option>
                                <option {{ getSetting('mail_mailer') == 'smtp' ? 'selected' : '' }} value="smtp">
                                    {{ __('SMTP') }}
                                </option>
                            </select>
                            <x-error name="mail_mailer" />
                        </div>

                        <div
                            class="col-12 col-md-12 mailer_show {{ getSetting('mail_mailer') == 'smtp' ? ' d-block' : 'd-none' }}">
                            <x-input name='mail_host' label="{{ __('Mail Host') }}" id="mail_host" :value="is_demo() ? 'Hidden in demo' : getSetting('mail_host')" />
                            <small
                                class="form-text text-muted">{{ __('Specify the SMTP server host for sending emails.') }}</small>
                        </div>

                        <div
                            class="col-12 col-md-12 mailer_show {{ getSetting('mail_mailer') == 'smtp' ? ' d-block' : 'd-none' }}">
                            <x-input type="number" name='mail_port' label="{{ __('Mail Port') }}" id="mail_port"
                                :value="getSetting('mail_port')" />
                            <small class="form-text text-muted">
                                {{ __('Specify the SMTP server port for sending emails (e.g., 25, 465, 587).') }}
                            </small>
                        </div>

                        <div
                            class="col-12 col-md-12 mailer_show {{ getSetting('mail_mailer') == 'smtp' ? ' d-block' : 'd-none' }}">
                            <x-input name='mail_username' label="{{ __('Mail Username') }}" id="mail_user"
                                :value="is_demo() ? 'Hidden in demo' : getSetting('mail_username')" />
                            <small
                                class="form-text text-muted">{{ __('Enter the username for authenticating with the SMTP server.') }}</small>
                        </div>

                        <div
                            class="col-12 col-md-12 mailer_show {{ getSetting('mail_mailer') == 'smtp' ? ' d-block' : 'd-none' }}">
                            <x-input type="password" name='mail_password' label="{{ __('Mail Password') }}" id="mail_pass"
                                :value="is_demo() ? 'Hidden in demo' : getSetting('mail_password')" />
                            <small
                                class="form-text text-muted">{{ __('Enter the password for authenticating with the SMTP server.') }}</small>
                        </div>

                        <div
                            class="col-12 col-md-12 mailer_show {{ getSetting('mail_mailer') == 'smtp' ? ' d-block' : 'd-none' }}">
                            <label class="form-label">{{ __('Mail Encryption Type') }}</label>
                            <select class="select-input" hidden name="mail_encryption">
                                <option {{ getSetting('mail_encryption') == 'tls' ? 'selected' : '' }} value="tls">
                                    {{ __('TLS') }}</option>
                                <option {{ getSetting('mail_encryption') == 'ssl' ? 'selected' : '' }} value="ssl">
                                    {{ __('SSL') }}</option>
                            </select>
                            <small
                                class="form-text text-muted">{{ __('Select the encryption type for secure email delivery (TLS or SSL).') }}</small>
                            <x-error name="mail_encryption" />
                        </div>

                        <div class="col-12 col-md-12">
                            <x-input name='mail_from_name' label="{{ __('Name') }}" id="mail_name" :value="is_demo() ? 'Hidden in demo' : getSetting('mail_from_name')" />
                            <small
                                class="form-text text-muted">{{ __('Specify the sender\'s name that will appear in outgoing emails.') }}</small>
                        </div>

                        <div class="col-12 col-md-12">
                            <x-input required type="email" name='mail_from_address' label="{{ __('From Email') }}"
                                id="mail_address" :value="is_demo() ? 'Hidden in demo' : getSetting('mail_from_address')" />
                            <small
                                class="form-text text-muted">{{ __('Specify the email address from which the emails will be sent.') }}</small>
                        </div>

                        <div class="col-12 col-md-12">
                            <x-input required type="email" name='mail_to_address' label="{{ __('Recipient Email') }}"
                                id="mail_to_address" :value="is_demo() ? 'Hidden in demo' : getSetting('mail_to_address')" />
                            <small
                                class="form-text text-muted">{{ __('Specify the email address where the contact form submissions and alerts will be sent.') }}</small>
                        </div>


                        <div class="col-12">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>

                </div>
            </form>

            <form action="{{ route('admin.settings.smtp.send') }}" method="POST">
                @csrf
                <div class="box">
                    <div class="row row-cols-1 g-3">
                        <x-label name="Test Your SMTP" for="test_email" />
                        <div class="input-group">
                            <x-input :show-errors="false" type="email" name='test_email' placeholder="Test@gmail.com"
                                required aria-label="E-mail Address" aria-describedby="button-addon2" />
                            <button id="gen_btn" class="btn btn-primary" type="submit"
                                id="button-addon2">{{ __('Send Test Email!') }}</button>
                        </div>
                        <small class="form-text text-muted">
                            {{ __('Enter the email address where you want to send a test email to verify SMTP settings.') }}
                        </small>
                        <x-error name="test_email" />
                    </div>
                </div>
            </form>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
