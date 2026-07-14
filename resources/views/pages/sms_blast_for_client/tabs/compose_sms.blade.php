<!-- PRODUCT TAB -->
<div class="tab-pane fade fade show active"
     id="bordered-ph1"
     role="tabpanel"
     aria-labelledby="ph1-tab">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <!-- TITLE -->
                <div class="d-flex align-items-center">

                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:55px;height:55px;">

                        <i class="bi bi-box-seam text-primary fs-4"></i>

                    </div>

                    <div>

                        <h5 class="card-title fw-bold mb-1">
                            SMS Blast (Per Account Type)
                        </h5>
						<!--
                        <small class="text-muted">
                            Product-based purchase order transactions
                        </small>
						-->
                    </div>

                </div>

                <!-- ACTION BUTTONS
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <div id="download_options_product"></div>

                    <div id="save_options_P"></div>

                    <button type="button"
                            class="btn btn-success rounded-3 shadow-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#PurchaseOrderProductModal">

                        <i class="bi bi-sliders me-2"></i>
                        Report Options

                    </button>

                </div>
				 -->
            </div>

        </div>

        <!-- BODY -->
        <div class="card-body p-4">
			
<!-- Start SMS Form -->
<form id="SmsBlastForm" novalidate>

<div class="row g-4">

    <!-- LEFT -->
    <div class="col-lg-4"></div>

    <!-- CENTER -->
    <div class="col-lg-4">

        <!-- Customer Type -->
        <div class="mb-3">

            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                      style="width:34px;height:34px;">

                    <i class="bi bi-people text-success"></i>

                </span>

                <span>Customer Type</span>

            </label>

            <select class="form-select rounded-3"
                    id="customer_type"
                    name="customer_type"
                    required>

                <option value="">-- Select Customer Type --</option>
                <option value="Commercial Account">Commercial Account</option>
                <option value="Household">Household</option>
                <option value="Outlet">Outlet</option>
                <option value="Fuel Station">Fuel Station</option>
                <option value="Fuel Customer">Fuel Customer</option>
                <option value="Industrial">Industrial</option>

            </select>

            <div class="invalid-feedback" id="customer_type_error"></div>

        </div>

        <!-- Message -->
        <div class="mb-3">

            <label class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                      style="width:34px;height:34px;">

                    <i class="bi bi-chat-left-text text-primary"></i>

                </span>

                <span>Message</span>

            </label>

            <textarea class="form-control rounded-3"
                      id="sms_content"
                      name="sms_content"
                      rows="6"
                      placeholder="Type your SMS message..."
                      required></textarea>

            <div class="d-flex justify-content-between mt-2">

                <small class="text-muted">

                    Characters:
                    <span id="sms_count">0</span>

                </small>

                <small class="text-muted">

                    Estimated SMS:
                    <span id="sms_parts">1</span>

                </small>

            </div>

            <div class="invalid-feedback" id="sms_content_error"></div>

        </div>

        <button type="button"
                class="btn btn-success w-100"
                id="send_sms_btn">

            <i class="bi bi-send-fill me-2"></i>

            Send SMS Broadcast

        </button>

    </div>

    <!-- RIGHT -->
    <div class="col-lg-4"></div>

</div>

</form>
<!-- End SMS Form -->



        </div> <!-- card-body -->

    </div> <!-- card -->

</div> <!-- tab-pane -->