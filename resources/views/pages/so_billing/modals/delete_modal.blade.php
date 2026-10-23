<div class="modal fade so-modal-theme" id="DeleteSOModal" tabindex="-1" aria-labelledby="delete-so-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content so-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="so-modal-icon me-3"><i class="bi bi-receipt-cutoff fs-4" aria-hidden="true"></i></div>
                    <div>
                        <p class="so-modal-eyebrow">Review before deleting</p>
                        <h2 class="modal-title" id="delete-so-title">Sales order details</h2>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <div id="so-delete-feedback" class="so-feedback" role="alert" hidden></div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <dl class="so-detail-list">
                            <div><dt>Date</dt><dd id="delete_so_order_date">—</dd></div>
                            <div><dt>Time</dt><dd id="delete_so_order_time">—</dd></div>
                            <div><dt>S.O. number</dt><dd id="delete_so_so_number">—</dd></div>
                            <div><dt>Account name</dt><dd id="delete_so_client_name">—</dd></div>
                            <div><dt>Driver</dt><dd id="delete_so_drivers_name">—</dd></div>
                            <div><dt>Description</dt><dd id="delete_so_plate_no">—</dd></div>
                            <div><dt>Total</dt><dd id="delete_so_total">—</dd></div>
                        </dl>
                    </div>
                    <div class="col-lg-8">
                        <h3 class="so-products-title">Products on this order</h3>
                        <div class="table-responsive">
                            <table class="table table-striped so-products-table" id="so_product_list">
                                <caption class="visually-hidden">Products that will be removed with this sales order</caption>
                                <thead><tr><th scope="col">#</th><th scope="col">Product</th><th scope="col" class="text-end">Quantity</th><th scope="col" class="text-end">Price</th><th scope="col" class="text-end">Amount</th></tr></thead>
                                <tbody id="so_product_list_data_delete"><tr><td colspan="5" class="text-center text-muted">Select an order to load its products.</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="so-delete-warning me-auto"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i> This removes the sales order and its product rows.</p>
                <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                <button type="button" class="btn btn-danger btn-sm rounded-3 px-3" id="deleteSOConfirmed" value="">
                    <span class="spinner-border spinner-border-sm me-1" id="so-delete-spinner" role="status" aria-hidden="true" hidden></span>
                    <i class="bi bi-trash3" aria-hidden="true"></i> Delete sales order
                </button>
            </div>
        </div>
    </div>
</div>
