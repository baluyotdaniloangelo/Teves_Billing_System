<div class="modal fade br-modal" id="ReceivableDeleteModal" tabindex="-1" aria-labelledby="ReceivableDeleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <p class="br-modal-eyebrow">BILLING / RECEIVABLE</p>
                    <h5 class="modal-title" id="ReceivableDeleteTitle">Delete receivable?</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modal-body" class="br-delete-intro">This receivable and its payment entries will be deleted.</p>
                <dl class="br-confirm-details">
                    <div><dt>Billing date</dt><dd id="confirm_delete_billing_date">—</dd></div>
                    <div><dt>Control number</dt><dd id="confirm_delete_control_number">—</dd></div>
                    <div><dt>Client</dt><dd id="confirm_delete_client_info">—</dd></div>
                    <div><dt>Description</dt><dd id="confirm_delete_description">—</dd></div>
                    <div><dt>Amount</dt><dd id="confirm_delete_amount">—</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn br-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger br-delete-button" id="deleteReceivableConfirmed" value="">
                    <i class="bi bi-trash3" aria-hidden="true"></i> Delete receivable
                </button>
            </div>
        </div>
    </div>
</div>
