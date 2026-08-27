<!-- DELETE BANK DETAILS MODAL -->
<div class="modal fade"
     id="BankDetailsDeleteModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header border-0 pb-0 px-4 pt-4">

                <div class="w-100 text-center">

                    <!-- WARNING ICON -->
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:75px;height:75px;">

                        <i class="bi bi-bank2 text-danger fs-1"></i>

                    </div>

                    <!-- TITLE -->
                    <h4 class="fw-bold text-danger mb-1">

                        Delete Bank Details

                    </h4>

                    <!-- SUBTITLE -->
                    <p class="text-muted mb-0">

                        This action cannot be undone.

                    </p>

                </div>

            </div>

            <!-- BODY -->
            <div class="modal-body px-4 py-4">

                <!-- WARNING -->
                <div class="alert alert-danger border-0 rounded-4 mb-4">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-exclamation-triangle-fill text-danger me-3 fs-5"></i>

                        <div>

                            <div class="fw-semibold mb-1">

                                Are you sure you want to delete this Bank Details record?

                            </div>

                            <small class="text-muted">

                                Please review the banking information before proceeding.

                            </small>

                        </div>

                    </div>

                </div>

                <!-- DETAILS -->
                <div class="card border-0 bg-light rounded-4">

                    <div class="card-body p-3">

                        <div class="row g-3">

                            <!-- BANK NAME -->
                            <div class="col-md-12">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-bank me-1 text-primary"></i>
                                    Bank Name

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_bank_name">
                                </div>

                            </div>

                            <!-- BRANCH -->
                            <div class="col-md-12">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-geo-alt me-1 text-info"></i>
                                    Branch

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_bank_branch">
                                </div>

                            </div>

                            <!-- ACCOUNT NUMBER -->
                            <div class="col-md-12">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-credit-card-2-front me-1 text-success"></i>
                                    Account Number

                                </div>

                                <div class="fw-bold text-danger"
                                     id="confirm_delete_bank_account_number">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">

                <!-- CANCEL -->
                <button type="button"
                        class="btn btn-light rounded-3 px-4"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-2"></i>
                    Cancel

                </button>

                <!-- DELETE -->
                <button type="button"
                        class="btn btn-danger rounded-3 shadow-sm px-4"
                        data-bs-dismiss="modal"
                        id="deleteBankDetailsConfirmed"
                        value="">

                    <i class="bi bi-trash3-fill me-2"></i>
                    Delete Bank Details

                </button>

            </div>

        </div>

    </div>

</div>