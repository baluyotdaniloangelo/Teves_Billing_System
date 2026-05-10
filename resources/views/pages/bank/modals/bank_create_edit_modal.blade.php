<!-- CREATE BANK DETAILS MODAL -->
<div class="modal fade"
     id="BankDetailsModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <!-- MAIN ICON -->
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width:60px;height:60px;">

                        <i class="bi bi-bank2 fs-3"></i>

                    </div>

                    <!-- TITLE -->
                    <div>

                        <h4 class="modal-title fw-bold mb-0">
                            Create Bank Details
                        </h4>

                        <small class="opacity-75">
                            Add and manage banking information
                        </small>

                    </div>

                </div>

                <!-- CLOSE -->
                <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <!-- FORM -->
            <form class="needs-validation"
                  id="BankDetailsForm"
                  novalidate>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <!-- BANK INFORMATION -->
                    <div class="card border-0 bg-light rounded-4 mb-3">

                        <div class="card-body p-3">

                            <div class="row g-3">

                                <!-- BANK NAME -->
                                <div class="col-md-12">

                                    <label for="bank_name_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-bank text-primary"></i>

                                        </span>

                                        <span>
                                            Bank Name
                                        </span>

                                    </label>

                                    <input type="text"
                                           class="form-control form-control-lg rounded-3"
                                           id="bank_name"
                                           name="bank_name"
                                           placeholder="Enter Bank Name"
                                           autocomplete="off"
                                           required>

                                    <span class="invalid-feedback"
                                          id="bank_name_error">
                                    </span>

                                </div>

                                <!-- BRANCH -->
                                <div class="col-md-12">

                                    <label for="bank_branch_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-geo-alt text-info"></i>

                                        </span>

                                        <span>
                                            Branch
                                        </span>

                                    </label>

                                    <input type="text"
                                           class="form-control form-control-lg rounded-3"
                                           name="bank_branch"
                                           id="bank_branch"
                                           placeholder="Enter Branch Name"
                                           autocomplete="off"
                                           required>

                                    <span class="invalid-feedback"
                                          id="bank_branch_error">
                                    </span>

                                </div>

                                <!-- ACCOUNT NUMBER -->
                                <div class="col-md-12">

                                    <label for="bank_account_number_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-credit-card-2-front text-success"></i>

                                        </span>

                                        <span>
                                            Account Number
                                        </span>

                                    </label>

                                    <input type="text"
                                           class="form-control form-control-lg rounded-3"
                                           id="bank_account_number"
                                           name="bank_account_number"
                                           placeholder="Enter Account Number"
                                           autocomplete="off"
                                           required>

                                   <div class="invalid-feedback"
										 id="bank_account_number_error">

										Please enter Account Number.

									</div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 px-4 pb-4">

                    <!-- LOADING -->
                    <div id="loading_data_create"
                         style="display:none;">

                        <div class="spinner-border spinner-border-sm text-primary"
                             role="status">

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </div>
					<input type="hidden" id="bank_id">
                    <!-- RESET -->
                    <button type="reset"
                            class="btn btn-light rounded-3 px-4"
							id="reset-bank-form">

                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Reset

                    </button>

                    <!-- SUBMIT -->
                    <button type="submit"
                            class="btn btn-primary rounded-3 shadow-sm px-4"
                            id="save-bank">

                        <i class="bi bi-save-fill me-2"></i>
                        Save Bank Details

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- SUCCESS MODAL -->
<div class="modal fade"
     id="SuccessModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-body text-center p-4">

                <!-- ICON -->
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;">

                    <i class="bi bi-check-circle-fill text-success fs-1"></i>

                </div>

                <!-- TITLE -->
                <h5 class="fw-bold mb-2"
                    id="success_modal_title">

                    Success

                </h5>

                <!-- MESSAGE -->
                <div class="text-muted"
                     id="success_modal_message">

                    Record saved successfully.

                </div>

            </div>

        </div>

    </div>

</div>


<!-- VALIDATION ERROR MODAL -->
<div class="modal fade"
     id="ValidationErrorModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- BODY -->
            <div class="modal-body text-center p-4">

                <!-- ICON -->
                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;">

                    <i class="bi bi-exclamation-circle-fill text-danger fs-1"></i>

                </div>

                <!-- TITLE -->
                <h5 class="fw-bold text-danger mb-2">

                    Validation Error

                </h5>

                <!-- MESSAGE -->
                <div class="text-muted"
                     id="validation_error_message">

                    Something went wrong.

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 justify-content-center pb-4">

                <button type="button"
                        class="btn btn-danger rounded-3 px-4"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-2"></i>
                    Close

                </button>

            </div>

        </div>

    </div>

</div>