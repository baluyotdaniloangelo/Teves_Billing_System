<div class="modal fade so-modal so-edit-workspace" id="SalesOrderEditWorkspaceModal" data-editor-url="{{ route('sales_order_form') }}" tabindex="-1" aria-labelledby="sales-order-edit-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <p class="so-modal-eyebrow">EDIT SALES ORDER</p>
                    <h2 class="modal-title" id="sales-order-edit-title">Sales Order details</h2>
                    <p class="so-modal-subtitle">Sales Order Information, products, delivery, payment, and receivable details.</p>
                    <span class="so-edit-reference" id="sales-order-edit-number"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close Sales Order editor"></button>
            </div>
            <div class="so-edit-body">
                <div class="so-edit-loading" id="sales-order-edit-loading">
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span>Loading Sales Order…</span>
                </div>
                <div id="sales-order-edit-content"></div>
            </div>
        </div>
    </div>
</div>
