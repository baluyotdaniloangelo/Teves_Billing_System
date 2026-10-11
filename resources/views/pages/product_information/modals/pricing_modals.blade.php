	<div class="modal fade product-information-modal-theme" id="UpdateProductPricePerBranchModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="UpdateProductPricePerBranchModal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;"><i class="bi bi-building-gear fs-5" aria-hidden="true"></i></div>
                    <div><h5 class="modal-title fw-bold mb-0" id="UpdateProductPricePerBranchModal_title">Update Price per Branch</h5><small class="text-white-50">Adjust the buying price and margin for this branch.</small></div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <form class="needs-validation" id="UpdateProductPricePerBranch" novalidate>
                <div class="modal-body product-settings-modal-body">
                    <section class="product-settings-form-card">
                        <div class="card border-0 bg-light rounded-3 mb-3"><div class="card-body py-2 px-3"><div class="row g-2 small"><div class="col-12"><div class="text-muted">Branch Name</div><div class="fw-semibold" id="branch_name_price"></div></div><div class="col-7"><div class="text-muted">Branch Code</div><div class="fw-semibold" id="branch_code_price"></div></div><div class="col-5 text-end"><div class="text-muted">Current Selling Price</div><div class="fw-semibold" id="branch_price"></div></div></div></div></div>
                        <div class="mb-3"><label for="update_buying_price" class="form-label small fw-semibold mb-1"><i class="bi bi-currency-exchange me-1 text-success" aria-hidden="true"></i>Buying Price</label><input type="number" class="form-control form-control-sm rounded-3" name="update_buying_price" id="update_buying_price" min="0" step="0.01" inputmode="decimal" required><div class="invalid-feedback" id="update_buying_priceError"></div></div>
                        <div class="mb-3"><label for="update_profit_margin" class="form-label small fw-semibold mb-1"><i class="bi bi-graph-up-arrow me-1 text-success" aria-hidden="true"></i>Profit Margin</label><input type="number" class="form-control form-control-sm rounded-3" name="update_profit_margin" id="update_profit_margin" min="0" step="0.01" inputmode="decimal" required><div class="invalid-feedback" id="update_profit_marginError"></div></div>
                        <div><label for="update_profit_margin_type" class="form-label small fw-semibold mb-1"><i class="bi bi-percent me-1 text-success" aria-hidden="true"></i>Profit Margin Type</label><select class="form-select form-select-sm rounded-3" name="update_profit_margin_type" id="update_profit_margin_type" required><option value="Percentage">Percentage</option><option value="Peso">Peso</option></select><div class="invalid-feedback" id="update_profit_margin_typeError"></div></div>
                    </section>
                </div>
                <div class="modal-footer product-settings-modal-footer">
                    <div id="loading_data_update_product" class="d-none" aria-live="polite"><span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span><span class="visually-hidden">Saving...</span></div>
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="update-branch-price" value="0"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Update Price</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade product-information-modal-theme" id="ProductHistoryChangesModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="productHistoryChangesModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;">
                        <i class="bi bi-clock-history fs-5" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="productHistoryChangesModalTitle">Price Change History</h5>
                        <small class="text-white-50">Review recorded price updates for this product and branch.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body product-settings-modal-body">
                <section class="card border-0 bg-light rounded-3 mb-3" aria-label="Product and branch details">
                    <div class="card-body py-3 px-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-lg-5">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 text-success bg-success bg-opacity-10" style="width:40px;height:40px;"><i class="bi bi-box-seam" aria-hidden="true"></i></div>
                                    <div class="min-w-0"><div class="small text-muted">Product</div><div class="fw-semibold text-break" id="product_name_history_view"></div></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="small text-muted">Branch</div>
                                <div class="fw-semibold text-break" id="branch_name_history_view"></div>
                                <div class="small text-muted mt-1">Code: <span class="fw-semibold" id="branch_code_history_view"></span></div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3 text-md-end">
                                <div class="small text-muted">Current Selling Price</div>
                                <div class="fw-bold fs-5 text-success">₱<span id="branch_price_history_view"></span></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="product-settings-form-card p-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1"><i class="bi bi-list-columns-reverse me-2 text-success" aria-hidden="true"></i>Change records</h6>
                            <p class="small text-muted mb-0">Buying price, margin, and selling price for each recorded update.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table dataTable display nowrap cell-border table-hover align-middle mb-0" id="product_price_branch_history" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="all">Item #</th>
                                    <th class="all">Date</th>
                                    <th class="all">Time</th>
                                    <th class="all">Branch</th>
                                    <th class="none">Buying Price</th>
                                    <th class="none">Profit Margin</th>
                                    <th class="none">Margin Type</th>
                                    <th class="none">Margin Amount</th>
                                    <th class="all">Selling Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="modal-footer product-settings-modal-footer">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Close</button>
            </div>
        </div>
    </div>
</div>