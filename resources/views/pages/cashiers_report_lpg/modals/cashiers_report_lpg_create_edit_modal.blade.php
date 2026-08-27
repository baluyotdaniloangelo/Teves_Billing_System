```html
<!-- CREATE CASHIER REPORT LPG MODAL -->
<div class="modal fade"
     id="CashierReportLPGModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- =================================================
            HEADER
            ================================================= -->

            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <!-- MAIN ICON -->
                    <div class="bg-white bg-opacity-25 rounded-circle
                                d-flex align-items-center justify-content-center
                                me-3 flex-shrink-0"
                         style="width:60px;height:60px;">

                        <i class="bi bi-receipt-cutoff fs-3"></i>

                    </div>


                    <!-- TITLE -->
                    <div>

                        <h4 class="modal-title fw-bold mb-0">
                            Create Cashier's Report
                        </h4>

                        <small class="opacity-75">
                            Create and manage LPG cashier reports
                        </small>

                    </div>

                </div>


                <!-- CLOSE -->
                <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <!-- =================================================
            FORM
            ================================================= -->

            <form class="needs-validation"
                  id="CashierReportformNew"
                  novalidate>


                <!-- =================================================
                BODY
                ================================================= -->

                <div class="modal-body p-4">


                    <!-- =================================================
                    CASHIER REPORT INFORMATION
                    ================================================= -->

                    <div class="card border-0 bg-light rounded-4 mb-3">

                        <div class="card-body p-3">

                            <div class="row g-3">


                                <!-- =====================================
                                BRANCH
                                ====================================== -->

                                <div class="col-md-6">

                                    <label for="teves_branch"
                                           class="form-label fw-semibold
                                                  d-flex align-items-center
                                                  gap-2 mb-2">

                                        <span class="bg-success bg-opacity-10
                                                     rounded-circle
                                                     d-flex align-items-center
                                                     justify-content-center
                                                     flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-building text-success"></i>

                                        </span>

                                        <span>
                                            Branch
                                        </span>

                                    </label>


                                    <select class="form-select form-select-lg rounded-3"
                                            required
                                            name="teves_branch"
                                            id="teves_branch">

                                        @foreach ($teves_branch as $teves_branch_cols)

                                            <option value="{{ $teves_branch_cols->branch_id }}">

                                                {{ $teves_branch_cols->branch_code }}

                                            </option>

                                        @endforeach

                                    </select>


                                    <span class="invalid-feedback"
                                          id="teves_branchError">

                                        Please select Branch.

                                    </span>

                                </div>


                                <!-- =====================================
                                REPORT DATE
                                ====================================== -->

                                <div class="col-md-6">

                                    <label for="report_date"
                                           class="form-label fw-semibold
                                                  d-flex align-items-center
                                                  gap-2 mb-2">

                                        <span class="bg-primary bg-opacity-10
                                                     rounded-circle
                                                     d-flex align-items-center
                                                     justify-content-center
                                                     flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-calendar-event text-primary"></i>

                                        </span>

                                        <span>
                                            Report Date
                                        </span>

                                    </label>


                                    <input type="date"
                                           class="form-control form-control-lg rounded-3"
                                           name="report_date"
                                           id="report_date"
                                           value="{{ date('Y-m-d') }}"
                                           required>


                                    <span class="invalid-feedback"
                                          id="report_dateError">

                                        Please select Report Date.

                                    </span>

                                </div>


                                <!-- =====================================
                                CASHIER'S ON DUTY
                                ====================================== -->

                                <div class="col-md-6">

                                    <label for="cashiers_name"
                                           class="form-label fw-semibold
                                                  d-flex align-items-center
                                                  gap-2 mb-2">

                                        <span class="bg-info bg-opacity-10
                                                     rounded-circle
                                                     d-flex align-items-center
                                                     justify-content-center
                                                     flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-person-badge text-info"></i>

                                        </span>

                                        <span>
                                            Cashier's on Duty
                                        </span>

                                    </label>


                                    <input type="text"
                                           class="form-control form-control-lg rounded-3"
                                           name="cashiers_name"
                                           id="cashiers_name"
                                           value=""
                                           placeholder="Enter Cashier's Name"
                                           autocomplete="off"
                                           required>


                                    <span class="invalid-feedback"
                                          id="cashiers_nameError">

                                        Please enter Cashier's Name.

                                    </span>

                                </div>


                                <!-- =====================================
                                EMPLOYEE ON-DUTY
                                ====================================== -->

                                <div class="col-md-6">

                                    <label for="forecourt_attendant"
                                           class="form-label fw-semibold
                                                  d-flex align-items-center
                                                  gap-2 mb-2">

                                        <span class="bg-info bg-opacity-10
                                                     rounded-circle
                                                     d-flex align-items-center
                                                     justify-content-center
                                                     flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-person-workspace text-info"></i>

                                        </span>

                                        <span>
                                            Employee's On-Duty
                                        </span>

                                    </label>


                                    <input type="text"
                                           class="form-control form-control-lg rounded-3"
                                           name="forecourt_attendant"
                                           id="forecourt_attendant"
                                           value=""
                                           placeholder="Enter Employee's Name"
                                           autocomplete="off"
                                           required>


                                    <span class="invalid-feedback"
                                          id="forecourt_attendantError">

                                        Please enter Employee's Name.

                                    </span>

                                </div>


                                <!-- =====================================
                                SHIFT
                                ====================================== -->

                                <div class="col-md-6">

                                    <label for="shift"
                                           class="form-label fw-semibold
                                                  d-flex align-items-center
                                                  gap-2 mb-2">

                                        <span class="bg-warning bg-opacity-10
                                                     rounded-circle
                                                     d-flex align-items-center
                                                     justify-content-center
                                                     flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-clock text-warning"></i>

                                        </span>

                                        <span>
                                            Shift
                                        </span>

                                    </label>


                                    <select class="form-select form-select-lg rounded-3"
                                            required
                                            name="shift"
                                            id="shift">

                                        <option value="1st Shift">
                                            1st Shift
                                        </option>

                                        <option value="2nd Shift">
                                            2nd Shift
                                        </option>

                                        <option value="3rd Shift">
                                            3rd Shift
                                        </option>

                                        <option value="4th Shift">
                                            4th Shift
                                        </option>

                                        <option value="5th Shift">
                                            5th Shift
                                        </option>

                                        <option value="6th Shift">
                                            6th Shift
                                        </option>

                                    </select>


                                    <span class="invalid-feedback"
                                          id="shiftError">

                                        Please select Shift.

                                    </span>

                                </div>


                                <!-- =====================================
                                REMARKS
                                ====================================== -->

                                <div class="col-md-6">

                                    <label for="cashier_report_remarks"
                                           class="form-label fw-semibold
                                                  d-flex align-items-center
                                                  gap-2 mb-2">

                                        <span class="bg-secondary bg-opacity-10
                                                     rounded-circle
                                                     d-flex align-items-center
                                                     justify-content-center
                                                     flex-shrink-0"
                                              style="width:34px;height:34px;">

                                            <i class="bi bi-chat-left-text text-secondary"></i>

                                        </span>

                                        <span>
                                            Remarks
                                        </span>

                                    </label>


                                    <input type="text"
                                           class="form-control form-control-lg rounded-3"
                                           name="cashier_report_remarks"
                                           id="cashier_report_remarks"
                                           placeholder="Enter remarks (optional)"
                                           autocomplete="off">


                                    <span class="invalid-feedback"
                                          id="cashier_report_remarksError">
                                    </span>

                                </div>


                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                FOOTER
                ================================================= -->

                <div class="modal-footer border-0 px-4 pb-4">


                    <!-- LOADING -->

                    <div id="loading_data_create"
                         style="display:none;">

                        <div class="spinner-border spinner-border-sm text-success"
                             role="status">

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </div>


                    <!-- RESET -->

                    <button type="reset"
                            class="btn btn-light rounded-3 px-4"
                            id="clear-cashiers-report">

                        <i class="bi bi-arrow-counterclockwise me-2"></i>

                        Reset

                    </button>


                    <!-- SUBMIT -->

                    <button type="submit"
                            class="btn btn-success rounded-3 shadow-sm px-4"
                            id="save-cashiers-report">

                        <i class="bi bi-save-fill me-2"></i>

                        Save Cashier's Report

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
                <h5 class="fw-bold text-danger mb-2" id="action_error_message">

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