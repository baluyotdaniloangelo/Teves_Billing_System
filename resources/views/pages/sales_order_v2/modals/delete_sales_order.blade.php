<div class="modal fade so-modal" id="SalesOrderDeleteModal" tabindex="-1" aria-labelledby="so-delete-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div><p class="so-modal-eyebrow">RECORD MANAGEMENT</p><h2 class="modal-title" id="so-delete-title">Delete record</h2><p class="so-modal-subtitle">Please confirm the order details before deleting.</p></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="so-record-details">
                    <div><dt>Order no.</dt><dd id="confirm_delete_sales_control_number"></dd></div>
                    <div><dt>Date</dt><dd id="confirm_delete_sales_order_date"></dd></div>
                    <div><dt>Client</dt><dd id="confirm_delete_client_name"></dd></div>
                    <div><dt>Total due</dt><dd id="confirm_delete_total_due"></dd></div>
                </dl>
                <span class="d-none" id="confirm_delete_dr_number"></span><span class="d-none" id="confirm_delete_or_number"></span>
            </div>
            <div class="modal-footer"><button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="so-btn-danger" data-bs-dismiss="modal" id="deleteSalesOrderConfirmed" value=""><i class="bi bi-trash3"></i> Delete record</button></div>
        </div>
    </div>
</div>
