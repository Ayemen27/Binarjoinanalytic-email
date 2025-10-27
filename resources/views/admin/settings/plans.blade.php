@extends('admin.layouts.admin')
@section('title', 'Account Management')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <form action="{{ route('admin.settings.plans.update') }}" method="POST">
                @csrf
                <div class="box">
                    <h5 class="mb-4">{{ __('User Settings') }}</h5>
                    <div class="row row-cols-1 g-3">
                        <div class="col-12 col-md-6">
                            <x-input type='number' required name='domains'
                                value="{{ $free->getFeatureByTag('domains')->value }}" step="1" min="-1"
                                placeholder="0" label="Custom domains" />
                            <small class="d-block form-text text-muted w-100">
                                {{ __('-1 = Unlimited ') }}
                            </small>
                        </div>


                        <div class="col-12 col-md-6">
                            <x-input type='number' required name='history'
                                value="{{ $free->getFeatureByTag('history')->value }}" step="1" min="-1"
                                placeholder="100" label="History size" />
                            <small class="d-block form-text text-muted w-100">
                                {{ __('-1 = Unlimited ') }}
                            </small>
                        </div>

                        <div class="col-12 col-md-6">
                            <x-input type='number' required name='messages'
                                value="{{ $free->getFeatureByTag('messages')->value }}" step="1" min="-1"
                                placeholder="0" label="Favorite Messages" id="messages" />

                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __(' Premium Domains ') }} </label>
                            <select class="select-input" name="premium_domains">
                                <option value="1"
                                    {{ $free->getFeatureByTag('premium_domains')->value == '1' ? 'selected' : '' }}>
                                    {{ __('On') }}
                                </option>
                                <option value="0"
                                    {{ $free->getFeatureByTag('premium_domains')->value == '0' ? 'selected' : '' }}>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            <x-error name="premium_domains" />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Ads') }} </label>
                            <select class="select-input" name="ads">
                                <option value="1" {{ $free->getFeatureByTag('ads')->value == '1' ? 'selected' : '' }}>
                                    {{ __('Show Ads') }}
                                </option>
                                <option value="0" {{ $free->getFeatureByTag('ads')->value == '0' ? 'selected' : '' }}>
                                    {{ __('Hide Ads') }}
                                </option>
                            </select>
                            <x-error name="ads" />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('See Attachments') }} </label>
                            <select class="select-input" name="attachments">
                                <option value="1"
                                    {{ $free->getFeatureByTag('attachments')->value == '1' ? 'selected' : '' }}>
                                    {{ __('On') }}
                                </option>
                                <option value="0"
                                    {{ $free->getFeatureByTag('attachments')->value == '0' ? 'selected' : '' }}>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            <x-error name="attachments" />
                        </div>

                        <hr>

                        <h5 class="mb-4">{{ __('Guest Settings') }}</h5>
                        <div class="col-12 col-md-6">
                            <x-input type='number' required name='guest_history'
                                value="{{ $guest->getFeatureByTag('history')->value }}" step="1" min="-1"
                                placeholder="100" label="History size" />
                            <small class="d-block form-text text-muted w-100">
                                {{ __('-1 = Unlimited ') }}
                            </small>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __(' Premium Domains ') }} </label>
                            <select class="select-input" name="guest_premium_domains">
                                <option value="1"
                                    {{ $guest->getFeatureByTag('premium_domains')->value == '1' ? 'selected' : '' }}>
                                    {{ __('On') }}
                                </option>
                                <option value="0"
                                    {{ $guest->getFeatureByTag('premium_domains')->value == '0' ? 'selected' : '' }}>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            <x-error name="guest_premium_domains" />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Ads') }} </label>
                            <select class="select-input" name="guest_ads">
                                <option value="1"
                                    {{ $guest->getFeatureByTag('ads')->value == '1' ? 'selected' : '' }}>
                                    {{ __('Show Ads') }}
                                </option>
                                <option value="0"
                                    {{ $guest->getFeatureByTag('ads')->value == '0' ? 'selected' : '' }}>
                                    {{ __('Hide Ads') }}
                                </option>
                            </select>
                            <x-error name="guest_ads" />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('See Attachments') }} </label>
                            <select class="select-input" name="guest_attachments">
                                <option value="1"
                                    {{ $guest->getFeatureByTag('attachments')->value == '1' ? 'selected' : '' }}>
                                    {{ __('On') }}
                                </option>
                                <option value="0"
                                    {{ $guest->getFeatureByTag('attachments')->value == '0' ? 'selected' : '' }}>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            <x-error name="guest_attachments" />
                        </div>

                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Settings -->
@endsection
