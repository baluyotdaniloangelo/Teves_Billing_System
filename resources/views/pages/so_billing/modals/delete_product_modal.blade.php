<div class="modal fade so-modal-theme" id="DeleteSOProductModal" tabindex="-1" aria-labelledby="delete-so-product-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content so-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="so-modal-icon me-3"><i class="bi bi-trash3 fs-4" aria-hidden="true"></i></div>
                    <div>
                        <p class="so-modal-eyebrow">Sales order product</p>
                        <h2 class="modal-title" id="delete-so-product-title">Delete product?</h2>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">This will remove <strong id="delete_so_product_name">this product</strong> from sales order <strong id="delete_so_product_order">—</strong>.</p>
                <p class="small text-muted mb-0">Only products that are not linked to a Cashiers Report can be deleted.</p>
                <div id="delete-so-product-feedback" class="so-feedback mt-3" role="alert" hidden></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm rounded-3 px-3" id="confirm-delete-so-product">
                    <span class="spinner-border spinner-border-sm me-1" id="delete-so-product-spinner" role="status" aria-hidden="true" hidden></span>
                    <i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete product
                </button>
            </div>
        </div>
    </div>
</div>
