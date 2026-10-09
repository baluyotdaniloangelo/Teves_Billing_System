<!-- ADD BUTTON -->
<div align="right">
    <button type="button" class="btn btn-success bi bi-plus-circle"
            data-bs-toggle="modal" data-bs-target="#CRPH9_Modal">
    </button>
</div>

<br>
<style>
#CashiersReportPH9Table {
    width: 100% !important;
}
#PH9_footer_total{
	text-align: right;
}
</style>
<!-- PAYMENT TABLE -->
<table  class="table dataTable display nowrap cell-border"  id="CashiersReportPH9Table">
    <thead>
        <tr>
			<th>#</th>
            <th>Bank</th>
			<th>Date</th>
			<th>Transaction Reference</th>
			<th>Remarks</th>
			<th class="text-right" >Amount</th>
            <th>Action</th>
        </tr>
    </thead>
	<tfoot>
        <tr>
            <th colspan="5" class="text-end">TOTAL</th>
            <th class="text-right" id="PH9_footer_total">₱ 0.00</th>
            <th class="text-end"></th>
        </tr>
    </tfoot>
</table>


<!-- ================= ADD CASH DEPOSIT MODAL ================= -->
<div class="modal fade" id="CRPH9_Modal" tabindex="-1">
    
    <div class="modal-dialog modal-xl modal-dialog-centered">
        
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-light border-0 px-4 py-3">

                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">

                    <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                          style="width:42px;height:42px;">

                        <i class="bi bi-cash-coin text-success"></i>

                    </span>

                    <span>
                        Add Cash Deposit
                    </span>

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body px-4 py-4">

                <form id="CRPH9_form"
                      class="needs-validation"
                      novalidate>

                    <div class="row g-4">

                        <!-- LEFT SIDE -->
                        <div class="col-md-8">

                            <!-- BANK -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2">

                                    <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-bank text-primary"></i>

                                    </span>

                                    <span>
                                        Bank
                                    </span>

                                </label>

                                <select class="form-select rounded-3"
                                        name="bank_idx"
                                        id="bank_idx"
                                        required>

                                    <option value="">
                                        -- Select Bank --
                                    </option>

                                    @foreach($bank_list as $bank)

                                        <option value="{{ $bank->bank_id }}">

                                            {{ $bank->bank_name }}

                                            @if($bank->bank_branch)
                                                | {{ $bank->bank_account_number }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <div class="invalid-feedback">

                                    Please select a bank.

                                </div>

                            </div>

                            <!-- DATE -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2">

                                    <span class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-calendar-event text-info"></i>

                                    </span>

                                    <span>
                                        Date
                                    </span>

                                </label>

                                <input type="datetime-local"
                                       class="form-control rounded-3"
                                       name="cash_deposit_date"
                                       id="cash_deposit_date"
                                       required>

                                <div class="invalid-feedback">

                                    Please select deposit date.

                                </div>

                            </div>

                            <!-- AMOUNT -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2">

                                    <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-currency-dollar text-success"></i>

                                    </span>

                                    <span>
                                        Amount
                                    </span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₱
                                    </span>

                                    <input type="number"
                                           class="form-control"
                                           name="cash_deposit_amount"
                                           id="cash_deposit_amount"
                                           step=".01"
                                           min="0"
                                           placeholder="0.00"
                                           required>

                                    <div class="invalid-feedback">

                                        Please enter deposit amount.

                                    </div>

                                </div>

                            </div>

                            <!-- REFERENCE NUMBER -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2">

                                    <span class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-upc-scan text-warning"></i>

                                    </span>

                                    <span>
                                        Reference Number
                                    </span>

                                </label>

                                <input type="text"
                                       class="form-control rounded-3"
                                       name="cash_deposit_reference"
                                       id="cash_deposit_reference"
                                       placeholder="Transaction / Deposit Slip No.">

                            </div>

                            <!-- REMARKS -->
                            <div class="mb-0">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2">

                                    <span class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-chat-left-text text-secondary"></i>

                                    </span>

                                    <span>
                                        Remarks
                                    </span>

                                </label>

                                <textarea class="form-control rounded-3"
                                          name="cash_deposit_remarks"
                                          id="cash_deposit_remarks"
                                          rows="4"
                                          placeholder="Optional notes..."></textarea>

                            </div>

                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-md-4">

                            <div class="border rounded-4 bg-light p-3 h-100">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-3">

                                    <span class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-image text-danger"></i>

                                    </span>

                                    <span>
                                        Receipt Preview
                                    </span>

                                </label>

                                <!-- IMAGE PREVIEW -->
                                <div class="text-center mb-3">

                                    <img id="cash_deposit_preview"
                                         src=""
                                         class="img-fluid rounded-4 border shadow-sm"
                                         style="max-height:300px; display:none;">

                                </div>

                                <!-- FILE INPUT -->
                                <div class="mb-2">

                                    <label class="form-label fw-semibold">

                                        Upload Receipt

                                    </label>

                                    <input type="file"
                                           class="form-control rounded-3"
                                           name="cash_deposit_photo"
                                           id="cash_deposit_photo"
                                           accept="image/*">

                                </div>

                                <small class="text-muted">

                                    JPG, PNG (Max 10MB)

                                </small>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 py-3 d-flex justify-content-between">

                <!-- RESET -->
                <button type="reset"
                        form="CRPH9_form"
                        class="btn btn-light rounded-3 px-4"
                        id="clear-CRPH9-save">

                    <i class="bi bi-arrow-counterclockwise me-2"></i>

                    Reset

                </button>

                <!-- SAVE -->
                <button type="submit"
                        class="btn btn-success rounded-3 shadow-sm px-4"
                        id="save-CRPH9">

                    <i class="bi bi-save me-2"></i>

                    Save Deposit

                </button>

            </div>

        </div>

    </div>

</div>

<!-- ================= Update CASH DEPOSIT MODAL ================= -->
<div class="modal fade" id="Update_CRPH9_Modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content shadow">

      <!-- HEADER -->
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-semibold">
          <i class="bi bi-cash-coin me-2"></i> Update Cash Deposit
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body px-4 py-3">
        <form id="update_CRPH9_form" class="needs-validation" novalidate>

          <div class="row">

            <!-- LEFT SIDE (FORM) -->
            <div class="col-md-8">

                            <!-- BANK -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold d-flex align-items-center gap-2">

                                    <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;">

                                        <i class="bi bi-bank text-primary"></i>

                                    </span>

                                    <span>
                                        Bank
                                    </span>

                                </label>

                                <select class="form-select rounded-3"
                                        name="update_bank_idx"
                                        id="update_bank_idx"
                                        required>

                                    <option value="">
                                        -- Select Bank --
                                    </option>

                                    @foreach($bank_list as $bank)

                                        <option value="{{ $bank->bank_id }}">

                                            {{ $bank->bank_name }}

                                            @if($bank->bank_branch)
                                                | {{ $bank->bank_account_number }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <div class="invalid-feedback">

                                    Please select a bank.

                                </div>

                            </div>

              <!-- DATE -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Date</label>
                <input type="datetime-local" 
                       class="form-control" 
                       name="update_cash_deposit_date" 
                       id="update_cash_deposit_date">
              </div>

              <!-- AMOUNT -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Amount</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number"
                         class="form-control"
                         name="update_cash_deposit_amount"
                         id="update_cash_deposit_amount"
                         step=".01"
                         min="0"
                         placeholder="0.00"
                         required>
                </div>
              </div>

              <!-- REFERENCE -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Reference Number</label>
                <input type="text" 
                       class="form-control" 
                       name="update_cash_deposit_reference" 
                       id="update_cash_deposit_reference"
                       placeholder="Transaction / Deposit Slip No.">
              </div>

              <!-- REMARKS -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Remarks</label>
                <textarea class="form-control" 
                          name="update_cash_deposit_remarks" 
                          id="update_cash_deposit_remarks" 
                          rows="3"
                          placeholder="Optional notes..."></textarea>
              </div>

            </div>

            <!-- RIGHT SIDE (PHOTO) -->
            <div class="col-md-4 border-start">

              <div class="ps-md-3">

                <label class="form-label fw-semibold">Receipt Preview</label>

                <!-- CURRENT / PREVIEW IMAGE -->
                <div class="text-center mb-3">
                  <img id="current_photo_preview" 
                       src="" 
                       class="img-fluid rounded border shadow-sm"
                       style="max-height:300px; display:none;">
                </div>

                <!-- UPLOAD -->
                <div class="mb-3">
                  <label class="form-label">Change Photo</label>
                  <input type="file"
                         class="form-control"
                         name="update_cash_deposit_photo"
                         id="update_cash_deposit_photo"
                         accept="image/*">
                  <small class="text-muted">JPG, PNG (Max 10MB)</small>
                </div>

              </div>

            </div>

          </div>

        </form>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer justify-content-end px-4">
        <button type="submit" 
                class="btn btn-success px-4"
                id="update-CRPH9">
          <i class="bi bi-save me-1"></i> Update Deposit
        </button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="Preview_CRPH9_Modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-receipt me-2"></i> Cash Deposit Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="row">

          <!-- LEFT: IMAGE -->
          <div class="col-md-5 text-center">
            <img id="preview_image"
                 src=""
                 class="img-fluid rounded border shadow"
                 style="max-height:500px; display:none;">

            <div id="no_image_text" class="text-muted">
              No image available
            </div>
          </div>

          <!-- RIGHT: DETAILS -->
          <div class="col-md-7">

            <table class="table table-sm">
              <tr>
                <th>Bank</th>
                <td id="preview_bank"></td>
              </tr>
              <tr>
                <th>Amount</th>
                <td id="preview_amount"></td>
              </tr>
              <tr>
                <th>Reference</th>
                <td id="preview_reference"></td>
              </tr>
              <tr>
                <th>Date</th>
                <td id="preview_date"></td>
              </tr>
            </table>

          </div>

        </div>

      </div>

    </div>
  </div>
</div>

<!-- ================= DELETE MODAL ================= -->
<div class="modal fade" id="CRPH9DeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header header_modal_bg">
                <h5 class="modal-title">Confirm Delete</h5>
                <div class="btn-sm btn-warning bi bi-exclamation-circle"></div>
            </div>

            <div class="modal-body warning_modal_bg">
                Are you sure you want to delete this Cash Deposit?
                <hr>
                <strong>Bank:</strong>
                <span id="delete_cash_deposit_bank"></span><br>
				<strong>Bank:</strong>
                <span id="delete_cash_deposit_date"></span><br>
                <strong>Amount:</strong>
                ₱ <span id="delete_cash_deposit_amount"></span>
            </div>

            <div class="modal-footer footer_modal_bg">
                <button type="button" class="btn btn-danger"
                        id="deleteCRPH9Confirmed"
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
