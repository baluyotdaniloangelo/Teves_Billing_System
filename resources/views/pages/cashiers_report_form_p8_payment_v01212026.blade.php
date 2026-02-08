<!-- ADD BUTTON -->
<div align="right">
    <button type="button" class="btn btn-success bi bi-plus-circle"
            data-bs-toggle="modal" data-bs-target="#CRPH8_Modal">
    </button>
</div>

<br>
<style>
#CashiersReportPH8Table {
    width: 100% !important;
}
#ph8_footer_total{
	text-align: right;
}
</style>
<!-- PAYMENT TABLE -->
<table  class="table dataTable display nowrap cell-border"  id="CashiersReportPH8Table">
    <thead>
        <tr>
			<th>#</th>
            <th>Payment Type</th>
            <th class="text-right" >Amount</th>
            <th>Action</th>
        </tr>
    </thead>
	<tfoot>
        <tr>
            <th colspan="2" class="text-end">TOTAL</th>
            <th class="text-right" id="ph8_footer_total">₱ 0.00</th>
            <th></th>
        </tr>
    </tfoot>
</table>


<!-- ================= ADD PAYMENT MODAL ================= -->
<div class="modal fade" id="CRPH8_Modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Cash Payment</h5>
                <button type="button" class="btn btn-danger bi bi-x-circle"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="CRPH8_form" class="needs-validation">

                    <!-- PAYMENT TYPE -->
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Payment Type</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="payment_type" id="payment_type" required>
                                <option value="">-- Select Payment Type --</option>
                                <option value="limitless">Limitless Payment</option>
                                <option value="credit_debit">Credit / Debit</option>
                                <option value="gcash">GCASH</option>
                                <option value="online">Other Online Payment</option>
                            </select>
                        </div>
                    </div>

                    <!-- AMOUNT -->
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="number"
                                   class="form-control"
                                   name="payment_amount"
                                   id="payment_amount"
                                   step=".01"
                                   min="0"
                                   required>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm bi bi-save-fill" id="save-CRPH8">
                    Submit
                </button>
                <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill" id="clear-CRPH8-save">
                    Reset
                </button>
            </div>

                </form>
        </div>
    </div>
</div>

<!-- ================= UPDATE PAYMENT MODAL ================= -->
<div class="modal fade" id="Update_CRPH8_Modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Update Cash Payment</h5>
                <button type="button" class="btn btn-danger bi bi-x-circle"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="update_CRPH8_form" class="needs-validation">

                    <!-- PAYMENT TYPE -->
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Payment Type</label>
                        <div class="col-sm-8">
                            <select class="form-select"
                                    name="update_payment_type"
                                    id="update_payment_type"
                                    required>
                                <option value="limitless">Limitless Payment</option>
                                <option value="credit_debit">Credit / Debit</option>
                                <option value="gcash">GCASH</option>
                                <option value="online">Other Online Payment</option>
                            </select>
                        </div>
                    </div>

                    <!-- AMOUNT -->
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="number"
                                   class="form-control"
                                   name="update_payment_amount"
                                   id="update_payment_amount"
                                   step=".01"
                                   min="0"
                                   required>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm bi bi-save-fill" id="update-CRPH8">
                    Submit
                </button>
                <!--<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill" id="clear-CRPH8-update">
                    Reset
                </button>-->
            </div>

                </form>
        </div>
    </div>
</div>

<!-- ================= DELETE MODAL ================= -->
<div class="modal fade" id="CRPH8DeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header header_modal_bg">
                <h5 class="modal-title">Confirm Delete</h5>
                <div class="btn-sm btn-warning bi bi-exclamation-circle"></div>
            </div>

            <div class="modal-body warning_modal_bg">
                Are you sure you want to delete this payment?
                <hr>
                <strong>Payment Type:</strong>
                <span id="delete_payment_type"></span><br>
                <strong>Amount:</strong>
                ₱ <span id="delete_payment_amount"></span>
            </div>

            <div class="modal-footer footer_modal_bg">
                <button type="button" class="btn btn-danger"
                        id="deleteCRPH8Confirmed"
                        data-bs-dismiss="modal">
                    <i class="bi bi-trash3"></i> Delete
                </button>
                <button type="button" class="btn btn-primary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>

        </div>
    </div>
</div>
