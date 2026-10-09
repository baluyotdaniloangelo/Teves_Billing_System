<!-- ========================================== -->
<!-- LPG NON-CASH PAYMENT ADD / EDIT MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="CashierReportLpgNonCashPaymentModal"
     tabindex="-1"
     aria-labelledby="CashierReportLpgNonCashPaymentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #198754, #157347);">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3"
                         style="width:48px;height:48px;">
                        <i class="bi bi-credit-card-2-front fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="CashierReportLpgNonCashPaymentModalLabel">Add Non-Cash Payment</h5>
                        <small class="text-white-50">Record check, bank, or other non-cash payment</small>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm rounded-circle"
                        data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body p-4">
                <form id="CashierReportLpgNonCashPaymentForm" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" id="cashiers_report_lpg_non_cash_payment_id"
                           name="cashiers_report_lpg_non_cash_payment_id" value="0">
                    <input type="hidden" id="cashiers_report_lpg_non_cash_payment_report_id"
                           name="cashiers_report_id"
                           value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="lpg_non_cash_mode" class="form-label fw-semibold">Mode of Payment</label>
                            <select class="form-select form-select-lg rounded-3"
                                    id="lpg_non_cash_mode" name="mode_of_payment" required>
                                <option value="" selected disabled>Select payment mode</option>
                                <option value="limitless">Limitless Payment</option>
                                <option value="credit_debit">Credit / Debit</option>
                                <option value="gcash">GCASH</option>
                                <option value="check">Check</option>
                            </select>
                            <div class="invalid-feedback" id="mode_of_paymentError">Please select a payment mode.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_non_cash_payer_name" class="form-label fw-semibold">Payer Name</label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                   id="lpg_non_cash_payer_name" name="payer_name"
                                   maxlength="255" placeholder="Enter payer name">
                            <div class="invalid-feedback" id="payer_nameError">Please check the payer name.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_non_cash_payer_number" class="form-label fw-semibold">Account Number</label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                   id="lpg_non_cash_payer_number" name="payer_number"
                                   maxlength="100" placeholder="Enter account number">
                            <div class="invalid-feedback" id="payer_numberError">Please check the account number.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_non_cash_reference_no" class="form-label fw-semibold">Reference Number</label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                   id="lpg_non_cash_reference_no" name="reference_no"
                                   maxlength="100" placeholder="Check number or transaction reference">
                            <div class="invalid-feedback" id="reference_noError">Please check the reference number.</div>
                        </div>

                        <div class="col-12" id="lpg_non_cash_check_expiry_wrap" hidden>
                            <label for="lpg_non_cash_check_expiry_date" class="form-label fw-semibold">Check Expiry Date</label>
                            <input type="date" class="form-control form-control-lg rounded-3"
                                   id="lpg_non_cash_check_expiry_date" name="check_expiry_date">
                            <div class="invalid-feedback" id="check_expiry_dateError">Please enter the check expiry date.</div>
                        </div>

                        <div class="col-12">
                            <label for="lpg_non_cash_amount" class="form-label fw-semibold">Amount</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control rounded-end-3"
                                       id="lpg_non_cash_amount" name="amount"
                                       min="0.01" step="0.01" required placeholder="0.00">
                            </div>
                            <div class="invalid-feedback" id="amountError">Please enter an amount greater than zero.</div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="reset" class="btn btn-light border rounded-3 px-4"
                        id="clear-lpg-non-cash-payment" form="CashierReportLpgNonCashPaymentForm">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </button>
                <button type="submit" form="CashierReportLpgNonCashPaymentForm"
                        class="btn btn-success rounded-3 px-4 shadow-sm" id="save-lpg-non-cash-payment">
                    <i class="bi bi-save-fill me-2"></i>Save Payment
                </button>
            </div>
        </div>
    </div>
</div>
