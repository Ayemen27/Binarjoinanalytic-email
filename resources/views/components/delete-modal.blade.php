<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="delete-icon mx-auto">
                        <i class="fa fa-trash-alt"></i>
                    </div>
                    <h4 class="delete-title">{{ translate('Delete Item') }}</h4>
                    <p class="delete-text text-muted mb-0">
                        {{ translate('Are you sure you want to delete this item? This action cannot be undone') }}
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-outline-secondary btn-md"
                    data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-md">{{ translate('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
