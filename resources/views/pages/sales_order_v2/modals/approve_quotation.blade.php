<div class="modal fade so-modal" id="SalesOrderQuotationApprovalModal" tabindex="-1" aria-labelledby="so-approve-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div><p class="so-modal-eyebrow">QUOTATION DECISION</p><h2 class="modal-title" id="so-approve-title">Approve quotation</h2><p class="so-modal-subtitle">This quotation will move to the Sales Order tab.</p></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="so-record-details">
                    <div><dt>Quotation no.</dt><dd id="confirm_approval_sales_control_number"></dd></div>
                    <div><dt>Date</dt><dd id="confirm_approval_sales_order_date"></dd></div>
                    <div><dt>Client</dt><dd id="confirm_approval_client_name"></dd></div>
                    <div><dt>Total due</dt><dd id="confirm_approval_total_due"></dd></div>
                </dl>
                <span class="d-none" id="confirm_approval_dr_number"></span><span class="d-none" id="confirm_approval_or_number"></span>
            </div>
            <div class="modal-footer"><button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="so-btn-primary" data-bs-dismiss="modal" id="approval_SalesOrderConfirmed" value=""><i class="bi bi-check2"></i> Approve quotation</button></div>
        </div>
    </div>
</div>
