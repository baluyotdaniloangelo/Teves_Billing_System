<!-- CREATE SALES ORDER MODAL -->
<div class="modal fade"
     id="UpdatePurchaseOrderModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <!-- MAIN ICON -->
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width:60px;height:60px;">

                        <i class="bi bi-receipt-cutoff fs-3"></i>

                    </div>

                    <!-- TITLE -->
                    <div>

                        <h4 class="modal-title fw-bold mb-0">
                            Update Purchase Order
                        </h4>

                        <small class="opacity-75">
                            Manage Purchase Order transaction
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
                  id="PurchaseOrderformUpdate"
                  novalidate>

                <!-- BODY -->
                <div class="modal-body p-4">

					<ul class="nav nav-tabs mb-3" id="PurchaseOrderTabs" role="tablist">

						<li class="nav-item">
							<button class="nav-link active"
									data-bs-toggle="tab"
									data-bs-target="#general-tab" type="button" role="tab" >
								<i class="bi bi-info-circle me-1"></i>
								General
							</button>
						</li>

						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#billing-tab" type="button" role="tab" >
								<i class="bi bi-receipt me-1"></i>
								Billing
							</button>
						</li>

						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#delivery-tab" type="button" role="tab" >
								<i class="bi bi-truck me-1"></i>
								Delivery
							</button>
						</li>

						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#notes-tab" type="button" role="tab" >
								<i class="bi bi-journal-text me-1"></i>
								Notes
							</button>
						</li>
						<!--
						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#additional-tab" type="button" role="tab" >
								<i class="bi bi-sliders me-1"></i>
								Options
							</button>
						</li>
						-->
					</ul>                   

                
					<div class="tab-content">

						<div class="tab-pane fade show active"
							 id="general-tab">

							<!-- General Information -->
							<!-- ===========================================================
							GENERAL INFORMATION
							=========================================================== -->
							<div class="card border-0 shadow-sm rounded-4 mb-3">

								<div class="card-header bg-white border-0 py-3">

									<h5 class="fw-bold mb-0">
										<i class="bi bi-info-circle-fill text-primary me-2"></i>
										General Information
									</h5>

									<small class="text-muted">
										Basic Purchase Order information.
									</small>

								</div>

								<div class="card-body">

									<!-- DATE + BRANCH -->
									<div class="row">
										
										<!-- SALES ORDER TYPE -->
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">

												<i class="bi bi-tags-fill text-success me-2"></i>

												Purchase Order Type

											</label>

											<select class="form-select rounded-3"
													required
													name="purchase_order_type"
													id="purchase_order_type">

												
												<option value="">Please select Type</option>
												@foreach ($product_category_data as $product_category_data_cols)

													

													<option value="{{$product_category_data_cols->category_id}}">

														{{$product_category_data_cols->category_name}}

													</option>

												@endforeach

											</select>
											
											<span class="text-danger small"
											  id="purchase_order_typeError"></span>
											  
										</div>
										
										<!-- DATE -->
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">

												<i class="bi bi-calendar-event text-primary me-2"></i>

												Date

											</label>

											<input type="date"
												   class="form-control rounded-3"
												   id="purchase_order_date"
												   name="purchase_order_date"
												   value=""
												   required
												   max="9999-12-31">

											<span class="text-danger small"
											  id="purchase_order_date_error"></span>
											  
										</div>

										
										<!-- BRANCH -->
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">

												<i class="bi bi-building text-success me-2"></i>

												Branch

											</label>

											<select class="form-select rounded-3"
													required
													name="company_header"
													id="company_header"
													onchange="UpdateBranch()">
													
												@foreach ($teves_branch as $teves_branch_cols)
													<option value="{{$teves_branch_cols->branch_id}}">
														{{$teves_branch_cols->branch_code}}
													</option>
												@endforeach

											</select>

										</div>

									</div>

									<!-- SOLD TO -->
									<div class="mb-3">

										<label class="form-label fw-semibold">
											<i class="bi bi-person-fill text-warning me-2"></i>
											Sold To
										</label>

										<input class="form-control rounded-3"
											   list="supplier_name_list"
											   name="supplier_name"
											   id="supplier_idx"
											   onChange="SupplierInfo()"
											   placeholder="Supplier's Name"
											   autocomplete="off"
											   required>
										
										<input type="hidden"
										   id="supplier_idx"
										   name="supplier_idx">
	   
										<datalist id="supplier_name_list">
											@foreach ($supplier_data as $supplier_data_cols)
											  <option label="{{$supplier_data_cols->supplier_name}}"
											  data-id="{{$supplier_data_cols->supplier_id}}"
											  value="{{$supplier_data_cols->supplier_name}}">
											@endforeach
										</datalist>

										<span class="text-danger small"
											  id="supplier_idxError"></span>

									</div>

									<!-- PAYMENT + SALES INVOICE -->
									<div class="row">


										

									</div>

									<!-- NET + With Sales Invoice + Less Value -->
									<div class="row">

										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-cash-stack text-success me-2"></i>
												Net Value
											</label>

											<input type="number"
												   class="form-control rounded-3 bg-light"
												   id="purchase_order_net_percentage"
												   name="purchase_order_net_percentage"
												   >

										</div>
										
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-receipt text-primary me-2"></i>
												With Sales Invoice
											</label>

											<select class="form-select rounded-3"
													id="purchase_order_invoice"
													name="purchase_order_invoice"
													onchange="check_withholding_tax()"
													required>

												<option value="1" selected>Yes</option>
												<option value="0">No</option>

											</select>

										</div>
										
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-percent text-danger me-2"></i>
												Less Value
											</label>

											<input type="number"
												   class="form-control rounded-3 bg-light"
												   id="purchase_order_less_percentage"
												   name="purchase_order_less_percentage"
											>

										</div>
										
										

									</div>

								</div>

							</div>							

						</div>

						<div class="tab-pane fade"
							 id="billing-tab">

							<!-- Billing -->
						<!-- ===========================================================
						BILLING INFORMATION
						=========================================================== -->
						<div class="card border-0 shadow-sm rounded-4 mb-3">

							<div class="card-header bg-white border-0 py-3">

								<h5 class="fw-bold mb-0">

									<i class="bi bi-receipt-cutoff text-primary me-2"></i>

									Billing Information

								</h5>

								<small class="text-muted">

									Sales order billing and invoice references.

								</small>

							</div>

							<div class="card-body">

								<!-- DR NUMBER -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-truck text-primary me-2"></i>

										Sales Order #

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="purchase_order_sales_order_number"
										   name="purchase_order_sales_order_number"
										   placeholder="Enter Sales Order #">

								</div>

								<!-- PO NUMBER -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-file-earmark-text text-success me-2"></i>

										P.O. Number

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="purchase_order_collection_receipt_no"
										   name="purchase_order_collection_receipt_no"
										   placeholder="Enter Purchase Order Number">

								</div>

								<!-- SALES INVOICE -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-receipt text-danger me-2"></i>

										Sales Invoice

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="purchase_order_official_receipt_no"
										   name="purchase_order_official_receipt_no"
										   placeholder="Enter Sales Invoice Number">

								</div>

								<!-- Delivery Receipt # -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-journal-check text-warning me-2"></i>

										Delivery Receipt #

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="purchase_order_delivery_receipt_no"
										   name="purchase_order_delivery_receipt_no"
										   placeholder="Enter Charge Delivery Receipt #">

								</div>


							</div>

						</div>
						</div>

						<div class="tab-pane fade"
							 id="delivery-tab">

							<!-- Delivery -->
						<!-- ===========================================================
						DELIVERY INFORMATION
						=========================================================== -->
						<div class="card border-0 shadow-sm rounded-4 mb-3">

							<div class="card-header bg-white border-0 py-3">

								<h5 class="fw-bold mb-0">

									<i class="bi bi-truck text-primary me-2"></i>

									Delivery Information

								</h5>

								<small class="text-muted">

									Delivery destination and logistics details.

								</small>

							</div>

							<div class="card-body">

								<!-- DELIVERY METHOD -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-truck-flatbed text-primary me-2"></i>

										Delivery Method

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="purchase_order_delivery_method"
										   name="purchase_order_delivery_method"
										   placeholder="e.g. Company Truck, Pick-up">

								</div>

								<!-- Loading Terminal -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-truck-front-fill text-warning me-2"></i>

										Loading Terminal

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="purchase_loading_terminal"
										   name="purchase_loading_terminal" 
										   list="purchase_loading_terminal_list" 
										   placeholder="Loading Terminal">
										   
									<datalist id="purchase_loading_terminal_list">
											@foreach ($purchase_data_suggestion as $purchase_loading_terminal_cols)
												<option value="{{$purchase_loading_terminal_cols->purchase_loading_terminal}}">
											@endforeach
									</datalist>

								</div>
								
								<!-- HAULER -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-truck-front-fill text-warning me-2"></i>

										Hauler

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="hauler_operator"
										   name="hauler_operator"
										   placeholder="Enter Hauler">

								</div>								
								

								<!-- REQUIRED DATE -->
								<div class="mb-2">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-calendar-check-fill text-info me-2"></i>

										Driver's Name

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="lorry_driver"
										   name="lorry_driver"
										   list="lorry_driver_list" 
										   placeholder="Driver's Name">
									
									<datalist id="lorry_driver_list">
												@foreach ($purchase_data_suggestion as $lorry_driver_cols)
													<option value="{{$lorry_driver_cols->lorry_driver}}">
												@endforeach
									</datalist>
									
								</div>
								
								<!-- REQUIRED DATE -->
								<div class="mb-2">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-calendar-check-fill text-info me-2"></i>

										Plate Number

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="plate_number"
										   name="plate_number"
										   placeholder="Plate Number">
									
									
									
								</div>
								
								<div class="mb-3">

										<label class="form-label fw-semibold mb-1">

											<i class="bi bi-list-check text-primary me-2"></i>

											Destination

										</label>

										<textarea
											class="form-control rounded-3"
											id="purchase_destination"
											name="purchase_destination"
											rows="4"
											placeholder="Destination"
											 list="purchase_destination_list"></textarea>
										
										<datalist id="purchase_destination_list">
												@foreach ($purchase_data_suggestion as $purchase_destination_cols)
													<option value="{{$purchase_destination_cols->purchase_destination}}">
												@endforeach
										</datalist>
									</div>
									
							</div>

						</div>
						</div>

						<div class="tab-pane fade"
							 id="notes-tab">

							<!-- Notes -->
							<!-- ===========================================================
							INSTRUCTIONS & NOTES
							=========================================================== -->
							<div class="card border-0 shadow-sm rounded-4 mb-3">

								<div class="card-header bg-white border-0 py-3">

									<h5 class="fw-bold mb-0">

										<i class="bi bi-journal-text text-primary me-2"></i>

										Instructions & Notes

									</h5>

									<small class="text-muted">

										Additional remarks and special instructions for this sales order.

									</small>

								</div>

								<div class="card-body">

									<!-- INSTRUCTIONS -->
									<div class="mb-3">

										<label class="form-label fw-semibold mb-1">

											<i class="bi bi-list-check text-primary me-2"></i>

											Instructions

										</label>

										<textarea
											class="form-control rounded-3"
											id="purchase_order_instructions"
											name="purchase_order_instructions"
											rows="4"
											placeholder="Enter delivery or processing instructions..."></textarea>

									</div>

									<!-- NOTES -->
									<div class="mb-2">

										<label class="form-label fw-semibold mb-1">

											<i class="bi bi-pencil-square text-success me-2"></i>

											Notes

										</label>

										<textarea
											class="form-control rounded-3"
											id="purchase_order_note"
											name="purchase_order_note"
											rows="4"
											placeholder="Enter additional notes or remarks..."></textarea>

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

                        <div class="spinner-border spinner-border-sm text-success"
                             role="status">

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </div>

                    <!-- RESET 
                    <button type="reset"
                            class="btn btn-light btn-sm rounded-3 px-3" id="clear-sales-order">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset

                    </button> -->

                    <!-- SUBMIT -->
					<input type="hidden" id="purchase_order_id">
                    <button type="submit"
                            class="btn btn-success btn-sm rounded-3 shadow-sm px-3"
                            id="update-purchase-order">

                        <i class="bi bi-save-fill me-1"></i>
                        Update Purchase Order

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