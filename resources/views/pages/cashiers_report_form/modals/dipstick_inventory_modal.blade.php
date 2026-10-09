<div class="modal fade" id="dipstickInventoryModal" tabindex="-1" aria-labelledby="dipstickInventoryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div id="dipstick_inventory_form" class="needs-validation" role="form">
                <input type="hidden" id="dipstick_inventory_id" name="dipstick_inventory_id" value="0">

                <div class="modal-header border-0 px-4 py-3 text-white" style="background:linear-gradient(135deg,#198754,#157347)">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px">
                            <i class="bi bi-speedometer2 fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="dipstickInventoryModalTitle">Add Dipstick Inventory</h5>
                            <small class="text-white-50">Enter the tank readings for this cashier report</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light btn-sm rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <section class="rounded-4 border bg-white p-3 mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success me-2" style="width:36px;height:36px"><i class="bi bi-fuel-pump-fill"></i></span>
                            <div><h6 class="fw-bold mb-0">Product Details</h6><small class="text-muted">Select the fuel product and its tank.</small></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="product_idx_dipstick_inventory" class="form-label fw-semibold">Product</label>
                                <input class="form-control rounded-3" list="product_list_inventory" name="product_name_dipstick_inventory" id="product_idx_dipstick_inventory" autocomplete="off" placeholder="Loading Fuel products..." required disabled>
                                <div class="invalid-feedback" id="product_idx_dipstick_inventoryError"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="product_tank_idx_dipstick_inventory" class="form-label fw-semibold">Tank</label>
                                <input class="form-control rounded-3" list="product_tank_list" name="product_tank_name_dipstick_inventory" id="product_tank_idx_dipstick_inventory" autocomplete="off" placeholder="Select tank" required>
                                <div class="invalid-feedback" id="product_tank_idx_dipstick_inventoryError"></div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-4 border bg-white p-3 mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary me-2" style="width:36px;height:36px"><i class="bi bi-speedometer2"></i></span>
                            <div><h6 class="fw-bold mb-0">Readings</h6><small class="text-muted">Enter the tank dipstick readings in liters.</small></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="beginning_dipstick_inventory" class="form-label fw-semibold"><i class="bi bi-box-arrow-in-down me-2 text-success"></i>Beginning</label>
                                <div class="input-group"><input type="number" class="form-control" name="beginning_dipstick_inventory" id="beginning_dipstick_inventory" step="0.01" placeholder="0.00" required><span class="input-group-text">L</span></div>
                                <div class="invalid-feedback" id="beginning_dipstick_inventoryError"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="ugt_pumping_dipstick_inventory" class="form-label fw-semibold"><i class="bi bi-arrow-down-up me-2 text-success"></i>UGT Pumping</label>
                                <div class="input-group"><input type="number" class="form-control" name="ugt_pumping_dipstick_inventory" id="ugt_pumping_dipstick_inventory" step="0.01" placeholder="0.00" required><span class="input-group-text">L</span></div>
                                <div class="invalid-feedback" id="ugt_pumping_dipstick_inventoryError"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="ending_dipstick_inventory" class="form-label fw-semibold"><i class="bi bi-box-arrow-up me-2 text-success"></i>Ending</label>
                                <div class="input-group"><input type="number" class="form-control" name="ending_dipstick_inventory" id="ending_dipstick_inventory" step="0.01" placeholder="0.00" required><span class="input-group-text">L</span></div>
                                <div class="invalid-feedback" id="ending_dipstick_inventoryError"></div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-4 border bg-white p-3">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning me-2" style="width:36px;height:36px"><i class="bi bi-truck"></i></span>
                            <div><h6 class="fw-bold mb-0">Sales and Delivery</h6><small class="text-muted">Record fuel sold and fuel delivered during the report period.</small></div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="sales_in_liters_dipstick_inventory" class="form-label fw-semibold"><i class="bi bi-cart-dash-fill me-2 text-success"></i>Sales in Liters</label>
                                <div class="input-group"><input type="number" class="form-control" name="sales_in_liters_dipstick_inventory" id="sales_in_liters_dipstick_inventory" step="0.01" placeholder="0.00" required><span class="input-group-text">L</span></div>
                                <div class="invalid-feedback" id="sales_in_liters_dipstick_inventoryError"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_dipstick_inventory" class="form-label fw-semibold"><i class="bi bi-truck me-2 text-success"></i>Delivery</label>
                                <div class="input-group"><input type="number" class="form-control" name="delivery_dipstick_inventory" id="delivery_dipstick_inventory" step="0.01" placeholder="0.00" required><span class="input-group-text">L</span></div>
                                <div class="invalid-feedback" id="delivery_dipstick_inventoryError"></div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6"><div class="d-flex align-items-center rounded-3 bg-light border-start border-3 border-primary px-3 py-2"><i class="bi bi-calculator-fill fs-4 text-primary me-3"></i><div><div class="small text-muted fw-semibold">BOOK STOCK</div><div class="fs-5 fw-bold"><span id="TotalBookStock_dipstick_inventory">0.00</span> <small class="fs-6">L</small></div></div></div></div>
                            <div class="col-md-6"><div class="d-flex align-items-center rounded-3 bg-light border-start border-3 border-warning px-3 py-2"><i class="bi bi-activity fs-4 text-warning me-3"></i><div><div class="small text-muted fw-semibold">VARIANCE</div><div class="fs-5 fw-bold"><span id="TotalVariance_dipstick_inventory">0.00</span> <small class="fs-6">L</small></div></div></div></div>
                        </div>
                    </section>

                    <datalist id="product_list_inventory">
                    </datalist>
                    <datalist id="product_tank_list"></datalist>
                </div>

                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light border rounded-3 px-3" id="reset_dipstick_inventory_form">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success rounded-3 px-4 shadow-sm" id="save-dipstick_inventory">
                        <i class="bi bi-save-fill me-2"></i><span id="dipstick_inventory_submit_label">Save Dipstick Reading</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dipstick_inventoryDeleteModal" tabindex="-1" aria-labelledby="dipstickInventoryDeleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3 text-white" style="background:linear-gradient(135deg,#dc3545,#b02a37)">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px"><i class="bi bi-trash3-fill fs-5"></i></div>
                    <div><h5 class="modal-title fw-bold mb-0" id="dipstickInventoryDeleteTitle">Delete Dipstick Inventory?</h5><small class="text-white-50">Please confirm this deletion.</small></div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body p-4">
                <dl class="row mb-0">
                    <dt class="col-5">Product</dt><dd class="col-7" id="delete_product_idx_dipstick_inventory"></dd>
                    <dt class="col-5">Tank</dt><dd class="col-7" id="delete_product_tank_idx_dipstick_inventory"></dd>
                    <dt class="col-5">Ending Inventory</dt><dd class="col-7"><span id="delete_ending_dipstick_inventory"></span> L</dd>
                    <dt class="col-5">Book Stock</dt><dd class="col-7"><span id="dipstick_inventory_delete_TotalBookStock"></span> L</dd>
                    <dt class="col-5">Variance</dt><dd class="col-7"><span id="dipstick_inventory_delete_TotalVariance"></span> L</dd>
                </dl>
            </div>
            <div class="modal-footer border-0 bg-light px-4">
                <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-3" id="delete_dipstick_inventory_confirmed" value="0"><i class="bi bi-trash3 me-1"></i>Delete Item</button>
            </div>
        </div>
    </div>
</div>
