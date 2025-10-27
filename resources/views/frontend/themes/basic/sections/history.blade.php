<div class="modal fade" id="history" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">{{ translate('Email History', 'general') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <p class="modal-title text-muted ps-3 pe-5 mb-0 small">
                {{ translate('View your email history', 'general') }}</p>
            <div class="modal-body text-center modal-history">
                <div class="row row-cols-auto flex-nowrap g-3 mb-3">
                    <div class="col-12 flex-shrink-1">
                        <input type="text" id="search_history" class="form-control form-control-md"
                            placeholder="{{ translate('Type to search ... ', 'general') }}" />
                    </div>
                    <div class="col">
                        <button id="delete_history"
                            class="btn btn-danger btn-md kill">{{ translate('Delete', 'general') }}</button>
                    </div>
                </div>
                <div class="mail-history" id="mail-history">
                </div>
                <div class="mail-results-info">
                    <p class="mb-0 small">{{ translate('Emails in History', 'general') }}: <span id="history-count">0
                        </span> /
                        @php
                            $history = getFeatureValue('history');
                        @endphp
                        <span id="history-total">{!! $history == -1 ? '<i class="fa-solid fa-infinity"></i>' : $history !!}</span>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
