<div class="so-order-editor-content"
     data-sales-order-id="{{ $SalesOrderID }}"
     data-client-id="{{ $sales_order_data[0]['sales_order_client_idx'] }}"
     data-receivable-id="{{ $receivables_details['receivable_id'] ?? '' }}"
     data-category-id="{{ $category_idx ?? 0 }}">
<div class="so-editor-main">

    <section class="section">
		<div class="card">

            <div class="card-body">

				<h5 class="card-title" align="center">Sales Order Information</h5>
				<div class="row mb-2">
					<div class="col-sm-6">
					<h6>S.O. No : <span class="badge" id='so-editor-control_no' style="background-color: orange !important; color:#000 !important; font-weight:bold;">{{ $sales_order_data[0]['sales_order_control_number'] }}</span></h6>
					</div>
					<div class="col-sm-6">
						<div class="d-flex justify-content-end">
							<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -35px; position: absolute;">
								@if(!request()->boolean('embedded') && !request()->boolean('modal'))
									<a class="btn btn-secondary new_item bi bi-chevron-double-left form_button_icon" href="{{ route('salesorder') }}" title="Back to Sales Order List">
										<span>Back</span>
									</a>
								@endif
								<button type="button" class="so-btn-secondary so-editor-print" id="so-editor-PrintSalesOrder"><i class="bi bi-printer" aria-hidden="true"></i> Print order</button>
								<!--<button type="button" class="btn btn-success new_item bi bi-printer" onclick="#"></button>-->

							</div>
						</div>

					</div>
				</div>

				<hr>
				<div class="row mb-2">
				<?php
							$sales_order_invoice = $sales_order_data[0]->sales_order_invoice;

							if($sales_order_data[0]['sales_order_quotation']==1){
								$sales_order_quotation_check = 'checked="checked"';
								$sales_order_disabled_tab = 'disabled="disabled"';
							}else{
								$sales_order_quotation_check = "";
								$sales_order_disabled_tab = '';
							}

							if($sales_order_data[0]['sales_order_quotation_hide_volume']==1){
								$sales_order_quotation_hide_volume_check = 'checked="checked"';
								//$sales_order_disabled_tab = 'disabled="disabled"';
							}else{
								$sales_order_quotation_hide_volume_check = "";
								//$sales_order_disabled_tab = '';
							}

				?>
			@include('pages.sales_order_form_update_modal')

			<div class="col-sm-12">
					 <!-- Default Tabs -->
              <ul class="nav nav-tabs" id="so-editor-myTab" role="tablist">
                <!-- nav-link active -->
				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='sales_order_information') { echo 'active'; } ?>" id="so-editor-sales_order_information-tab" data-bs-toggle="tab" data-bs-target="#so-editor-sales_order_information" type="button" role="tab" aria-controls="so-editor-sales_order_information" aria-selected="{{ $tab === 'sales_order_information' ? 'true' : 'false' }}" title='View Sales Order Information'>Sales Order Information</button>
                </li>

				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='product') { echo 'active'; } ?>" id="so-editor-product-tab" data-bs-toggle="tab" data-bs-target="#so-editor-product" type="button" role="tab" aria-controls="so-editor-product" aria-selected="{{ $tab === 'product' ? 'true' : 'false' }}" title='Product List, Create, Update and Delete Product'>Product</button>
                </li>

				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='delivery') { echo 'active'; } ?>" id="so-editor-delivery-tab" data-bs-toggle="tab" data-bs-target="#so-editor-delivery" type="button" role="tab" aria-controls="so-editor-delivery" aria-selected="{{ $tab === 'delivery' ? 'true' : 'false' }}" title='Product Delivery List, Create, Update and Delete Delivery' <?=$sales_order_disabled_tab;?>>Delivery</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='payment') { echo 'active'; } ?>" id="so-editor-payment-tab" data-bs-toggle="tab" data-bs-target="#so-editor-payment" type="button" role="tab" aria-controls="so-editor-payment" aria-selected="{{ $tab === 'payment' ? 'true' : 'false' }}" title='Payment List, Create, Update and Delete Payment' <?=$sales_order_disabled_tab;?>>Payment</button>
                </li>

				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='receivable') { echo 'active'; } ?>" id="so-editor-receivable-tab" data-bs-toggle="tab" data-bs-target="#so-editor-receivable" type="button" role="tab" aria-controls="so-editor-receivable" aria-selected="{{ $tab === 'receivable' ? 'true' : 'false' }}" title='Update Receivable Information' <?=$sales_order_disabled_tab;?>>Receivable Details</button>
                </li>

              </ul>

              <div class="tab-content pt-2" id="so-editor-myTabContent">

			   	<div class="tab-pane fade  <?php if($tab=='sales_order_information') { echo ' show active'; } ?>" id="so-editor-sales_order_information" role="tabpanel" aria-labelledby="so-editor-sales_order_information-tab">
				<div class="d-flex justify-content-end">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success bi bi-pencil-fill so-entry-action" data-bs-toggle="modal" data-bs-target="#so-editor-UpdateSalesOrderModal" id="so-editor-UpdateSalesOrderBTN">Edit information</button>

					</div>

					</div>
					<!--Information Here-->
					<ul class="list-group list-group-flush">
							<li class="list-group-item"><b>Date:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_date'] }}</span></li>
							<li class="list-group-item"><b>Branch:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['branch_code'] }} - {{ $sales_order_data[0]['branch_name'] }}</span></li>
							<li class="list-group-item"><b>Sold To:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['client_name'] }}</span></li>
							<li class="list-group-item"><b>Payment Type:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_payment_type'] }}</span></li>
							<li class="list-group-item"><b>With Sales Invoice:</b><span style="font-weight: normal;">&nbsp;<?php if($sales_order_invoice=='1'){ echo "YES";} else{ echo "NO";} ?></span></li>
							<li class="list-group-item"><b>Net Value:</b><span style="font-weight: normal;"></span>&nbsp;&nbsp;{{ $sales_order_data[0]['sales_order_net_percentage'] }}</li>
							<li class="list-group-item"><b>Withholding Tax:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_withholding_tax'] }}</span></li>
							<li class="list-group-item"><b>D.R Number:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_dr_number'] }}</span></li>
							<li class="list-group-item"><b>P.O Number:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_po_number'] }}</span></li>
							<li class="list-group-item"><b>Sales Invoice:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_or_number'] }}</span></li>
							<li class="list-group-item"><b>Charge Invoice:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_charge_invoice'] }}</span></li>
							<li class="list-group-item"><b>Collection Receipt:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_collection_receipt'] }}</span></li>
							<li class="list-group-item"><b>Payment Term:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_payment_term'] }}</span></li>
							<li class="list-group-item"><b>Delivered To:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_delivered_to'] }}</span></li>
							<li class="list-group-item"><b>Delivered To Address:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}</span></li>
							<li class="list-group-item"><b>Delivery Method:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_delivery_method'] }}</span></li>
							<li class="list-group-item"><b>Hauler:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_hauler'] }}</span></li>
							<li class="list-group-item"><b>Required Date:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_required_date'] }}</span></li>
							<li class="list-group-item"><b>Instructions:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_instructions'] }}</span></li>
							<li class="list-group-item"><b>Notes:</b><span style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_note'] }}</span></li>
					</ul>


                </div>

			   <div class="tab-pane fade  <?php if($tab=='product') { echo ' show active'; } ?>" id="so-editor-product" role="tabpanel" aria-labelledby="so-editor-product-tab">
				<div class="d-flex justify-content-end">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success bi bi-plus-lg so-entry-action" data-bs-toggle="modal" data-bs-target="#so-editor-AddProductModal" id="so-editor-AddSalesOrderProductBTN">Add product</button>

					</div>
					</div>

						<table class="table table-striped">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;" class="action_column_class">Action</th>
							<th style="text-align:center !important;">Item #</th>
							<th style="text-align:center !important;">Description</th>
							<th style="text-align:center !important;">Price</th>
							<th style="text-align:center !important;">Quantity</th>
							<th style="text-align:center !important;">Amount</th>
						</tr>
						</thead>
							<tbody id="so-editor-table_sales_order_product_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>

                <div class="tab-pane fade  <?php if($tab=='delivery') { echo ' show active'; } ?>" id="so-editor-delivery" role="tabpanel" aria-labelledby="so-editor-delivery-tab">
				<div class="d-flex justify-content-end">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success bi bi-truck so-entry-action" data-bs-toggle="modal" data-bs-target="#so-editor-AddProductDeliveryModal" id="so-editor-addProductDeliveredBTN" title="Record delivered items">Record delivery</button>
						<button type="button" class="so-btn-secondary so-editor-print" id="so-editor-PrintSalesOrderDeliveyStatus"><i class="bi bi-printer" aria-hidden="true"></i> Print delivery</button>
					</div>
					</div>

						<table class="table table-striped">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;" class="">Action</th>
							<th style="text-align:center !important;">Item #</th>
							<th style="text-align:center !important;">Delivery Date</th>
							<th style="text-align:center !important;">Product</th>
							<th style="text-align:center !important;">Quantity</th>
						</tr>
						</thead>
							<tbody id="so-editor-product_list_delivery_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>

                <div class="tab-pane fade <?php if($tab=='payment') { echo 'show active'; } ?>" id="so-editor-payment" role="tabpanel" aria-labelledby="so-editor-payment-tab">
				<div class="d-flex justify-content-end">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success bi bi-credit-card so-entry-action" data-bs-toggle="modal" data-bs-target="#so-editor-AddPaymentModal" id="so-editor-AddPaymentOrderProductBTN">Record payment</button>
						<button type="button" class="btn bi bi-images so-entry-action so-btn-secondary" data-bs-toggle="modal" data-bs-target="#so-editor-ViewPaymentGalery">Receipts</button>
					</div>
					</div>

						<table class="table table-striped">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;">#</th>
							<th style="text-align:center !important;">Action</th>
							<th style="text-align:center !important;">Date</th>
							<th style="text-align:center !important;">Time</th>
							<th style="text-align:center !important;">Mode of Payment</th>
							<th style="text-align:center !important;">Reference No.</th>
							<th style="text-align:center !important;">Remarks</th>
							<th style="text-align:center !important;">Amount</th>
						</tr>
						</thead>
							<tbody id="so-editor-update_table_payment_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>

				<div class="tab-pane fade <?php if($tab=='receivable') { echo 'show active'; } ?>" id="so-editor-receivable" role="tabpanel" aria-labelledby="so-editor-receivable-tab">

				<div class="d-flex justify-content-end">

							<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">

								<button type="button" class="so-btn-secondary so-editor-print" id="so-editor-PrintSOA"><i class="bi bi-printer" aria-hidden="true"></i> Print SOA</button>
								<button type="button" class="so-btn-secondary so-editor-print" id="so-editor-PrintReceivable"><i class="bi bi-printer" aria-hidden="true"></i> Print receivable</button>
								<!--<button type="button" class="btn btn-success new_item bi bi-printer" onclick="#"></button>-->

							</div>

						</div>
					<div class="row mb-2">

					<div class="col-sm-12">
					<h6>
						<div class="row mb-2">
							<label class="col-sm-4 col-form-label">Control Number :</label>
							<label class="col-sm-8 col-form-label" id='so-editor-receivable_control_number_info'>{{ @$receivables_details['control_number'] }}</label>



						</div>
					</h6>

					<form class="g-2 needs-validation pt-1" id="so-editor-ReceivableformEditFromSalesOrder">

						<div class="row mb-2">
						  <label for="so-editor-receivable_billing_date_SO" class="col-sm-4 col-form-label">Billing Date : </label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="receivable_billing_date_SO" id="so-editor-receivable_billing_date_SO" value="" required>
							<span class="valid-feedback" id="so-editor-receivable_billing_date_SO_Error"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="so-editor-receivable_payment_term_SO" class="col-sm-4 col-form-label">Payment Term : </label>
						  <div class="col-sm-8">
							<input type="text" class="form-control " name="receivable_payment_term_SO" id="so-editor-receivable_payment_term_SO" value="">
							<span class="valid-feedback" id="so-editor-receivable_payment_term_SO_Error"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="so-editor-receivable_description_SO" class="col-sm-4 col-form-label">Description : </label>
						  <div class="col-sm-8">
							<textarea class="form-control" id="so-editor-receivable_description_SO" style="height: 50px;" required></textarea>
							<span class="valid-feedback" id="so-editor-receivable_description_SO_Error"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">Amount : </label>
						  <label class="col-sm-8 col-form-label" id="so-editor-receivable_amount_info"></label>
						</div>
					<div class="card-footer">
												<div class="row mb-3">
												<div class="col-sm-6" align="">
												<div id="so-editor-update_loading_data_receivable" style="display:none;">
													<div class="spinner-border text-success" role="status">
														<span class="visually-hidden">Loading...</span>
													</div>
												</div>
												</div>
												<div class="col-sm-6" align="right">

												<button type="submit" class="so-btn-primary" id="so-editor-SO-update-receivables"><i class="bi bi-check2" aria-hidden="true"></i> Save receivable</button>
												</div>
												</div>
					</div>


					</form><!-- End Multi Columns Form -->
					</div>


					</div>
                </div>

			  </div>

					</div>
				</div>
			</div>
		</div>

	@include('pages.sales_order_v2.editor.forms.product', ['editing' => false])
@include('pages.sales_order_v2.editor.forms.product', ['editing' => true])

<!-- Bill Delete Modal-->
    <div class="modal fade" id="so-editor-SalesOrderComponentDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>


                <div class="modal-body warning_modal_bg">
				Are you sure you want to Delete This Product?<br>
				</div>
				<div align="left"style="margin: 10px;">

				Product: <span id="so-editor-bill_delete_product_name"></span><br>
				Quantity: <span id="so-editor-bill_delete_order_quantity"></span><br>

				Total Amount: <span id="so-editor-bill_delete_order_total_amount"></span><br>

				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="so-editor-deleteSalesOrderComponentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>

                </div>
            </div>
        </div>
    </div>

	<!--Data List for Product-->
	<datalist id="so-editor-product_list">
			<span >	</span>
	</datalist>
    </section>


	@include('pages.sales_order_v2.editor.forms.payment')

<!-- View Payment Gallery Modal-->
    <div class="modal fade" id="so-editor-ViewPaymentGalery" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="height: 900px;">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title">Payment Information</h5>
 					<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
                </div>

				<br>

					<div id="so-editor-carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" align="center">

					<div class="carousel-indicators">

					</div>

					<div class="carousel-inner" style="height: 700px;">

					</div>

					<button class="carousel-control-prev" type="button" data-bs-target="#so-editor-carouselExampleCaptions" data-bs-slide="prev">
					  <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: magenta; border-radius: 15px;"></span>
					  <span class="visually-hidden" >Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#so-editor-carouselExampleCaptions" data-bs-slide="next">
					  <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: magenta; border-radius: 15px;"></span>
					  <span class="visually-hidden">Next</span>
					</button>

					</div>

				<br>

            </div>
        </div>
    </div>

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="so-editor-SalesOrderPaymentDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg">
				Are you sure you want to Delete This Payment?<br>
				</div>
				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">
					Mode of Payment: <span id="so-editor-delete_receivable_mode_of_payment"></span><br>
					Date: <span id="so-editor-delete_receivable_date_of_payment"></span><br>
					Reference No.: <span id="so-editor-delete_receivable_reference"></span><br>
					Amount: <span id="so-editor-delete_receivable_payment_amount"></span><br>
				</div>
				</div>
				<div class="col-sm-8">
					<div class="delete_img-holder" align="center"></div>
				</div>
				</div>

                <div class="modal-footer footer_modal_bg">

					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="so-editor-deleteSalesOrderPaymentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>

                </div>
            </div>
        </div>
    </div>

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="so-editor-ViewOrderViewPaymentReferenceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title">Payment Information</h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">

				Date: <span id="so-editor-view_receivable_date_of_payment"></span><br>
				Time: <span id="so-editor-view_receivable_time_of_payment"></span><br>
				Mode of Payment: <span id="so-editor-view_receivable_mode_of_payment"></span><br>
				Reference No.: <span id="so-editor-view_receivable_reference"></span><br>
				Remarks: <span id="so-editor-view_receivable_payment_remarks"></span><br>
				Amount: <span id="so-editor-view_receivable_payment_amount"></span><br>

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


	<!--Delivery Form-->


    </section>

	@include('pages.sales_order_v2.editor.forms.delivery')

<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="so-editor-SalesOrderProductDeliveryDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg">
				Are you sure you want to Delete This Product Delivery?<br>
				</div>
				<div align="left"style="margin: 10px;">

				Delivery Date: <span id="so-editor-delete_delivery_sales_order_delivery_date"></span><br>

				Product: <span id="so-editor-delete_delivery_delete_product_name"></span><br>
				Quantity: <span id="so-editor-delete_delivery_delete_sales_order_delivery_quantity"></span><br>

				Delivery Reference: <span id="so-editor-delete_delivery_sales_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="so-editor-delete_delivery_sales_order_delivery_hauler_details"></span><br>

				Remarks: <span id="so-editor-delete_delivery_sales_order_delivery_remarks"></span><br>

				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="so-editor-deleteSalesOrderProdcutDeliveryConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>

                </div>
            </div>
        </div>
    </div>

		<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="so-editor-SalesOrderProductDeliveryViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>


                <div class="modal-body warning_modal_bg">
				Delivery Information<br>
				</div>
				<div align="left"style="margin: 10px;">

				Delivery Date: <span id="so-editor-view_delivery_sales_order_delivery_date"></span><br>

				Product: <span id="so-editor-view_delivery_delete_product_name"></span><br>
				Quantity: <span id="so-editor-view_delivery_delete_sales_order_delivery_quantity"></span><br>

				Delivery Reference: <span id="so-editor-view_delivery_sales_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="so-editor-view_delivery_sales_order_delivery_hauler_details"></span><br>

				Remarks: <span id="so-editor-view_delivery_sales_order_delivery_remarks"></span><br>

				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
