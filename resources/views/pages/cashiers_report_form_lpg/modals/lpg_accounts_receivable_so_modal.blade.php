<!--==================================================
CASHIER'S REPORT
LPG ACCOUNTS RECEIVABLES COLLECTION
ADD / EDIT MODAL
==================================================-->

<div class="modal fade"
     id="CashierReportLpgArModal"
     tabindex="-1"
     aria-labelledby="CashierReportLpgArModalLabel"
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
                        <h5 class="modal-title fw-bold mb-0" id="CashierReportLpgArModalLabel">
                            Add Accounts Receivable Collection
                        </h5>
                        <small class="text-white-50">Enter LPG collection details</small>
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
                      id="CashierReportLpgArForm"
                      novalidate>
                    @csrf

                    <input type="hidden"
                           id="cashiers_report_lpg_ar_id"
                           name="cashiers_report_lpg_ar_id"
                           value="0">

                    <input type="hidden"
                           id="cashiers_report_id"
                           name="cashiers_report_id"
                           value="{{ $cashiers_report_id ?? 0 }}">

                    <div class="row g-3">
						<!--
                        <div class="col-md-4">
                            <label for="ar_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-2 text-success"></i>Date
                            </label>
                            <input type="date"
                                   class="form-control form-control-lg rounded-3"
                                   id="ar_date"
                                   name="ar_date"
                                   required>
                            <div class="invalid-feedback" id="ar_dateError">
                                Please select a date.
                            </div>
                        </div>
						-->
						<div class="col-md-12">
                            <label for="dr_number" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-text me-2 text-success"></i>DR Number
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   id="dr_number"
                                   name="dr_number"
                                   maxlength="100"
                                   required
                                   autocomplete="off"
                                   placeholder="Enter DR number">
                            <div class="invalid-feedback" id="dr_numberError">
                                Please enter the DR number.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="ar_account_id" class="form-label fw-semibold">
                                <i class="bi bi-person-lines-fill me-2 text-success"></i>Account Name
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   list="lpgArClientNameList"
                                   id="ar_account_id"
                                   name="ar_account_id"
                                   required
                                   autocomplete="off"
                                   placeholder="Select account name">
								   
								   
                            <datalist id="lpgArClientNameList">
                                @foreach ($client_data as $client_data_cols)
									<option label='{{$client_data_cols->client_name}}' data-id='{{$client_data_cols->client_id}}' value='{{$client_data_cols->client_name}}'></option>
								@endforeach
                            </datalist>
                            <div class="invalid-feedback" id="account_nameError">
                                Please select an account name.
                            </div>
                        </div>

                        

                        <div class="col-12">
                            <label for="remarks" class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text me-2 text-success"></i>Remarks
                            </label>
                            <textarea class="form-control rounded-3"
                                      id="remarks"
                                      name="remarks"
                                      rows="3"
                                      placeholder="Optional remarks"></textarea>
                            <div class="invalid-feedback" id="remarksError">
                                Please check the remarks.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="amount_received" class="form-label fw-semibold">
                                <i class="bi bi-cash-coin me-2 text-success"></i>Amount Received
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                       class="form-control rounded-end-3"
                                       id="amount_received"
                                       name="amount_received"
                                       min="0"
                                       step="0.01"
                                       required
                                       placeholder="0.00">
                            </div>
                            <div class="invalid-feedback" id="amount_receivedError">
                                Please enter the amount received.
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="reset"
                        class="btn btn-light border rounded-3 px-4"
                        id="clear-lpg-ar"
                        form="CashierReportLpgArForm">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </button>

                <button type="submit"
                        form="CashierReportLpgArForm"
                        class="btn btn-success rounded-3 px-4 shadow-sm"
                        id="save-lpg-ar">
                    <i class="bi bi-save-fill me-2"></i>Save Collection
                </button>
            </div>

        </div>
    </div>
	
	
</div>


<!-- ========================================== -->
<!-- DELETE LPG AR COLLECTION MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="CashierReportLpgArDeleteModal"
     tabindex="-1"
     aria-labelledby="CashierReportLpgArDeleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #dc3545, #b02a37);">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3"
                         style="width:44px;height:44px;">
                        <i class="bi bi-trash3-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="CashierReportLpgArDeleteModalLabel">
                            Delete AR Collection
                        </h6>
                        <small class="text-white-50">Please confirm this deletion.</small>
                    </div>
                </div>

                <button type="button"
                        class="btn btn-light btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:32px;height:32px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-warning border-0 rounded-3 d-flex align-items-start mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <div class="small">
                        Are you sure you want to delete this AR collection?
                        <strong>This record will be removed from the report.</strong>
                    </div>
                </div>

                <input type="hidden" id="lpg_ar_delete_id">

                <div class="card border-0 bg-light rounded-3">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Date</span>
                            <span class="fw-semibold small text-end" id="lpg_ar_delete_date">-</span>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Client</span>
                            <span class="fw-semibold small text-end" id="lpg_ar_delete_client">-</span>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">DR Number</span>
                            <span class="fw-semibold small text-end" id="lpg_ar_delete_dr_number">-</span>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Remarks</span>
                            <span class="fw-semibold small text-end" id="lpg_ar_delete_remarks">-</span>
                        </div>

                        <div class="d-flex justify-content-between pt-2">
                            <span class="text-muted small fw-semibold">Amount Received</span>
                            <span class="text-danger fw-bold">
                                ₱ <span id="lpg_ar_delete_amount_received">0.00</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-2">
                <button type="button"
                        class="btn btn-light border btn-sm rounded-3 px-3"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>

                <button type="button"
                        class="btn btn-danger btn-sm rounded-3 px-3"
                        id="deleteLpgArConfirmed">
                    <i class="bi bi-trash3-fill me-2"></i>Delete AR Collection
                </button>
            </div>

        </div>
    </div>
</div>


