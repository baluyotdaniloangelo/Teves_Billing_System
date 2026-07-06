<!-- CREATE SALES ORDER MODAL -->
<div class="modal fade"
     id="UpdateSalesOrderModal"
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
                            Update Sales Order
                        </h4>

                        <small class="opacity-75">
                            Create and manage Sales Order transaction
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
                  id="UpdateSalesOrderformUpdate"
                  novalidate>

                <!-- BODY -->
                <div class="modal-body p-4">

					<ul class="nav nav-tabs mb-3" id="SalesOrderTabs" role="tablist">

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

						<li class="nav-item">
							<button class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#additional-tab" type="button" role="tab" >
								<i class="bi bi-sliders me-1"></i>
								Options
							</button>
						</li>
						
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
										Basic sales order information.
									</small>

								</div>

								<div class="card-body">

									<!-- DATE + BRANCH -->
									<div class="row">
										
										<!-- SALES ORDER TYPE -->
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">

												<i class="bi bi-tags-fill text-success me-2"></i>

												Sales Order Type

											</label>

											<select class="form-select rounded-3"
													required
													name="sales_order_type"
													id="sales_order_type">

												<?php $category_idx_so = $sales_order_data[0]['category_idx']; ?>
												<option value="">Please select Type</option>
												@foreach ($product_category_data as $product_category_data_cols)

													<?php $category_id = $product_category_data_cols->category_id; ?>

													<option value="{{$product_category_data_cols->category_id}}"
														{{ $category_idx_so == $category_id ? 'selected' : '' }}>

														{{$product_category_data_cols->category_name}}

													</option>

												@endforeach

											</select>
											
											<span class="text-danger small"
											  id="sales_order_typeError"></span>
											  
										</div>
										
										<!-- DATE -->
										<div class="col-md-4 mb-3">

											<label class="form-label fw-semibold">

												<i class="bi bi-calendar-event text-primary me-2"></i>

												Date

											</label>

											<input type="date"
												   class="form-control rounded-3"
												   id="sales_order_date"
												   name="sales_order_date"
												   value="{{ $sales_order_data[0]['sales_order_date'] }}"
												   required
												   max="9999-12-31">

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

												<?php $branch_idx = $sales_order_data[0]['company_header']; ?>

												@foreach($teves_branch as $teves_branch_cols)

													<?php $branch_id = $teves_branch_cols->branch_id; ?>

													<option value="{{$teves_branch_cols->branch_id}}"
														{{ $branch_id == $branch_idx ? 'selected' : '' }}>

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
											   list="client_name"
											   name="client_name"
											   id="client_id"
											   autocomplete="off"
											   required
											   value="{{ $sales_order_data[0]['client_name'] }}"
											   onchange="ClientInfo()">

										<datalist id="client_name">

											@foreach($client_data as $client_data_cols)

												<option
													value="{{$client_data_cols->client_name}}"
													data-id="{{$client_data_cols->client_id}}"
													label="{{$client_data_cols->client_name}}">
												</option>

											@endforeach

										</datalist>

										<span class="text-danger small"
											  id="client_idxError"></span>

									</div>

									<!-- PAYMENT + SALES INVOICE -->
									<div class="row">

										<div class="col-md-6 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-credit-card text-info me-2"></i>
												Payment Type
											</label>

											<?php $sales_order_payment_type = $sales_order_data[0]->sales_order_payment_type; ?>

											<select class="form-select rounded-3"
													id="sales_order_payment_type"
													name="sales_order_payment_type"
													required>

												<option value="Receivable"
													{{ $sales_order_payment_type=='Receivable' ? 'selected' : '' }}>
													Receivable
												</option>

												<option value="PBD"
													{{ $sales_order_payment_type=='PBD' ? 'selected' : '' }}>
													Paid Before Delivery
												</option>

											</select>

										</div>

										<div class="col-md-6 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-receipt text-primary me-2"></i>
												With Sales Invoice
											</label>

											<select class="form-select rounded-3"
													id="sales_order_invoice"
													name="sales_order_invoice"
													onchange="check_withholding_tax()"
													required>

												<option value="1"
													{{ $sales_order_invoice=='1' ? 'selected' : '' }}>
													Yes
												</option>

												<option value="0"
													{{ $sales_order_invoice=='0' ? 'selected' : '' }}>
													No
												</option>

											</select>

										</div>

									</div>

									<!-- NET + WHT -->
									<div class="row">

										<div class="col-md-6 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-cash-stack text-success me-2"></i>
												Net Value
											</label>

											<input type="number"
												   class="form-control rounded-3 bg-light"
												   id="sales_order_net_percentage"
												   name="sales_order_net_percentage"
												   value="{{ $sales_order_data[0]['sales_order_net_percentage'] }}"
												   readonly>

										</div>

										<div class="col-md-6 mb-3">

											<label class="form-label fw-semibold">
												<i class="bi bi-percent text-danger me-2"></i>
												Withholding Tax
											</label>

											<input type="number"
												   class="form-control rounded-3 bg-light"
												   id="sales_order_withholding_tax"
												   name="sales_order_withholding_tax"
												   value="{{ $sales_order_data[0]['sales_order_withholding_tax'] }}"
												   readonly>

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

										D.R. Number

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="dr_number"
										   name="dr_number"
										   value="{{ $sales_order_data[0]['sales_order_dr_number'] }}"
										   placeholder="Enter Delivery Receipt Number">

								</div>

								<!-- PO NUMBER -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-file-earmark-text text-success me-2"></i>

										P.O. Number

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="sales_order_po_number"
										   name="sales_order_po_number"
										   value="{{ $sales_order_data[0]['sales_order_po_number'] }}"
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
										   id="sales_order_or_number"
										   name="sales_order_or_number"
										   value="{{ $sales_order_data[0]['sales_order_or_number'] }}"
										   placeholder="Enter Sales Invoice Number">

								</div>

								<!-- CHARGE INVOICE -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-journal-check text-warning me-2"></i>

										Charge Invoice

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="sales_order_charge_invoice"
										   name="sales_order_charge_invoice"
										   value="{{ $sales_order_data[0]['sales_order_charge_invoice'] }}"
										   placeholder="Enter Charge Invoice">

								</div>

								<!-- COLLECTION RECEIPT -->
								<div class="mb-3">

									<label class="form-label fw-semibold">

										<i class="bi bi-cash-coin text-info me-2"></i>

										Collection Receipt

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="sales_order_collection_receipt"
										   name="sales_order_collection_receipt"
										   value="{{ $sales_order_data[0]['sales_order_collection_receipt'] }}"
										   placeholder="Enter Collection Receipt">

								</div>

								<!-- PAYMENT TERM -->
								<div class="mb-2">

									<label class="form-label fw-semibold">

										<i class="bi bi-calendar-week text-secondary me-2"></i>

										Payment Term

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="payment_term"
										   name="payment_term"
										   value="{{ $sales_order_data[0]['sales_order_payment_term'] }}"
										   placeholder="Enter Payment Term">

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

								<!-- DELIVERED TO -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-person-check-fill text-success me-2"></i>

										Delivered To

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="delivered_to"
										   name="delivered_to"
										   list="sales_order_delivered_to_list"
										   value="{{ $sales_order_data[0]['sales_order_delivered_to'] }}"
										   placeholder="Enter Recipient">

									<datalist id="sales_order_delivered_to_list">

										@foreach ($sales_order_delivered_to as $sales_order_delivered_to_cols)

											<option value="{{$sales_order_delivered_to_cols->sales_order_delivered_to}}">

										@endforeach

									</datalist>

								</div>

								<!-- DELIVERY ADDRESS -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-geo-alt-fill text-danger me-2"></i>

										Delivery Address

									</label>

									<textarea class="form-control rounded-3"
											  id="delivered_to_address"
											  name="delivered_to_address"
											  rows="3"
											  placeholder="Enter Complete Delivery Address">{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}</textarea>

								</div>

								<!-- DELIVERY METHOD -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-truck-flatbed text-primary me-2"></i>

										Delivery Method

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="delivery_method"
										   name="delivery_method"
										   value="{{ $sales_order_data[0]['sales_order_delivery_method'] }}"
										   placeholder="e.g. Company Truck, Pick-up">

								</div>

								<!-- HAULER -->
								<div class="mb-3">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-truck-front-fill text-warning me-2"></i>

										Hauler

									</label>

									<input type="text"
										   class="form-control rounded-3"
										   id="hauler"
										   name="hauler"
										   value="{{ $sales_order_data[0]['sales_order_hauler'] }}"
										   placeholder="Enter Hauler">

								</div>

								<!-- REQUIRED DATE -->
								<div class="mb-2">

									<label class="form-label fw-semibold mb-1">

										<i class="bi bi-calendar-check-fill text-info me-2"></i>

										Required Date

									</label>

									<input type="date"
										   class="form-control rounded-3"
										   id="required_date"
										   name="required_date"
										   value="{{ $sales_order_data[0]['sales_order_required_date'] }}">

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
											id="instructions"
											name="instructions"
											rows="4"
											placeholder="Enter delivery or processing instructions...">{{ $sales_order_data[0]['sales_order_instructions'] }}</textarea>

									</div>

									<!-- NOTES -->
									<div class="mb-2">

										<label class="form-label fw-semibold mb-1">

											<i class="bi bi-pencil-square text-success me-2"></i>

											Notes

										</label>

										<textarea
											class="form-control rounded-3"
											id="note"
											name="note"
											rows="4"
											placeholder="Enter additional notes or remarks...">{{ $sales_order_data[0]['sales_order_note'] }}</textarea>

									</div>

								</div>

							</div>
						</div>
						
						<div class="tab-pane fade"
							 id="additional-tab">

							<!-- additional -->
							<!-- ===========================================================
							ADDITIONAL INFORMATION
							=========================================================== -->
							<div class="card border-0 shadow-sm rounded-4 mb-3">

								<div class="card-header bg-white border-0 py-3">

									<h5 class="fw-bold mb-0">

										<i class="bi bi-sliders text-primary me-2"></i>

										Additional Information

									</h5>

									<small class="text-muted">

										Configure additional sales order options.

									</small>

								</div>

								<div class="card-body">

									<!-- QUOTATION -->
									<div class="border rounded-3 p-3 mb-3">

										<div class="d-flex justify-content-between align-items-center">

											<div>

												<div class="fw-semibold">

													<i class="bi bi-file-earmark-text text-primary me-2"></i>

													Generate as Quotation

												</div>

												<small class="text-muted">

													Save this transaction as a quotation instead of a finalized sales order.

												</small>

											</div>

											<div class="form-check form-switch">

												<input
													class="form-check-input sales_order_quotation"
													type="checkbox"
													id="sales_order_quotation"
													name="sales_order_quotation"
													<?=$sales_order_quotation_check;?>>

											</div>

										</div>

									</div>

									<!-- HIDE VOLUME -->
									<div class="border rounded-3 p-3">

										<div class="d-flex justify-content-between align-items-center">

											<div>

												<div class="fw-semibold">

													<i class="bi bi-eye-slash text-danger me-2"></i>

													Hide Volume on Print

												</div>

												<small class="text-muted">

													Exclude product volume from the printed quotation or sales order.

												</small>

											</div>

											<div class="form-check form-switch">

												<input
													class="form-check-input sales_order_quotation_hide_volume"
													type="checkbox"
													id="sales_order_quotation_hide_volume"
													name="sales_order_quotation_hide_volume"
													<?=$sales_order_quotation_hide_volume_check;?>>

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
                    <button type="submit"
                            class="btn btn-success btn-sm rounded-3 shadow-sm px-3"
                            id="update-sales-order">

                        <i class="bi bi-save-fill me-1"></i>
                        Save Purchase Order

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>