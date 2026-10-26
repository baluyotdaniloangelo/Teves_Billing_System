<div class="modal fade so-modal" id="SalesOrderQuotationDisapprovalModal" tabindex="-1" aria-labelledby="so-reject-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div><p class="so-modal-eyebrow">QUOTATION DECISION</p><h2 class="modal-title" id="so-reject-title">Reject quotation</h2><p class="so-modal-subtitle">The quotation will remain in this tab with Rejected status.</p></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="so-record-details">
                    <div><dt>Quotation no.</dt><dd id="confirm_disapproval_sales_control_number"></dd></div>
                    <div><dt>Date</dt><dd id="confirm_disapproval_sales_order_date"></dd></div>
                    <div><dt>Client</dt><dd id="confirm_disapproval_client_name"></dd></div>
                    <div><dt>Total due</dt><dd id="confirm_disapproval_total_due"></dd></div>
                </dl>
                <span class="d-none" id="confirm_disapproval_dr_number"></span><span class="d-none" id="confirm_disapproval_or_number"></span>
            </div>
            <div class="modal-footer"><button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="so-btn-danger" data-bs-dismiss="modal" id="disapproval_SalesOrderConfirmed" value=""><i class="bi bi-x-lg"></i> Reject quotation</button></div>
        </div>
    </div>
</div>
