<!-- ========================================== -->
<!-- LPG AR SALES ORDER / RETURN ADD-EDIT MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="CashierReportLpgArSoModal"
     tabindex="-1"
     aria-labelledby="CashierReportLpgArSoModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #198754, #157347);">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3"
                         style="width:48px;height:48px;">
                        <i class="bi bi-receipt-cutoff fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="CashierReportLpgArSoModalLabel">
                            Add Sales Order / Return
                        </h5>
                        <small class="text-white-50">Enter LPG account receivable item details</small>
                    </div>
                </div>

                <button type="button"
                        class="btn btn-danger btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:36px;height:36px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body p-4">
                <form class="needs-validation"
                      id="CashierReportLpgArSoForm"
                      novalidate>
                    @csrf

                    <input type="hidden"
                           id="cashiers_report_lpg_ar_so_id"
                           name="cashiers_report_lpg_ar_so_id"
                           value="0">
                    <input type="hidden"
                           id="cashiers_report_lpg_ar_so_report_id"
                           name="cashiers_report_id"
                           value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="ar_so_dr_number" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-text me-2 text-success"></i>DR Number
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   id="ar_so_dr_number"
                                   name="dr_number"
                                   maxlength="100"
                                   autocomplete="off"
                                   placeholder="Enter DR number (optional)">
                            <div class="invalid-feedback" id="dr_numberError">Please check the DR number.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="ar_so_type" class="form-label fw-semibold">
                                <i class="bi bi-arrow-left-right me-2 text-success"></i>Item Type
                            </label>
                            <select class="form-select form-select-lg rounded-3"
                                    id="ar_so_type"
                                    name="item_description"
                                    required>
                                <option value="" selected disabled>Select item type</option>
                                <option value="Sales Order">Sales Order</option>
                                <option value="Cash Sales">Cash Sales</option>
                                <option value="Return">Return</option>
                            </select>
                            <div class="invalid-feedback" id="item_descriptionError">Please select Sales Order, Cash Sales, or Return.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="ar_so_account_id" class="form-label fw-semibold">
                                <i class="bi bi-person-lines-fill me-2 text-success"></i>Account Name
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   list="lpgArSoClientNameList"
                                   id="ar_so_account_id"
                                   required
                                   autocomplete="off"
                                   placeholder="Select account">
                            <datalist id="lpgArSoClientNameList">
                                @foreach ($client_data as $client_data_cols)
                                    <option label="{{ $client_data_cols->client_name }}"
                                            data-id="{{ $client_data_cols->client_id }}"
                                            value="{{ $client_data_cols->client_name }}"></option>
                                @endforeach
                            </datalist>
                            <div class="invalid-feedback" id="client_idxError">Please select an account from the list.</div>
                        </div>

                        <div class="col-12">
                            <label for="ar_so_product" class="form-label fw-semibold">
                                <i class="bi bi-box-seam me-2 text-success"></i>Product / Item
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   list="lpgArSoProductList"
                                   id="ar_so_product"
                                   required
                                   autocomplete="off"
                                   placeholder="Select LPG product">
                            <datalist id="lpgArSoProductList">
                                @foreach ($product_data as $product_data_cols)
                                    @php
                                        $lpgArSoProductLabel = trim(($product_data_cols->product_name ?? '') . ' ' . ($product_data_cols->product_unit_measurement ?? ''));
                                    @endphp
                                    <option label="{{ $lpgArSoProductLabel }} | ₱ {{ number_format($product_data_cols->product_price ?? 0, 2) }}"
                                            data-id="{{ $product_data_cols->product_id }}"
                                            data-price="{{ $product_data_cols->product_price ?? 0 }}"
                                            value="{{ $lpgArSoProductLabel }}"></option>
                                @endforeach
                            </datalist>
                            <div class="invalid-feedback" id="product_idxError">Please select a product from the list.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="ar_so_quantity" class="form-label fw-semibold">Quantity</label>
                            <input type="number"
                                   class="form-control form-control-lg rounded-3"
                                   id="ar_so_quantity"
                                   name="order_quantity"
                                   min="0.01"
                                   step="0.01"
                                   required
                                   placeholder="0.00">
                            <div class="invalid-feedback" id="order_quantityError">Enter a quantity greater than zero.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="ar_so_unit_price" class="form-label fw-semibold">SRP / Original Unit Price</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                       class="form-control rounded-end-3"
                                       id="ar_so_unit_price"
                                       name="unit_price"
                                       min="0"
                                       step="0.01"
                                       readonly
                                       placeholder="0.00">
                            </div>
                            <div class="form-text">Filled from the selected product's price.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="ar_so_discount_per_unit" class="form-label fw-semibold">Less / Discount per Unit</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                       class="form-control rounded-end-3"
                                       id="ar_so_discount_per_unit"
                                       name="discount_per_unit"
                                       min="0"
                                       step="0.01"
                                       value="0"
                                       required
                                       placeholder="0.00">
                            </div>
                            <div class="invalid-feedback" id="discount_per_unitError">Enter a discount no greater than the SRP.</div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 bg-success bg-opacity-10 rounded-4">
                                <div class="card-body px-4 py-3">
                                    <div class="row g-2">
                                        <div class="col-6 text-muted">Discounted Unit Price</div>
                                        <div class="col-6 text-end fw-semibold">₱ <span id="LpgArSoDiscountedUnitPrice">0.00</span></div>
                                        <div class="col-6 text-muted">Total Discount</div>
                                        <div class="col-6 text-end fw-semibold text-danger">₱ <span id="LpgArSoTotalDiscount">0.00</span></div>
                                        <div class="col-6 fw-semibold">Net Amount</div>
                                        <div class="col-6 text-end text-success fw-bold fs-4">₱ <span id="LpgArSoTotalAmount">0.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="reset"
                        class="btn btn-light border rounded-3 px-4"
                        id="clear-lpg-ar-so"
                        form="CashierReportLpgArSoForm">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </button>
                <button type="submit"
                        form="CashierReportLpgArSoForm"
                        class="btn btn-success rounded-3 px-4 shadow-sm"
                        id="save-lpg-ar-so">
                    <i class="bi bi-save-fill me-2"></i>Save Item
                </button>
            </div>

        </div>
    </div>
</div>
