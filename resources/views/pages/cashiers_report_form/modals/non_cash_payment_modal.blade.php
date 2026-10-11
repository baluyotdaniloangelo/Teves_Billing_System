<!-- ========================================== -->
<!-- FUEL NON-CASH PAYMENT ADD / EDIT MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="nonCashPaymentModal"
     tabindex="-1"
     aria-labelledby="nonCashPaymentModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3"
                         style="width:48px;height:48px;">
                        <i class="bi bi-credit-card-2-front fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="nonCashPaymentModalTitle">Add Non-Cash Payment</h5>
                        <small class="text-white-50">Record check, bank, or other non-cash payment</small>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm rounded-circle"
                        data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body p-4">
                <form id="non_cash_payment_form" class="needs-validation" novalidate>
                    <div class="alert alert-danger d-none mb-3" id="non_cash_payment_error" role="alert"></div>
                    <input type="hidden" id="non_cash_payment_id" name="non_cash_payment_id" value="0">
                    <input type="hidden" name="cash_report_id" value="{{ $CashiersReportId ?? 0 }}">

                    <!-- PAYMENT INFORMATION -->
                    <section class="rounded-4 border bg-white p-3 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary me-2" style="width:38px;height:38px;">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0">Payment Information</h6>
                                <small class="text-muted">Select the payment mode and identify the payer.</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="non_cash_payment_type">Mode of Payment</label>
                                <select class="form-select form-select-lg rounded-3" id="non_cash_payment_type"
                                        name="payment_type" required>
                                    <option value="" selected disabled>Select payment mode</option>
                                    <option value="limitless">Limitless Payment</option>
                                    <option value="credit_debit">Credit / Debit</option>
                                    <option value="gcash">GCASH</option>
                                    <option value="check">Check</option>
                                </select>
                                <div class="invalid-feedback">Please select a payment mode.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="non_cash_payment_name">Payer Name</label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                       id="non_cash_payment_name" name="payer_name" maxlength="255"
                                       placeholder="Enter payer name" required>
                                <div class="invalid-feedback">Please enter the payer name.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="non_cash_payment_number">Account Number</label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                       id="non_cash_payment_number" name="payer_number" maxlength="100"
                                       placeholder="Enter account number" required>
                                <div class="invalid-feedback">Please enter the account number.</div>
                            </div>
                        </div>
                    </section>

                    <!-- TRANSACTION DETAILS -->
                    <section class="rounded-4 border bg-white p-3 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary me-2" style="width:38px;height:38px;">
                                <i class="bi bi-receipt"></i>
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0">Transaction Details</h6>
                                <small class="text-muted">Enter the reference number and payment amount.</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="non_cash_payment_reference">Reference Number</label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                       id="non_cash_payment_reference" name="reference_number" maxlength="100"
                                       placeholder="Check number or transaction reference" required>
                                <div class="invalid-feedback">Please enter the reference number.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="non_cash_payment_amount">Amount</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control rounded-end-3"
                                           id="non_cash_payment_amount" name="payment_amount"
                                           min="0.01" step="0.01" required placeholder="0.00">
                                </div>
                                <div class="invalid-feedback">Please enter an amount greater than zero.</div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="submit" form="non_cash_payment_form"
                        class="btn btn-success rounded-3 px-4 shadow-sm" id="save_non_cash_payment">
                    <i class="bi bi-save-fill me-2"></i><span>Save Payment</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNonCashPaymentModal" tabindex="-1" aria-labelledby="deleteNonCashPaymentTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content border-0 rounded-4 shadow"><div class="modal-header border-0"><h5 class="modal-title fw-bold" id="deleteNonCashPaymentTitle">Delete Payment?</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">Delete this non-cash payment record?</div><div class="modal-footer border-0"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" id="confirm_delete_non_cash_payment">Delete</button></div></div></div></div>
