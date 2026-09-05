<!-- =========================================================
DELETE CASHIER REPORT MODAL
========================================================= -->

<div class="modal fade"
     id="CashiersReportDeleteModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- =================================================
            HEADER
            ================================================= -->

            <div class="modal-header border-0 pb-0 px-4 pt-4">

                <div class="w-100 text-center">

                    <!-- WARNING ICON -->

                    <div class="bg-danger bg-opacity-10
                                rounded-circle
                                d-inline-flex
                                align-items-center
                                justify-content-center
                                mb-3"
                         style="width:75px;height:75px;">

                        <i class="bi bi-receipt-cutoff text-danger fs-1"></i>

                    </div>


                    <!-- TITLE -->

                    <h4 class="fw-bold text-danger mb-1">

                        Delete Cashier's Report

                    </h4>


                    <!-- SUBTITLE -->

                    <p class="text-muted mb-0">

                        This action cannot be undone.

                    </p>

                </div>

            </div>


            <!-- =================================================
            BODY
            ================================================= -->

            <div class="modal-body px-4 py-4">


                <!-- =================================================
                WARNING
                ================================================= -->

                <div class="alert alert-danger border-0 rounded-4 mb-4">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-exclamation-triangle-fill
                                  text-danger
                                  me-3
                                  fs-5">
                        </i>

                        <div>

                            <div class="fw-semibold mb-1">

                                Are you sure you want to delete this
                                Cashier's Report?

                            </div>

                            <small class="text-muted">

                                Please review the report information
                                before proceeding.

                            </small>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                DETAILS
                ================================================= -->

                <div class="card border-0 bg-light rounded-4">

                    <div class="card-body p-3">

                        <div class="row g-3">


                            <!-- =====================================
                            REPORT DATE
                            ====================================== -->

                            <div class="col-md-6">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-calendar-event
                                              me-1
                                              text-primary">
                                    </i>

                                    Report Date

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_report_date">

                                </div>

                            </div>


                            <!-- =====================================
                            BRANCH
                            ====================================== -->

                            <div class="col-md-6">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-building
                                              me-1
                                              text-success">
                                    </i>

                                    Branch

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_teves_branch">

                                </div>

                            </div>


                            <!-- =====================================
                            CASHIER
                            ====================================== -->

                            <div class="col-md-6">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-person-badge
                                              me-1
                                              text-info">
                                    </i>

                                    Cashier's Name

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_cashiers_name">

                                </div>

                            </div>


                            <!-- =====================================
                            EMPLOYEE ON DUTY
                            ====================================== -->

                            <div class="col-md-6">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-person-workspace
                                              me-1
                                              text-info">
                                    </i>

                                    Employee's On-Duty

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_forecourt_attendant">

                                </div>

                            </div>


                            <!-- =====================================
                            SHIFT
                            ====================================== -->

                            <div class="col-md-6">

                                <div class="small text-muted mb-1">

                                    <i class="bi bi-clock
                                              me-1
                                              text-warning">
                                    </i>

                                    Shift

                                </div>

                                <div class="fw-semibold"
                                     id="confirm_delete_shift">

                                </div>

                            </div>


                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
            FOOTER
            ================================================= -->

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
                        id="deleteCashiersReportConfirmed"
                        value="">

                    <i class="bi bi-trash3-fill me-2"></i>

                    Delete Cashier's Report

                </button>

            </div>

        </div>

    </div>

</div>