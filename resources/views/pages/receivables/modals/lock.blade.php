<div class="modal fade br-modal" id="BillingReceivableLockModal" tabindex="-1" aria-labelledby="BillingReceivableLockTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <p class="br-modal-eyebrow">BILLING / ACCESS</p>
                    <h5 class="modal-title" id="BillingReceivableLockTitle">Lock settings</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="BillingReceivableLockStatusForm" novalidate>
                <div class="modal-body">
                    <dl class="br-confirm-details">
                        <div><dt>Billing date</dt><dd id="lock_receivable_billing_date">—</dd></div>
                        <div><dt>Control number</dt><dd id="lock_receivable_confirm_control_number">—</dd></div>
                        <div><dt>Client</dt><dd id="lock_receivable_client_info">—</dd></div>
                        <div><dt>Amount</dt><dd id="lock_receivable_amount">—</dd></div>
                    </dl>
                    <p class="br-lock-heading">Sections to lock</p>
                    <div class="br-lock-options">
                        <div class="form-check form-switch">
                            <input class="form-check-input lock_billing_information" type="checkbox" id="lock_billing_information" name="lock_billing_information">
                            <label class="form-check-label" for="lock_billing_information">Receivable information</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input lock_billing_item" type="checkbox" id="lock_billing_item" name="lock_billing_item">
                            <label class="form-check-label" for="lock_billing_item">Products</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input lock_billing_payment_item" type="checkbox" id="lock_billing_payment_item" name="lock_billing_payment_item">
                            <label class="form-check-label" for="lock_billing_payment_item">Payments</label>
                        </div>
                    </div>
                    <div class="br-lock-expiration">
                        <label for="receivable_unlock_expiration" class="form-label">Auto lock at</label>
                        <input type="datetime-local" class="form-control" id="receivable_unlock_expiration" name="receivable_unlock_expiration" required>
                        <span class="invalid-feedback" id="receivable_unlock_expirationError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn br-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn br-btn-primary" id="LockConfirmed" value=""><i class="bi bi-save" aria-hidden="true"></i> Save settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
