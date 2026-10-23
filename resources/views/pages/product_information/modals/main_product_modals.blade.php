<div class="modal fade product-information-modal-theme" id="MainProductModal" tabindex="-1" aria-labelledby="MainProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="MainProductModalLabel">Add Main Product</h5>
                    <small class="text-white-50">Create the parent product used to group variants.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="MainProductForm" novalidate>
                <input type="hidden" name="product_main_id" id="product_main_id">
                <div class="modal-body product-settings-modal-body">
                    <div class="product-settings-form-card">
                        <div class="mb-3">
                            <label for="main_product_name" class="form-label small fw-semibold">Main Product Name</label>
                            <input type="text" class="form-control" id="main_product_name" name="product_name" maxlength="255" required autocomplete="off">
                        </div>
                        <div>
                            <label for="main_category_idx" class="form-label small fw-semibold">Category</label>
                            <select class="form-select" id="main_category_idx" name="category_idx" required>
                                <option value="">Select a category</option>
                                @foreach ($product_category as $categoryOption)
                                    <option value="{{ $categoryOption->category_id }}">{{ $categoryOption->category_name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Variants assigned to this main product must use the same category.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer product-settings-modal-footer">
                    <button type="button" class="btn btn-light border btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3" id="save-main-product">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Main Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="ManageMainProductModal" tabindex="-1" aria-labelledby="ManageMainProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="ManageMainProductModalLabel">Product Variants</h5>
                    <small class="text-white-50" id="manageMainProductCategory">Manage variants linked to this main product.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body product-settings-modal-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <p class="small text-muted mb-0">Existing product records are only linked when you choose to assign them.</p>
                    <button type="button" class="btn btn-primary btn-sm rounded-3" id="addVariantToMainProduct">
                        <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add Variant
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100" id="MainProductVariantsTable">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Product Code</th>
                                <th>Product Variant</th>
                                <th>Category</th>
                                <th class="text-end">Default Price</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Capacity</th>
                                <th class="text-center"><i class="bi bi-qr-code me-1" aria-hidden="true"></i>QR Code</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer product-settings-modal-footer">
                <button type="button" class="btn btn-light border btn-sm rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="DeleteMainProductModal" tabindex="-1" aria-labelledby="DeleteMainProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="DeleteMainProductModalLabel">Delete Main Product?</h5>
                    <small class="text-white-50">This is allowed only when it has no variants.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body product-settings-modal-body">
                <input type="hidden" id="delete_product_main_id">
                <p class="mb-0">Delete <strong id="deleteMainProductName"></strong>?</p>
            </div>
            <div class="modal-footer product-settings-modal-footer">
                <button type="button" class="btn btn-light border btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm rounded-3" id="confirmDeleteMainProduct">
                    <i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="UnlinkProductVariantModal" tabindex="-1" aria-labelledby="UnlinkProductVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="UnlinkProductVariantModalLabel">Remove Variant Link?</h5>
                    <small class="text-white-50">The variant record and its pricing will remain.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body product-settings-modal-body">
                <input type="hidden" id="unlink_product_id">
                <p class="mb-0">Remove <strong id="unlinkProductName"></strong> from this main product?</p>
            </div>
            <div class="modal-footer product-settings-modal-footer">
                <button type="button" class="btn btn-light border btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm rounded-3" id="confirmUnlinkProductVariant">
                    <i class="bi bi-link-45deg me-1" aria-hidden="true"></i>Remove Link
                </button>
            </div>
        </div>
    </div>
</div>
