<!-- Account Creation MODAL -->
<div class="modal fade"
     id="CreateClientModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

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

                        <h4 class="modal-title fw-bold mb-0" id="client_modal_title">
                            Account Creation
                        </h4>

                        <small class="opacity-75">
                            Add and manage Account information
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
                  id="ClientformNew"
                  novalidate>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <div class="row g-4">

                        <!-- LEFT SIDE -->
                        <div class="col-lg-6">

                            <div class="card border-0 bg-light rounded-4 h-90">

                                <div class="card-body p-4">

                                    <h6 class="fw-bold mb-4 text-success">
                                        Account Information
                                    </h6>

                                    <!-- CLIENT NAME -->
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
                                               name="client_name"
                                               id="client_name"
                                               placeholder="Enter Name/Description"
                                               autocomplete="off"
                                               required>

                                        <div class="invalid-feedback"
                                             id="client_name_error">
                                        </div>

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

                                        <textarea class="form-control rounded-3"
                                                  name="client_address"
                                                  id="client_address"
                                                  rows="3"
                                                  placeholder="Enter Complete Address"
                                                  required></textarea>

                                        <div class="invalid-feedback"
                                             id="client_address_error">
                                        </div>

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
           name="client_contact_number"
           id="client_contact_number"
           placeholder="09XXXXXXXXX"
           autocomplete="off">

    <div class="invalid-feedback"
         id="client_contact_number_error">
    </div>

</div>

<!-- AGE -->
<div class="mb-3">

    <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

        <span class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
              style="width:34px;height:34px;">

            <i class="bi bi-person-badge-fill text-info"></i>

        </span>

        <span>Age</span>

    </label>

    <input type="number"
           class="form-control form-control-lg rounded-3"
           name="client_age"
           id="client_age"
           min="1"
           max="120"
           placeholder="Enter Age">

    <div class="invalid-feedback"
         id="client_age_error">
    </div>

</div>
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                            <span class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                  style="width:34px;height:34px;">

                                                <i class="bi bi-receipt text-warning"></i>

                                            </span>

                                            <span>Referred By</span>

                                        </label>

                                        <input class="form-control form-control-lg rounded-3"
                                           list="sales_agent_name"
                                           name="sales_agent_name"
                                           id="sales_agent_id"
                                           autocomplete="off"
                                           placeholder="Search Agent">

                                    <datalist id="sales_agent_name">

                                        @foreach ($sales_agent_data as $sales_agent_data_cols)

                                            <option
                                                label="{{$sales_agent_data_cols->sales_agent_name}}"
                                                data-id="{{$sales_agent_data_cols->sales_agent_id}}"
                                                value="{{$sales_agent_data_cols->sales_agent_name}}">
                                            </option>

                                        @endforeach

                                    </datalist>

                                    </div>
								
                                </div>

                            </div>

                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-lg-6">

                            <!-- TAX SETTINGS -->
                            <div class="card border-0 bg-light rounded-4 mb-4">

                                <div class="card-body p-4">

                                    <h6 class="fw-bold mb-4 text-success">
                                        Tax & Payment Settings
                                    </h6>
									
                                    <!-- TIN -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                            <span class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                  style="width:34px;height:34px;">

                                                <i class="bi bi-receipt text-warning"></i>

                                            </span>

                                            <span>TIN Number</span>

                                        </label>

                                        <input type="text"
                                               class="form-control form-control-lg rounded-3"
                                               name="client_tin"
                                               id="client_tin"
                                               placeholder="000-000-000-000"
                                               autocomplete="off">
											   
										<div class="invalid-feedback"
                                             id="client_tin_error">
                                        </div>

                                    </div>

                                    <!-- DISCOUNT -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Less / Discount
                                        </label>

                                        <div class="input-group">

                                            <input type="number"
                                                   class="form-control form-control-lg rounded-start"
                                                   name="default_less_percentage"
                                                   id="default_less_percentage"
                                                   step=".01"
                                                   min="0"
                                                   placeholder="0.00">

                                            <span class="input-group-text">
                                                %
                                            </span>

                                        </div>

                                    </div>

                                    <!-- NET -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Net Value
                                        </label>

                                        <div class="input-group">

                                            <input type="number"
                                                   class="form-control form-control-lg rounded-start"
                                                   name="default_net_percentage"
                                                   id="default_net_percentage"
                                                   step=".01"
                                                   min="0"
                                                   placeholder="0.00">

                                            <span class="input-group-text">
                                                %
                                            </span>

                                        </div>

                                    </div>

                                    <!-- VAT -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            VAT Value
                                        </label>

                                        <div class="input-group">

                                            <input type="number"
                                                   class="form-control form-control-lg rounded-start"
                                                   name="default_vat_percentage"
                                                   id="default_vat_percentage"
                                                   step=".01"
                                                   min="0"
                                                   placeholder="0.00">

                                            <span class="input-group-text">
                                                %
                                            </span>

                                        </div>

                                    </div>

                                    <!-- WITHHOLDING -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Withholding Tax
                                        </label>

                                        <div class="input-group">

                                            <input type="number"
                                                   class="form-control form-control-lg rounded-start"
                                                   name="default_withholding_tax_percentage"
                                                   id="default_withholding_tax_percentage"
                                                   step=".01"
                                                   min="0"
                                                   placeholder="0.00">

                                            <span class="input-group-text">
                                                %
                                            </span>

                                        </div>

                                    </div>

                                    <!-- PAYMENT TERMS -->
                                    <div class="mb-0">

                                        <label class="form-label fw-semibold">
                                            Payment Terms
                                        </label>

                                        <input type="text"
                                               class="form-control form-control-lg rounded-3"
                                               name="default_payment_terms"
                                               id="default_payment_terms"
                                               placeholder="e.g. 30 Days">

                                    </div>

                                </div>

                            </div>



                    </div>

                </div></div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 px-4 pb-4">
					<input type="hidden" id="client_id">
                    <!-- RESET -->
                    <button type="reset"
                            class="btn btn-light rounded-3 px-4"
                            id="clear-client">

                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Reset

                    </button>

                    <!-- SAVE -->
                    <button type="button"
                            class="btn btn-success rounded-3 shadow-sm px-4"
                            id="save-client">

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