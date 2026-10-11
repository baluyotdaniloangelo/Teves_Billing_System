<div class="modal fade product-information-modal-theme" id="ProductDeleteModal" tabindex="-1" role="dialog" aria-labelledby="ProductDeleteModal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;"><i class="bi bi-trash3-fill fs-5" aria-hidden="true"></i></div>
                    <div><h5 class="modal-title fw-bold mb-0" id="ProductDeleteModal_title">Delete Product?</h5><small class="text-white-50">Please confirm this deletion.</small></div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body product-settings-modal-body">
                <div class="alert alert-warning border-0 rounded-3 d-flex align-items-start mb-3"><i class="bi bi-exclamation-triangle-fill me-2 mt-1" aria-hidden="true"></i><div class="small">Are you sure you want to delete this product? <strong>This action cannot be undone.</strong></div></div>
                <div class="card border-0 bg-light rounded-3"><div class="card-body py-2 px-3"><div class="small text-muted mb-1">Product</div><div class="fw-semibold mb-2" id="confirm_delete_product_name"></div><div class="d-flex justify-content-between border-top pt-2 small"><span class="text-muted">Default Price</span><span class="fw-semibold" id="confirm_delete_product_price"></span></div></div></div>
            </div>
            <div class="modal-footer product-settings-modal-footer">
                <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                <button type="button" class="btn btn-danger btn-sm rounded-3 px-3" id="deleteProductConfirmed" value=""><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete Product</button>
            </div>
        </div>
    </div>
</div>