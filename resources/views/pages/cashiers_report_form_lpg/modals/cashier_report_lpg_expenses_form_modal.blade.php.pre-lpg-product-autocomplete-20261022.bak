<!-- ========================================== -->
<!-- LPG EXPENSE ADD / EDIT MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="CashierReportLpgExpensesModal"
     tabindex="-1"
     aria-labelledby="CashierReportLpgExpensesModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #198754, #157347);">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3"
                         style="width:48px;height:48px;">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="CashierReportLpgExpensesModalLabel">
                            Add Expense
                        </h5>
                        <small class="text-white-50">Cash out, maintenance, or item expense</small>
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
                      id="CashierReportLpgExpensesForm"
                      novalidate>
                    @csrf

                    <input type="hidden"
                           id="cashiers_report_lpg_expenses_id"
                           name="cashiers_report_lpg_expenses_id"
                           value="0">
                    <input type="hidden"
                           id="cashiers_report_lpg_expenses_report_id"
                           name="cashiers_report_id"
                           value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="lpg_expense_type" class="form-label fw-semibold">Type</label>
                            <select class="form-select form-select-lg rounded-3"
                                    id="lpg_expense_type"
                                    name="expense_type"
                                    required>
                                <option value="" selected disabled>Select expense type</option>
                                <option value="OPEX">OPEX</option>
                                <option value="NON-OPEX">NON-OPEX</option>
                            </select>
                            <div class="invalid-feedback" id="expense_typeError">Please select OPEX or NON-OPEX.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="lpg_expense_client" class="form-label fw-semibold">Account Name <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   id="lpg_expense_client"
                                   list="lpgExpenseClientList"
                                   autocomplete="off"
                                   placeholder="Select client, if applicable">
                            <datalist id="lpgExpenseClientList">
                                @foreach ($client_data as $client_data_cols)
                                    <option label="{{ $client_data_cols->client_name }}"
                                            data-id="{{ $client_data_cols->client_id }}"
                                            value="{{ $client_data_cols->client_name }}"></option>
                                @endforeach
                            </datalist>
                            <div class="invalid-feedback" id="client_idxError">Select a client from the list or clear this field.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_expense_purpose" class="form-label fw-semibold">Purpose</label>
                            <textarea class="form-control rounded-3"
                                      id="lpg_expense_purpose"
                                      name="purpose"
                                      rows="2"
                                      placeholder="e.g. Repair, maintenance, raffle prize"></textarea>
                            <div class="invalid-feedback" id="purposeError">Please check the purpose.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_expense_item" class="form-label fw-semibold">Item / Description <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   id="lpg_expense_item"
                                   name="item_description"
                                   list="lpgExpenseProductList"
                                   autocomplete="off"
                                   placeholder="Select a product or enter a cash expense description">
                            <datalist id="lpgExpenseProductList">
                                @foreach ($product_data as $product_data_cols)
                                    @php
                                        $lpgExpenseProductLabel = trim(($product_data_cols->product_name ?? '') . ' ' . ($product_data_cols->product_unit_measurement ?? ''));
                                    @endphp
                                    <option label="{{ $lpgExpenseProductLabel }} | ₱ {{ number_format($product_data_cols->product_price ?? 0, 2) }}"
                                            data-id="{{ $product_data_cols->product_id }}"
                                            data-price="{{ $product_data_cols->product_price ?? 0 }}"
                                            value="{{ $lpgExpenseProductLabel }}"></option>
                                @endforeach
                            </datalist>
                            <div class="form-text">Choose a listed product to link it to inventory, or type a description for a non-product expense.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="lpg_expense_quantity" class="form-label fw-semibold">Quantity</label>
                            <input type="number"
                                   class="form-control form-control-lg rounded-3"
                                   id="lpg_expense_quantity"
                                   name="order_quantity"
                                   min="0.01"
                                   step="0.01"
                                   placeholder="Optional">
                            <div class="invalid-feedback" id="order_quantityError">Enter a quantity greater than zero.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="lpg_expense_unit_price" class="form-label fw-semibold">SRP / Unit Price</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                       class="form-control rounded-end-3"
                                       id="lpg_expense_unit_price"
                                       name="unit_price"
                                       min="0"
                                       step="0.01"
                                       placeholder="Enter unit price">
                            </div>
                            <div class="invalid-feedback" id="unit_priceError">Enter a valid unit price.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="lpg_expense_amount" class="form-label fw-semibold">Amount</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                       class="form-control rounded-end-3"
                                       id="lpg_expense_amount"
                                       name="amount"
                                       min="0"
                                       step="0.01"
                                       required
                                       placeholder="0.00">
                            </div>
                            <div class="form-text">For products, calculated as quantity × unit price. For cash outs without a product, enter the amount.</div>
                            <div class="invalid-feedback" id="amountError">Please enter the expense amount.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_expense_remarks" class="form-label fw-semibold">Remarks</label>
                            <textarea class="form-control rounded-3"
                                      id="lpg_expense_remarks"
                                      name="remarks"
                                      rows="2"
                                      placeholder="Optional remarks"></textarea>
                            <div class="invalid-feedback" id="remarksError">Please check the remarks.</div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="reset"
                        class="btn btn-light border rounded-3 px-4"
                        id="clear-lpg-expense"
                        form="CashierReportLpgExpensesForm">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </button>
                <button type="submit"
                        form="CashierReportLpgExpensesForm"
                        class="btn btn-success rounded-3 px-4 shadow-sm"
                        id="save-lpg-expense">
                    <i class="bi bi-save-fill me-2"></i>Save Expense
                </button>
            </div>

        </div>
    </div>
</div>
