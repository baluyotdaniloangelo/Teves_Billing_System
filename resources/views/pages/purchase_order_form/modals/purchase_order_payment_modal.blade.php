<!-- =========================== -->
<!-- Add Purchase Order Payment -->
<!-- =========================== -->
<div class="modal fade" id="AddPaymentModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg">

            <!-- HEADER -->
            <div class="modal-header bg-success border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <div class="rounded-3 bg-white bg-opacity-25 d-flex align-items-center justify-content-center me-3"
                        style="width:58px;height:58px;">

                        <i class="bi bi-credit-card-2-front-fill fs-3 text-white"></i>

                    </div>

                    <div>

                        <h2 class="mb-0 fw-bold text-white">

                            Purchase Order Payment

                        </h2>

                        <small class="text-white-50">

                            Record purchase order payment transaction

                        </small>

                    </div>

                </div>

                <button type="button"
                        class="btn btn-light rounded-circle"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <!-- BODY -->

            <div class="modal-body bg-light p-4">

                <form id="AddPayment"
                      action="{{ route('save_purchase_order_payment') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="row g-4">

                        <!-- LEFT -->

                        <div class="col-lg-6">

                            <div class="card border-0 shadow-sm rounded-4">

                                <div class="card-body p-4">

                                    <h4 class="fw-bold text-primary">

                                        <i class="bi bi-credit-card me-2"></i>

                                        Payment Information

                                    </h4>

                                    <p class="text-muted mb-4">

                                        Enter payment details.

                                    </p>


                                    <div class="row">

                                        <!-- DATE -->

                                        <div class="mb-4">

                                            <label class="form-label fw-semibold">

                                                <i class="bi bi-calendar-event text-primary me-2"></i>

                                                Date of Payment

                                            </label>

                                            <input type="date"
                                                   class="form-control rounded-3"
                                                   id="purchase_order_date_of_payment"
                                                   name="purchase_order_date_of_payment"
                                                   value="{{ date('Y-m-d') }}">
											<span id="purchase_order_date_of_paymentError"></span>
                                        </div>


                                    </div>
									
                                    <!-- BANK -->

                                    <div class="mb-4">

                                        <label class="form-label fw-semibold">

                                            <i class="bi bi-bank text-success me-2"></i>

                                            Bank

                                        </label>

                                        <textarea
                                            class="form-control rounded-3"
                                            rows="4"
                                            id="purchase_order_bank"
                                            name="purchase_order_bank"
                                            placeholder="Bank&#10;Branch&#10;Account Name&#10;Account Number"></textarea>
										<span id="purchase_order_bankError"></span>
                                    </div>

                                    <div class="row">

                                        <!-- REFERENCE -->

                                        <div class="mb-4">

                                            <label class="form-label fw-semibold">

                                                <i class="bi bi-upc-scan text-warning me-2"></i>

                                                Reference Number

                                            </label>

											<textarea class="form-control rounded-3"
											id="purchase_order_reference_no"
											name="purchase_order_reference_no"
											style="height: 50px;"
											placeholder="Transaction / Deposit Slip No."
											title="Please press Enter to create new lines for proper text formatting and improved readability."></textarea>
											<span id="purchase_order_reference_noError"></span>

                                        </div>

                                    </div>									

                                    <!-- AMOUNT -->

                                    <div class="mb-2">

                                        <label class="form-label fw-semibold">

                                            <i class="bi bi-cash-stack text-success me-2"></i>

                                            Payment Amount

                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">

                                                ₱

                                            </span>

                                            <input type="number"
                                                   class="form-control"
                                                   id="purchase_order_payment_amount"
                                                   name="purchase_order_payment_amount"
                                                   placeholder="0.00"
                                                   step=".01">
											<span id="purchase_order_payment_amountError"></span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->

                        <div class="col-lg-6">

                            <div class="card border-0 shadow-sm rounded-4 h-100">

                                <div class="card-body p-4">

                                    <h4 class="fw-bold text-primary">

                                        <i class="bi bi-image me-2"></i>

                                        Receipt Preview

                                    </h4>

                                    <p class="text-muted mb-4">

                                        Upload payment receipt.

                                    </p>

                                    <div class="text-center mb-4">

                                        <img id="payment_preview"
                                             src=""
                                             class="img-fluid rounded-4 border shadow-sm"
                                             style="display:none;max-height:280px;">

                                        <div id="image_payment_div"></div>

                                    </div>

                                    <label class="form-label fw-semibold">

                                        Upload Receipt

                                    </label>

                                    <input type="file"
                                           class="form-control rounded-3"
                                           id="payment_image_reference"
                                           name="payment_image_reference"
                                           accept="image/*">

                                    <small class="text-muted">

                                        JPG / PNG • Maximum 10MB

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                    <input type="hidden"
                           id="purchase_order_id_payment"
                           name="purchase_order_id_payment"
                           value="{{ $PurchaseOrderID }}">

                    <input type="hidden"
                           id="purchase_order_payment_details_id"
                           name="purchase_order_payment_details_id">

                </form>

            </div>

            <!-- FOOTER -->

            <div class="modal-footer bg-white border-0 px-4 py-3">

                <div class="me-auto"
                     id="loading_data_update_product"
                     style="display:none;">

                    <div class="spinner-border text-success"></div>

                </div>

                <button type="reset"
                        form="AddPayment"
                        id="clear-payment"
                        class="btn btn-light border px-4">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Reset

                </button>

                <button type="submit"
                        form="AddPayment"
                        id="save-payment"
                        value="0"
                        class="btn btn-success px-4">

                    <i class="bi bi-save-fill me-1"></i>

                    Save Payment

                </button>

            </div>

        </div>

    </div>

</div>	
	

<!-- PAYMENT DELETE MODAL -->
<div class="modal fade"
     id="PurchaseOrderPaymentDeleteModal"
     tabindex="-1"
     aria-labelledby="PurchaseOrderPaymentDeleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-body p-4">

                <!-- WARNING ICON -->
                <div class="text-center">

                    <div class="bg-warning bg-opacity-10 rounded-circle
                                d-inline-flex align-items-center
                                justify-content-center mb-3"
                         style="width:80px;height:80px;">

                        <i class="bi bi-exclamation-triangle-fill
                                  text-warning fs-1"></i>

                    </div>

                    <!-- TITLE -->
                    <h5 class="fw-bold mb-2"
                        id="PurchaseOrderPaymentDeleteModalLabel">

                        Delete Payment?

                    </h5>

                    <!-- MESSAGE -->
                    <div class="text-muted mb-4">

                        Are you sure you want to delete this payment?
                        <br>
                        <small>This action cannot be undone.</small>

                    </div>

                </div>


                <!-- PAYMENT INFORMATION -->
                <div class="row g-3 mb-4">

                    <!-- PAYMENT DETAILS -->
                    <div class="col-md-5">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="fw-semibold mb-3">
                                <i class="bi bi-receipt me-1"></i>
                                Payment Details
                            </div>

                            <div class="mb-3">
                                <div class="small text-muted">
                                    Bank
                                </div>

                                <div class="fw-semibold"
                                     id="delete_purchase_order_bank">
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="small text-muted">
                                    Date of Payment
                                </div>

                                <div class="fw-semibold"
                                     id="delete_purchase_order_date_of_payment">
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="small text-muted">
                                    Reference No.
                                </div>

                                <div class="fw-semibold"
                                     id="delete_purchase_order_reference_no">
                                </div>
                            </div>


                            <div>
                                <div class="small text-muted">
                                    Amount
                                </div>

                                <div class="fw-bold fs-5"
                                     id="delete_purchase_order_payment_amount">
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- PAYMENT IMAGE -->
                    <div class="col-md-7">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="fw-semibold mb-3">
                                <i class="bi bi-image me-1"></i>
                                Payment Proof
                            </div>

                            <div class="delete_img-holder
                                        text-center
                                        d-flex
                                        align-items-center
                                        justify-content-center"
                                 style="min-height:180px;">

                            </div>

                        </div>

                    </div>

                </div>


                <!-- BUTTONS -->
                <div class="d-flex justify-content-center gap-2">

                    <button type="button"
                            class="btn btn-danger px-4"
                            id="deletePurchaseOrderPaymentConfirmed"
                            value="">

                        <i class="bi bi-trash3 me-1"></i>
                        Delete Payment

                    </button>


                    <button type="button"
                            class="btn btn-secondary px-4"
                            data-bs-dismiss="modal">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


	
	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PurchaseOrderViewPaymentReferenceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Information</h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">
				
				Bank: <span id="view_purchase_order_bank"></span><br>
				Date Of Payment: <span id="view_purchase_order_date_of_payment"></span><br>	
				Reference No.: <span id="view_purchase_order_reference_no"></span><br>
				Amount: <span id="view_purchase_order_payment_amount"></span><br>
				
				</div>
				</div>
				<div class="col-sm-8">
					<div class="view_img-holder" align="center"></div>
				</div>
				</div>
				
                <div class="modal-footer footer_modal_bg">
				
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Close</button>
                  
                </div>
            </div>
        </div>
    </div>	

 	<!-- View Payment Gallery Modal-->
    <div class="modal fade" id="ViewPaymentGalery" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="height: 900px;">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Information</h5>
 					<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
                </div>
				
				<br>
				
					<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" align="center">
					
					
					<div class="carousel-indicators">
				
					</div>
					<div class="carousel-inner" style="height: 700px;">
					 
					</div>
					
					
					
					<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
					  <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: magenta; border-radius: 15px;"></span>
					  <span class="visually-hidden" >Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
					  <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: magenta; border-radius: 15px;"></span>
					  <span class="visually-hidden">Next</span>
					</button>

					</div>
					
				<br>
				
            </div>
        </div>
    </div>	