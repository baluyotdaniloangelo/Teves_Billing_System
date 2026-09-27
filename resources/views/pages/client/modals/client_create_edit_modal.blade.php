<!-- Account Creation MODAL -->
<div class="modal fade"
     id="CreateClientModal"
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

					<ul class="nav nav-tabs mb-3" id="PurchaseOrderTabs" role="tablist">

						<li class="nav-item">
							<button class="nav-link active"
									data-bs-toggle="tab"
									data-bs-target="#account-information-tab"
									type="button"
									role="tab">
								<i class="bi bi-info-circle me-1"></i>
								Account Information
							</button>
						</li>

						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#owner-information-tab"
									type="button"
									role="tab">
								<i class="bi bi-person-badge me-1"></i>
								Owner Information
							</button>
						</li>

						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#tax-payment-settings-tab"
									type="button"
									role="tab">
								<i class="bi bi-cash-coin me-1"></i>
								Tax & Payment Settings
							</button>
						</li>

						
					</ul>  
					
					
					
					<div class="tab-content" id="ClientTabsContent">

					<!-- ===================================== -->
					<!-- ACCOUNT INFORMATION -->
					<!-- ===================================== -->
					<div class="tab-pane fade show active"
						 id="account-information-tab"
						 role="tabpanel">

						<div class="tab-pane fade show active"
							 id="account-information-tab"
							 role="tabpanel">

							<div class="row">

								<div class="col-lg-12 mx-auto">

									<!-- CUSTOMER TYPE -->
									<div class="mb-4">

										<label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

											<span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
												  style="width:34px;height:34px;">
												<i class="bi bi-person text-success"></i>
											</span>

											<span>Customer Type</span>

										</label>

										<select class="form-select rounded-3"
												required
												name="customer_type"
												id="customer_type">

											<option value="Commercial Account" selected>
												Commercial Account
											</option>

											<option value="Household">Household</option>
											<option value="Outlet">Outlet</option>
											<option value="Fuel Retail Bulk">Fuel Retail Bulk</option>
											<option value="Industrial – Fuel">Industrial – Fuel</option>
											<option value="Industrial – LPG">Industrial – LPG</option>

										</select>

										<div class="invalid-feedback"
											 id="customer_type_error">
										</div>

									</div>


									<!-- COMPANY NAME -->
									<div class="mb-4">

										<label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

											<span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
												  style="width:34px;height:34px;">
												<i class="bi bi-building text-success"></i>
											</span>

											<span>Company Name</span>

										</label>

										<input type="text"
											   class="form-control rounded-3"
											   name="client_name"
											   id="client_name"
											   placeholder="Enter Company Name"
											   autocomplete="off"
											   required>

										<div class="invalid-feedback"
											 id="client_name_error">
										</div>

									</div>

<!-- CONTACT NUMBER + EMAIL ADDRESS -->
<div class="row g-3 mb-4">

    <!-- CONTACT NUMBER -->
    <div class="col-md-6">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-telephone-fill text-success"></i>
            </span>

            <span>Contact Number</span>

        </label>

        <input type="text"
               class="form-control rounded-3"
               name="client_contact_number"
               id="client_contact_number"
               placeholder="09XXXXXXXXX"
               autocomplete="off"
               required>

        <div class="invalid-feedback"
             id="client_contact_number_error">
        </div>

    </div>


    <!-- EMAIL ADDRESS -->
    <div class="col-md-6">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-envelope-fill text-success"></i>
            </span>

            <span>Email Address</span>

        </label>

        <input type="email"
               class="form-control rounded-3"
               name="client_email_address"
               id="client_email_address"
               placeholder="Enter Email Address"
               autocomplete="off">

        <div class="invalid-feedback"
             id="client_email_address_error">
        </div>

    </div>

</div>
<!-- ADDRESS -->
<div class="mb-4">

    <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-3">

        <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
              style="width:34px;height:34px;">
            <i class="bi bi-geo-alt text-primary"></i>
        </span>

        <span>Address</span>

    </label>


    <!-- ROW 1: House Number + Street -->
    <div class="row g-3 mb-3">

        <!-- House Number -->
        <div class="col-md-4">
            <label class="form-label fw-semibold">
                House Number
            </label>

            <input type="text"
                   class="form-control rounded-3"
                   name="client_house_number"
                   id="client_house_number"
                   placeholder="House Number"
                   autocomplete="off">

            <div class="invalid-feedback"
                 id="client_house_number_error">
            </div>
        </div>


        <!-- Street -->
        <div class="col-md-8">
            <label class="form-label fw-semibold">
                Street
            </label>

            <input type="text"
                   class="form-control rounded-3"
                   name="client_street"
                   id="client_street"
                   placeholder="Street"
                   autocomplete="off">

            <div class="invalid-feedback"
                 id="client_street_error">
            </div>
        </div>

    </div>

    <!-- ROW 4: Subdivision -->
    <div class="mb-3">

        <label class="form-label fw-semibold">
            Subdivision / Village
        </label>

        <input type="text"
               class="form-control rounded-3"
               name="client_subdivision"
               id="client_subdivision"
               placeholder="Subdivision / Village"
               autocomplete="off">

        <div class="invalid-feedback"
             id="client_subdivision_error">
        </div>

    </div>
	
    <!-- ROW 2: Barangay + City -->
    <div class="row g-3 mb-3">

        <!-- Barangay -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Barangay <span class="text-danger">*</span>
            </label>

            <input type="text"
                   class="form-control rounded-3"
                   name="client_barangay"
                   id="client_barangay"
                   placeholder="Barangay"
                   autocomplete="off"
                   required>

            <div class="invalid-feedback"
                 id="client_barangay_error">
            </div>
        </div>


        <!-- City / Municipality -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                City / Municipality <span class="text-danger">*</span>
            </label>

            <input type="text"
                   class="form-control rounded-3"
                   name="client_city"
                   id="client_city"
                   placeholder="City / Municipality"
                   autocomplete="off"
                   required>

            <div class="invalid-feedback"
                 id="client_city_error">
            </div>
        </div>

    </div>


    <!-- ROW 3: Province + Country -->
    <div class="row g-3 mb-3">

        <!-- Province -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Province <span class="text-danger">*</span>
            </label>

            <input type="text"
                   class="form-control rounded-3"
                   name="client_province"
                   id="client_province"
                   placeholder="Province"
                   autocomplete="off"
                   required>

            <div class="invalid-feedback"
                 id="client_province_error">
            </div>
        </div>


        <!-- Country -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Country <span class="text-danger">*</span>
            </label>

            <input type="text"
                   class="form-control rounded-3"
                   name="client_country"
                   id="client_country"
                   value="Philippines"
                   placeholder="Country"
                   autocomplete="off"
                   required>

            <div class="invalid-feedback"
                 id="client_country_error">
            </div>
        </div>

    </div>





    <!-- COMPLETE ADDRESS -->
    <div class="mb-3">

        <label class="form-label fw-semibold">
            Complete Address
        </label>

        <textarea class="form-control rounded-3"
                  name="client_address"
                  id="client_address"
                  rows="2"
                  placeholder="Complete address will be generated automatically"
                  readonly></textarea>

        <div class="invalid-feedback"
             id="client_address_error">
        </div>

    </div>

</div>




								</div>

							</div>

						</div>
					</div>

					<!-- ===================================== -->
					<!-- OWNER INFORMATION -->
					<!-- ===================================== -->

					<div class="tab-pane fade"
						 id="owner-information-tab"
						 role="tabpanel">

						<div class="row">

							<div class="col-lg-12 mx-auto">

								<!-- TITLE + GENDER -->
								<div class="row g-3 mb-4">

									<!-- TITLE -->
									<div class="col-md-4">

										<label class="form-label fw-semibold">
											Title
										</label>

										<input type="text"
											   class="form-control rounded-3"
											   name="client_title"
											   id="client_title"
											   placeholder="Dr., Engr."
											   autocomplete="off"
											   required>

										<div class="invalid-feedback"
											 id="client_title_error">
										</div>

									</div>


									<!-- GENDER -->
									<div class="col-md-8">

										<label class="form-label fw-semibold">
											Gender
										</label>

										<select class="form-select rounded-3"
												name="client_gender"
												id="client_gender"
												required>

											<option value="">Select Gender</option>
											<option value="Male">Male</option>
											<option value="Female">Female</option>

										</select>

										<div class="invalid-feedback"
											 id="client_gender_error">
										</div>

									</div>

								</div>

								<!-- ================================= -->
								<!-- FIRST NAME -->
								<!-- ================================= -->

								<div class="mb-4">

									<label class="form-label fw-semibold">
										First Name
									</label>

									<input type="text"
										   class="form-control rounded-3"
										   name="client_first_name"
										   id="client_first_name"
										   placeholder="Enter First Name"
										   autocomplete="off"
										   required>

									<div class="invalid-feedback"
										 id="client_first_name_error">
									</div>

								</div>


								<!-- ================================= -->
								<!-- MIDDLE NAME -->
								<!-- ================================= -->

								<div class="mb-4">

									<label class="form-label fw-semibold">
										Middle Name
									</label>

									<input type="text"
										   class="form-control rounded-3"
										   name="client_middle_name"
										   id="client_middle_name"
										   placeholder="Enter Middle Name"
										   autocomplete="off">

									<div class="invalid-feedback"
										 id="client_middle_name_error">
									</div>

								</div>


								<!-- ================================= -->
								<!-- LAST NAME -->
								<!-- ================================= -->

								<div class="mb-4">

									<label class="form-label fw-semibold">
										Last Name
									</label>

									<input type="text"
										   class="form-control rounded-3"
										   name="client_last_name"
										   id="client_last_name"
										   placeholder="Enter Last Name"
										   autocomplete="off"
										   required>

									<div class="invalid-feedback"
										 id="client_last_name_error">
									</div>

								</div>


								<!-- ================================= -->
								<!-- NAME EXTENSION -->
								<!-- ================================= -->

								<div class="mb-4">

									<label class="form-label fw-semibold">
										Name Extension
									</label>

									<select class="form-select rounded-3"
											name="client_name_extension"
											id="client_name_extension">

										<option value="">None</option>
										<option value="Jr.">Jr.</option>
										<option value="Sr.">Sr.</option>
										<option value="II">II</option>
										<option value="III">III</option>
										<option value="IV">IV</option>
										<option value="V">V</option>

									</select>

									<div class="invalid-feedback"
										 id="client_name_extension_error">
									</div>

								</div>


								<!-- ================================= -->
								<!-- BIRTHDAY -->
								<!-- ================================= -->

								<div class="mb-4">

									<label class="form-label fw-semibold">
										Birthday
									</label>

									<input type="date"
										   class="form-control rounded-3"
										   name="client_birthday"
										   id="client_birthday"
										   required>

									<div class="invalid-feedback"
										 id="client_birthday_error">
									</div>

								</div>


								<!-- ================================= -->
								<!-- REFERRED BY -->
								<!-- ================================= -->

								<div class="mb-4">

									<label class="form-label fw-semibold">
										Referred By
									</label>

									<input class="form-control rounded-3"
										   list="sales_agent_name"
										   name="sales_agent_name"
										   id="sales_agent_id"
										   autocomplete="off"
										   placeholder="Search Sales Agent">

									<datalist id="sales_agent_name">

										@foreach ($sales_agent_data as $sales_agent_data_cols)

											<option
												label="{{ $sales_agent_data_cols->sales_agent_name }}"
												data-id="{{ $sales_agent_data_cols->sales_agent_id }}"
												value="{{ $sales_agent_data_cols->sales_agent_name }}">
											</option>

										@endforeach

									</datalist>

									<div class="invalid-feedback"
										 id="sales_agent_name_error">
									</div>

								</div>

							</div>

						</div>

					</div>

										

					<!-- ===================================== -->
					<!-- TAX & PAYMENT SETTINGS -->
					<!-- ===================================== -->
					<div class="tab-pane fade"
						 id="tax-payment-settings-tab"
						 role="tabpanel">

						<div class="row">

							<div class="col-lg-12 mx-auto">

<!-- ID TYPE + ID NUMBER -->
<div class="row">

    <!-- ID TYPE -->
    <div class="col-md-5 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-person-vcard text-primary"></i>
            </span>

            <span>ID Type</span>

        </label>

        <select class="form-select rounded-3"
                name="client_id_type"
                id="client_id_type">

            <option value="">Select ID Type</option>

            <optgroup label="Tax / Business">
                <option value="TIN_ID">TIN ID</option>
                <option value="BIR_TIN">BIR TIN</option>
                <option value="SEC_REGISTRATION">SEC Registration No.</option>
                <option value="DTI_REGISTRATION">DTI Registration No.</option>
                <option value="BUSINESS_PERMIT">Business Permit No.</option>
                <option value="COMPANY_ID">Company ID</option>
            </optgroup>

            <optgroup label="Government-Issued ID">
                <option value="NATIONAL_ID">Philippine National ID</option>
                <option value="PASSPORT">Passport</option>
                <option value="DRIVERS_LICENSE">Driver's License</option>
                <option value="UMID">UMID</option>
                <option value="POSTAL_ID">Postal ID</option>
                <option value="PRC_ID">PRC ID</option>
                <option value="SSS_ID">SSS ID</option>
                <option value="PHILHEALTH_ID">PhilHealth ID</option>
                <option value="PAGIBIG_ID">Pag-IBIG ID</option>
                <option value="VOTERS_ID">Voter's ID</option>
            </optgroup>

            <optgroup label="Other IDs">
                <option value="SENIOR_CITIZEN_ID">Senior Citizen ID</option>
                <option value="PWD_ID">PWD ID</option>
                <option value="OTHER_ID">Other ID</option>
            </optgroup>

        </select>

        <div class="invalid-feedback"
             id="client_id_type_error">
        </div>

    </div>


    <!-- ID NUMBER -->
    <div class="col-md-7 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-receipt text-warning"></i>
            </span>

            <span>ID Number</span>

        </label>

        <input type="text"
               class="form-control rounded-3"
               name="client_tin"
               id="client_tin"
               placeholder="Enter ID Number"
               autocomplete="off">

        <div class="invalid-feedback"
             id="client_tin_error">
        </div>

    </div>

</div>


<!-- PAYMENT TERMS / LESS DISCOUNT -->
<div class="row">

    <!-- PAYMENT TERMS -->
    <div class="col-md-6 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-calendar-check text-secondary"></i>
            </span>

            <span>Payment Terms</span>

        </label>

        <select class="form-select rounded-3"
                name="default_payment_terms"
                id="default_payment_terms">

            <option value="Not Set" selected>Not Set</option>
            <option value="COD">COD</option>
            <option value="7 Days">7 Days</option>
            <option value="15 Days">15 Days</option>
            <option value="30 Days">30 Days</option>
            <option value="45 Days">45 Days</option>
            <option value="60 Days">60 Days</option>
            <option value="90 Days">90 Days</option>

        </select>

        <div class="invalid-feedback"
             id="default_payment_terms_error">
        </div>

    </div>


    <!-- LESS / DISCOUNT -->
    <div class="col-md-6 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-percent text-success"></i>
            </span>

            <span>Less / Discount</span>

        </label>

        <div class="input-group">

            <input type="number"
                   class="form-control rounded-start"
                   name="default_less_percentage"
                   id="default_less_percentage"
                   step=".01"
                   min="0"
                   placeholder="0.00">

            <span class="input-group-text">%</span>

        </div>

        <div class="invalid-feedback"
             id="default_less_percentage_error">
        </div>

    </div>

</div>


<!-- NET / VAT / WITHHOLDING TAX -->
<div class="row">

    <!-- NET -->
    <div class="col-md-4 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-calculator text-primary"></i>
            </span>

            <span>Net Value</span>

        </label>

        <div class="input-group">

            <input type="number"
                   class="form-control rounded-start"
                   name="default_net_percentage"
                   id="default_net_percentage"
                   step=".01"
                   min="0"
                   placeholder="0.00">

            <span class="input-group-text">%</span>

        </div>

        <div class="invalid-feedback"
             id="default_net_percentage_error">
        </div>

    </div>


    <!-- VAT -->
    <div class="col-md-4 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-receipt-cutoff text-info"></i>
            </span>

            <span>VAT Value</span>

        </label>

        <div class="input-group">

            <input type="number"
                   class="form-control rounded-start"
                   name="default_vat_percentage"
                   id="default_vat_percentage"
                   step=".01"
                   min="0"
                   placeholder="0.00">

            <span class="input-group-text">%</span>

        </div>

        <div class="invalid-feedback"
             id="default_vat_percentage_error">
        </div>

    </div>


    <!-- WITHHOLDING TAX -->
    <div class="col-md-4 mb-4">

        <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

            <span class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                  style="width:34px;height:34px;">
                <i class="bi bi-cash-coin text-danger"></i>
            </span>

            <span>Withholding Tax</span>

        </label>

        <div class="input-group">

            <input type="number"
                   class="form-control rounded-start"
                   name="default_withholding_tax_percentage"
                   id="default_withholding_tax_percentage"
                   step=".01"
                   min="0"
                   placeholder="0.00">

            <span class="input-group-text">%</span>

        </div>

        <div class="invalid-feedback"
             id="default_withholding_tax_percentage_error">
        </div>

    </div>

</div>




							</div>

						</div>

					</div>

				</div>
					
					
                       
                </div>
				
				</div>

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