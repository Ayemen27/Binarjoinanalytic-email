<div class="modal fade" id="changeEmail">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">{{ translate('Change Email', 'general') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ translate('Email Alias', 'general') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-md" id="random_code_input">
                        <button type="button" id="random_code"
                            class="btn btn-primary btn-md">{{ translate('Random Name', 'general') }}</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Domain', 'general') }}</label>
                    <select class="form-select form-select-md select-input" id="name_domain">

                        @if (count($custom_domains) > 0)
                            <optgroup label="{{ translate('My Domains', 'general') }}">
                                @foreach ($custom_domains as $domain)
                                    <option value="{{ $domain }}" data-icon="fa-crown">
                                        {{ $domain }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if (count($premium_domains) > 0)
                            <optgroup label="{{ translate('Premium Domains', 'general') }}">
                                @foreach ($premium_domains as $domain)
                                    <option {{ $can_use_premium_domains ? '' : 'disabled' }} value="{{ $domain }}"
                                        data-icon="fa-crown">
                                        {{ $domain }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        <optgroup label="{{ translate('Free Domains', 'general') }}">
                            @foreach ($free_domains as $domain)
                                <option value="{{ $domain }}">
                                    {{ $domain }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <button id="change_email"
                    class="btn btn-primary kill btn-md w-100">{{ translate('Update Email Address', 'general') }}</button>
            </div>
        </div>
    </div>
</div>
