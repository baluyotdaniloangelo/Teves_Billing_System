<div class="modal fade product-information-modal-theme" id="ProductPricePerSellerModal" tabindex="-1" aria-labelledby="ProductPricePerSellerModal_title" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;">
                        <i class="bi bi-truck fs-5" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="ProductPricePerSellerModal_title">Add Seller Price</h5>
                        <small class="text-white-50">Set this product's supplier cost for a branch.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>

            <form class="needs-validation" id="ProductSellerPrice" novalidate>
                <div class="modal-body product-settings-modal-body">
                    <section class="product-settings-form-card">
                        <div class="mb-3">
                            <label for="branch_idx_seller" class="form-label small fw-semibold mb-1"><i class="bi bi-building me-1 text-success" aria-hidden="true"></i>Branch</label>
                            <select class="form-select form-select-sm rounded-3" required name="branch_idx_seller" id="branch_idx_seller">
                                @foreach ($teves_branch as $teves_branch_cols)
                                    <option value="{{ $teves_branch_cols->branch_id }}">{{ $teves_branch_cols->branch_code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="supplier_idx" class="form-label small fw-semibold mb-1"><i class="bi bi-person-lines-fill me-1 text-success" aria-hidden="true"></i>Supplier</label>
                            <input class="form-control form-control-sm rounded-3" list="supplier_name" name="supplier_name" id="supplier_idx" required autocomplete="off" placeholder="Choose a supplier">
                            <datalist id="supplier_name">
                                @foreach ($supplier_data as $supplier_data_cols)
                                    <option label="{{ $supplier_data_cols->supplier_name }}" data-id="{{ $supplier_data_cols->supplier_id }}" value="{{ $supplier_data_cols->supplier_name }}">
                                @endforeach
                            </datalist>
                            <div class="form-text small">Select a supplier from the suggestions.</div>
                        </div>

                        <div>
                            <label for="seller_price" class="form-label small fw-semibold mb-1"><i class="bi bi-currency-exchange me-1 text-success" aria-hidden="true"></i>Seller Price</label>
                            <input type="number" class="form-control form-control-sm rounded-3" name="seller_price" id="seller_price" min="0" step="0.01" inputmode="decimal" placeholder="0.00" required>
                        </div>
                    </section>
                </div>
                <div class="modal-footer product-settings-modal-footer">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="save-seller-price" value="0"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Seller Price</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="SuppliersProductPriceInfoDeleteModal" tabindex="-1" aria-labelledby="SuppliersProductPriceInfoDeleteModal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content product-settings-modal-content">
        <div class="modal-header px-4 py-3"><div class="d-flex align-items-center text-white"><div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;"><i class="bi bi-trash3-fill" aria-hidden="true"></i></div><div><h5 class="modal-title fw-bold mb-0" id="SuppliersProductPriceInfoDeleteModal_title">Delete Seller Price?</h5><small class="text-white-50">Please confirm this deletion.</small></div></div><button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:34px;height:34px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <div class="modal-body product-settings-modal-body"><div class="alert alert-warning border-0 rounded-3 d-flex align-items-start mb-3"><i class="bi bi-exclamation-triangle-fill me-2 mt-1" aria-hidden="true"></i><div class="small">This seller price will be removed from the product.</div></div><div class="card border-0 bg-light rounded-3"><div class="card-body py-2 px-3"><dl class="row small mb-0"><dt class="col-5">Branch</dt><dd class="col-7" id="branch_suppliers_price_delete"></dd><dt class="col-5">Supplier</dt><dd class="col-7" id="suppliers_name_suppliers_price_delete"></dd><dt class="col-5">Price</dt><dd class="col-7 mb-0" id="sellers_price_suppliers_price_delete"></dd></dl></div></div></div>
        <div class="modal-footer product-settings-modal-footer"><button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger btn-sm rounded-3 px-3" id="deleteSuppliersProductPriceInfoConfirmed" value="0"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete</button></div>
    </div></div>
</div>