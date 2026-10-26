<div class="modal fade so-modal" id="SalesOrderDeliveredModal" tabindex="-1" aria-labelledby="so-receivable-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="ReceivableformAddFromSalesOrder" class="needs-validation">
                <div class="modal-header">
                    <div><p class="so-modal-eyebrow">DELIVERED / RECEIVABLE</p><h2 class="modal-title" id="so-receivable-title">Create receivable</h2><p class="so-modal-subtitle">Review the delivered order and enter its billing details.</p></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="so-form-grid">
                        <section class="so-form-section so-form-section-last">
                            <div class="so-section-heading"><span>01</span><div><h3>Order summary</h3><p>Details from the Sales Order</p></div></div>
                            <dl class="so-record-details">
                                <div><dt>Client</dt><dd id="client_name_receivables"></dd></div>
                                <div><dt>Address</dt><dd id="client_address_receivables"></dd></div>
                                <div><dt>TIN</dt><dd id="client_tin_receivables"></dd></div>
                                <div><dt>Total due</dt><dd id="amount_receivables"></dd></div>
                            </dl>
                        </section>
                        <section class="so-form-section so-form-section-last">
                            <div class="so-section-heading"><span>02</span><div><h3>Billing details</h3><p>Required to create the receivable</p></div></div>
                            <div class="so-field"><label for="receivable_billing_date">Billing date <em>*</em></label><input type="date" class="form-control" name="receivable_billing_date" id="receivable_billing_date" required><span class="invalid-feedback" id="receivable_billing_dateError"></span></div>
                            <div class="so-field mt-3"><label for="receivable_payment_term">Payment term</label><input type="text" class="form-control" name="receivable_payment_term" id="receivable_payment_term"><span class="invalid-feedback" id="receivable_payment_termError"></span></div>
                            <div class="so-field mt-3"><label for="receivable_description">Description <em>*</em></label><textarea class="form-control" name="receivable_description" id="receivable_description" rows="3" required></textarea><span class="invalid-feedback" id="receivable_descriptionError"></span></div>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="so-footer-note">Once moved to receivables, the delivery status can no longer be edited.</small>
                    <div id="loading_data_save_receivables" style="display:none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...</div>
                    <button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="so-btn-primary" id="add-to-receivables">Create receivable</button>
                </div>
            </form>
        </div>
    </div>
</div>
