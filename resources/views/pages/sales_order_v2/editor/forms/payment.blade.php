<div class="modal fade so-editor-entry" id="so-editor-AddPaymentModal" tabindex="-1" aria-labelledby="so-editor-payment-form-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <form id="so-editor-AddPayment" data-so-editor-form="payment" enctype="multipart/form-data" action="{{ route('sales_order_receivable_payment') }}" method="post" novalidate>
                @csrf
                <input type="hidden" id="so-editor-receivable_idx_payment" name="receivable_idx_payment" value="{{ $receivables_details['receivable_id'] ?? '' }}">
                <input type="hidden" id="so-editor-receivable_payment_id" name="receivable_payment_id" value="0" data-record-id>
                <div class="modal-header">
                    <div class="so-entry-heading">
                        <span class="so-entry-icon"><i class="bi bi-credit-card" aria-hidden="true"></i></span>
                        <div><p class="so-modal-eyebrow">SALES ORDER / PAYMENT</p><h2 class="modal-title" id="so-editor-payment-form-title" data-record-title data-create-title="Record payment" data-edit-title="Edit payment">Record payment</h2><p class="so-modal-subtitle">Enter the payment reference and attach a receipt if available.</p></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close payment form"></button>
                </div>
                <div class="modal-body">
                    <div class="so-form-alert" data-form-alert role="alert" hidden></div>
                    <div class="so-form-grid">
                        <div class="so-field">
                            <label for="so-editor-receivable_mode_of_payment">Payment method <em>*</em></label>
                            <select class="form-select" name="receivable_mode_of_payment" id="so-editor-receivable_mode_of_payment" data-errors="receivable_mode_of_payment" aria-describedby="so-editor-receivable_mode_of_paymentError" required>
                                @foreach (['Cash','Bank Transfer','Cash Deposit','Credit Card','Debit Card','E-Wallet/Mobile Payments','Dated Check','Post-Dated Check','Post-Dated Check - Cleared','Overpayment','Payroll Deduction'] as $method)
                                    <option value="{{ $method }}">{{ $method }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="so-editor-receivable_mode_of_paymentError"></span>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-receivable_payment_amount">Amount received <em>*</em></label>
                            <div class="so-input-money"><span>₱</span><input type="number" class="form-control" id="so-editor-receivable_payment_amount" name="receivable_payment_amount" data-errors="receivable_payment_amount" aria-describedby="so-editor-receivable_payment_amountError" min="0.01" step="0.01" placeholder="0.00" required></div>
                            <span class="invalid-feedback" id="so-editor-receivable_payment_amountError"></span>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-receivable_date_of_payment">Payment date <em>*</em></label>
                            <input type="date" class="form-control" id="so-editor-receivable_date_of_payment" name="receivable_date_of_payment" data-errors="receivable_date_of_payment" aria-describedby="so-editor-receivable_date_of_paymentError" value="{{ date('Y-m-d') }}" required>
                            <span class="invalid-feedback" id="so-editor-receivable_date_of_paymentError"></span>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-receivable_time_of_payment">Payment time <span class="so-label-optional">Optional</span></label>
                            <input type="time" class="form-control" id="so-editor-receivable_time_of_payment" name="receivable_time_of_payment" data-errors="receivable_time_of_payment" aria-describedby="so-editor-receivable_time_of_paymentError" value="{{ date('H:i') }}">
                            <span class="invalid-feedback" id="so-editor-receivable_time_of_paymentError"></span>
                        </div>
                        <div class="so-field so-field-wide">
                            <label for="so-editor-receivable_reference">Reference number <em>*</em></label>
                            <input class="form-control" id="so-editor-receivable_reference" name="receivable_reference" data-errors="receivable_reference" aria-describedby="so-editor-receivable_referenceError" required placeholder="Receipt, bank, or transaction reference">
                            <span class="invalid-feedback" id="so-editor-receivable_referenceError"></span>
                        </div>
                        <div class="so-field so-field-wide">
                            <label for="so-editor-receivable_payment_remarks">Payment notes <span class="so-label-optional">Optional</span></label>
                            <textarea class="form-control" id="so-editor-receivable_payment_remarks" name="receivable_payment_remarks" data-errors="receivable_payment_remarks" aria-describedby="so-editor-receivable_payment_remarksError" rows="2" placeholder="Additional payment details"></textarea>
                            <span class="invalid-feedback" id="so-editor-receivable_payment_remarksError"></span>
                        </div>
                    </div>
                    <div class="so-payment-upload">
                        <div class="so-field">
                            <label for="so-editor-payment_image_reference"><i class="bi bi-paperclip" aria-hidden="true"></i> Payment receipt <span class="so-label-optional">Optional</span></label>
                            <input class="form-control" type="file" id="so-editor-payment_image_reference" name="payment_image_reference" accept=".jpg,.jpeg,.png,.svg" data-payment-image data-errors="payment_image_reference" aria-describedby="so-editor-payment_image_referenceError">
                            <span class="invalid-feedback" id="so-editor-payment_image_referenceError"></span>
                            <small class="so-field-hint">JPG, PNG, or SVG. Maximum 10 MB. An existing receipt is kept unless replaced.</small>
                        </div>
                        <div class="img-holder so-payment-preview" id="so-editor-image_payment_div" hidden></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="so-entry-saving" data-submit-status role="status" hidden><span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Saving payment…</span>
                    <button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="reset" class="so-btn-secondary">Reset changes</button>
                    <button type="submit" class="so-btn-primary" id="so-editor-save-payment"><i class="bi bi-check2" aria-hidden="true"></i> Save payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
