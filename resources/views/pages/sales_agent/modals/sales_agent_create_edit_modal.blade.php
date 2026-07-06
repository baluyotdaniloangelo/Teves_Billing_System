<!-- Account Creation MODAL -->
<div class="modal fade"
     id="CreateSalesAgentModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <!-- ICON -->
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width:60px;height:60px;">

                        <i class="bi bi-people-fill fs-3"></i>

                    </div>

                    <!-- TITLE -->
                    <div>

                        <h4 class="modal-title fw-bold mb-0" id="sales_agent_modal_title">
                            Sales Agent Creation
                        </h4>

                        <small class="opacity-75">
                            Add and manage Sales Agent information
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

<form class="needs-validation"
      id="SalesAgentformNew"
      novalidate>

    <!-- BODY -->
    <div class="modal-body p-4">

        <div class="row g-4">

            <div class="col-lg-12">

                <div class="card border-0 bg-light rounded-4">

                    <div class="card-body p-4">

                        <h6 class="fw-bold mb-4 text-success">
                            Sales Agent Information
                        </h6>

                        <!-- NAME -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">
                                <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                      style="width:34px;height:34px;">
                                    <i class="bi bi-person text-success"></i>
                                </span>
                                <span>Name</span>
                            </label>

                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   name="sales_agent_name"
                                   id="sales_agent_name"
                                   placeholder="Enter Name/Description"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"
                                 id="sales_agent_name_error"></div>

                        </div>

                        <!-- ADDRESS -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">
                                <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                      style="width:34px;height:34px;">
                                    <i class="bi bi-geo-alt text-primary"></i>
                                </span>
                                <span>Address</span>
                            </label>

                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   name="sales_agent_address"
                                   id="sales_agent_address"
                                   placeholder="Address"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"
                                 id="sales_agent_address_error"></div>

                        </div>

                        <!-- CONTACT NUMBER -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">
                                <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                      style="width:34px;height:34px;">
                                    <i class="bi bi-telephone-fill text-success"></i>
                                </span>
                                <span>Contact Number</span>
                            </label>

                            <input type="text"
                                   class="form-control form-control-lg rounded-3"
                                   name="sales_agent_contact_number"
                                   id="sales_agent_contact_number"
                                   placeholder="09XXXXXXXXX"
                                   autocomplete="off">

                            <div class="invalid-feedback"
                                 id="sales_agent_contact_number_error"></div>

                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">
                                <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                      style="width:34px;height:34px;">
                                    <i class="bi bi-envelope-fill text-primary"></i>
                                </span>
                                <span>Email Address</span>
                            </label>

                            <input type="email"
                                   class="form-control form-control-lg rounded-3"
                                   name="sales_agent_email_address"
                                   id="sales_agent_email_address"
                                   placeholder="Email Address"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"
                                 id="sales_agent_email_address_error"></div>

                        </div>

                        <!-- STATUS -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">
                                <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                      style="width:34px;height:34px;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </span>
                                <span>Status</span>
                            </label>

                            <select class="form-select form-select-lg rounded-3"
                                    name="sales_agent_status"
                                    id="sales_agent_status"
                                    required>

                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>

                            </select>

                            <div class="invalid-feedback"
                                 id="sales_agent_status_error"></div>

                        </div>

                    </div><!-- card-body -->

                </div><!-- card -->

            </div><!-- col -->

        </div><!-- row -->

    </div><!-- modal-body -->

    <!-- FOOTER -->
    <div class="modal-footer border-0 px-4 pb-4">

        <input type="hidden" id="sales_agent_id">

        <button type="reset"
                class="btn btn-light rounded-3 px-4"
                id="clear-SalesAgent">

            <i class="bi bi-arrow-counterclockwise me-2"></i>
            Reset

        </button>

        <button type="button"
                class="btn btn-success rounded-3 shadow-sm px-4"
                id="save-SalesAgent">

            <i class="bi bi-save-fill me-2"></i>
            Save

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

                    Please check the Required Input.

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