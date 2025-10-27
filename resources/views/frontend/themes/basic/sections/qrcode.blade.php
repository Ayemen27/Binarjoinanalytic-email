<div class="modal fade" id="qrCode" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 py-3">
                <h5>{{ translate('Scan the QR Code', 'general') }}</h5>
                <p class="text-muted mb-3">
                    {{ translate('Use this QR code to quickly open your inbox on any compatible device', 'general') }}
                </p>
                <div class="mb-5">
                    <div id="qrcode"></div>
                </div>
                <button class="btn btn-primary btn-md w-100"
                    data-bs-dismiss="modal">{{ translate('Back to Inbox', 'general') }}</button>
            </div>
        </div>
    </div>
</div>
