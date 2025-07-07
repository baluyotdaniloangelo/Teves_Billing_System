@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	

    <section class="section">
		<div class="card">
		
            <div class="card-body">
			
				<h5 class="card-title" align="center">Update Purchase Order Information</h5>
				<div class="row mb-2">
					<div class="col-sm-6">
					<h6>Control Number : <span class="badge" id='control_no' style="background-color: yellowgreen !important; color:#000 !important; font-weight:bold;">Info</span></h6>
					</div>
					<div class="col-sm-6">
						<div class="d-flex justify-content-end" id="">
							<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -35px; position: absolute;">
								<a class="btn btn-secondary new_item bi bi-chevron-double-left form_button_icon" href="{{ route('purchaseorder_v2') }}" title="Back">  
								  <span title="Back to Sales Order List">Back</span>
								</a>
								<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintPurchaseOrder">&nbsp;Print</button>
								<!--<button type="button" class="btn btn-success new_item bi bi-printer" onclick="#"></button>-->
								
							</div>					
						</div>
						
					</div>
				</div>
				
				<hr>
			<div class="row mb-2">
			
		@include('pages.purchase_order_form_update_modal')

					<div class="col-sm-12">
					 <!-- Default Tabs -->
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                
				<li class="nav-item" role="presentation">
                  <button class="nav-link active" id="purchase_order_info-tab" data-bs-toggle="tab" data-bs-target="#purchase_order_info" type="button" role="tab" aria-controls="purchase_order_info" aria-selected="true">Purchase Order Information</button>
                </li>
				
				<li class="nav-item" role="presentation">
                  <button class="nav-link" id="purchase_order_product_list-tab" data-bs-toggle="tab" data-bs-target="#purchase_order_product_list" type="button" role="tab" aria-controls="purchase_order_product_list" aria-selected="true" onclick="LoadProduct()">Product</button>
                </li>
				
				<li class="nav-item" role="presentation">
                  <button class="nav-link" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab" aria-controls="delivery" aria-selected="true" onclick="" title='Product Withdrawal List, Create, Update and Delete Withdrawal'>Withdrawal</button>
                </li>
				
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Payment</button>
                </li>
               
              </ul>
			  
			  <div class="tab-content pt-2" id="myTabContent">
                <div class="tab-pane fade show active" id="purchase_order_info" role="tabpanel" aria-labelledby="purchase_order_info-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-pencil-fill form_button_icon" data-bs-toggle="modal" data-bs-target="#UpdatePurchaseOrderModal" id="UpdatePurchaseOrderBTN"></button>
					</div>					
					</div>

					<!--Information Here-->
					<ul class="list-group list-group-flush">
							<li class="list-group-item"><b>Date:&nbsp;</b><span style="font-weight: normal;" id="po_info_date">&nbsp;</span></li>
							<li class="list-group-item"><b>Branch:&nbsp;</b><span style="font-weight: normal;" id="po_info_branch_name">&nbsp;</span></li>
							<li class="list-group-item"><b>Supplier's Name:&nbsp;</b><span style="font-weight: normal;" id="po_info_suppliers_name">&nbsp;</span></li>
							<li class="list-group-item"><b>Net Value:&nbsp;</b><span style="font-weight: normal;" id="po_info_net_value"></span>&nbsp;</li>
							<li class="list-group-item"><b>With Sales Invoice:&nbsp;</b><span style="font-weight: normal;" id="po_info_with_sales_invoice">&nbsp;</span></li>
							<li class="list-group-item"><b>Less Value:&nbsp;</b><span style="font-weight: normal;" id="po_info_less_value">&nbsp;</span></li>
							<li class="list-group-item"><b>Sales Order #:&nbsp;</b><span style="font-weight: normal;" id="po_info_sales_order">&nbsp;</span></li>
							<li class="list-group-item"><b>Collection Receipt #:&nbsp;</b><span style="font-weight: normal;" id="po_info_collection_receipt">&nbsp;</span></li>
							<li class="list-group-item"><b>Sales Invoice #:&nbsp;</b><span style="font-weight: normal;" id="po_info_sales_invoice">&nbsp;</span></li>
							<li class="list-group-item"><b>Delivery Receipt #:&nbsp;</b><span style="font-weight: normal;" id="po_info_delivery_receipt">&nbsp;</span></li>
							<li class="list-group-item"><b>Delivery Method:&nbsp;</b><span style="font-weight: normal;" id="po_info_delivery_method">&nbsp;</span></li>
							<li class="list-group-item"><b>Loading Terminal:&nbsp;</b><span style="font-weight: normal;" id="po_info_loading_terminal">&nbsp;</span></li>
							<li class="list-group-item"><b>Hauler's Name:&nbsp;</b><span style="font-weight: normal;" id="po_info_haulers_name">&nbsp;</span></li>
							<li class="list-group-item"><b>Driver's Name:&nbsp;</b><span style="font-weight: normal;" id="po_info_drivers_name">&nbsp;</span></li>
							<li class="list-group-item"><b>Plate Number:&nbsp;</b><span style="font-weight: normal;" id="po_info_plate_number">&nbsp;</span></li>
							<li class="list-group-item"><b>Destination:&nbsp;</b><span style="font-weight: normal;" id="po_info_destination">&nbsp;</span></li>
							<li class="list-group-item"><b>Instructions:&nbsp;</b><span style="font-weight: normal;" id="po_info_instructions">&nbsp;</span></li>
							<li class="list-group-item"><b>Notes:&nbsp;</b><span style="font-weight: normal;" id="po_info_notes">&nbsp;</span></li>
					</ul>
						
                </div>
			  
                <div class="tab-pane fade" id="purchase_order_product_list" role="tabpanel" aria-labelledby="purchase_order_product_list-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductModal" id="AddPurchaseOrderProductBTN" onclick="LoadSuppliersPriceList()"></button>
					</div>					
					</div>

						<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;">#</th>
							<th style="text-align:center !important;" class="action_column_class">Action</th>
							<th style="text-align:center !important;">Description</th>
							<th style="text-align:center !important;">Price</th>
							<th style="text-align:center !important;">Quantity</th>
							<th style="text-align:center !important;">Amount</th>	
						</tr>
						</thead>
							<tbody id="table_purchase_order_product_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>
				
               <div class="tab-pane fade" id="delivery" role="tabpanel" aria-labelledby="delivery-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductDeliveryModal" id="" onclick="ResetDeliveryForm()" title="Add Sales Order Delivery Items"></button>
						<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintPurchaseOrderDeliveyStatus" title="Print Purchase Order Delivered Item / Status"></button>
					</div>											
					</div>
					
						<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;" class="">Action</th>
							<th style="text-align:center !important;">Item #</th>
							<th style="text-align:center !important;">Date</th>
							<th style="text-align:center !important;">Product</th>
							<th style="text-align:center !important;">Quantity</th>
							<th style="text-align:center !important;">Price</th>
							<th style="text-align:center !important;">Amount</th>
						</tr>
						</thead>
							<tbody id="product_list_delivery_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>				
				
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-warning new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#AddPaymentModal" id="AddPaymentOrderProductBTN" onclick="ResetPaymentForm()"></button>
						<button type="button" class="btn new_item bi bi-images" data-bs-toggle="modal" data-bs-target="#ViewPaymentGalery" style="background-color: magenta;"></button>
					</div>					
					</div>

						<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;">#</th>
							<th style="text-align:center !important;">Action</th>
							<th style="text-align:center !important;">Bank</th>
							<th style="text-align:center !important;">Date of Payment</th>
							<th style="text-align:center !important;">Reference No.</th>
							<th style="text-align:center !important;">Amount</th>	
						</tr>
						</thead>
							<tbody id="update_table_payment_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>
                
              </div><!-- End Default Tabs -->
					</div>
				</div>
			</div>
		</div>
        
	<!--Modal to Product-->
	<div class="modal fade" id="AddProductModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProduct">

						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list" name="product_name" id="product_idx" required autocomplete="off" onchange="TotalAmount()"placeholder="Date">
						  <label for="product_idx">Product</label>
						 </div>
						<span class="valid-feedback" id="product_idxError"></span>
						
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="product_manual_price" id="product_manual_price" value="" step=".01" onchange="TotalAmount()" placeholder="Custom Price">
								<label for="product_manual_price">Custom Price</label>
							</div>
						 
						</div>
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="order_quantity" id="order_quantity" required step=".01" onchange="TotalAmount()" placeholder="Quantity">
								<label for="order_quantity">Quantity</label>
							</div>
							 <span class="valid-feedback" id="order_quantityError"></span>
						</div>

						<div class="row mb-2">
						  <label class="col-sm-3 col-form-label">Amount</label>
						  <div class="col-sm-9">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="TotalAmount">0.00</span>
						  </div>
						</div>
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product" value="0"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-so-save-product"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>		

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PurchaseOrderProductDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Product?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Product: <span id="bill_delete_product_name"></span><br>
				Quantity: <span id="bill_delete_order_quantity"></span><br>
				
				Total Amount: <span id="bill_delete_order_total_amount"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePurchaseOrderComponentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>

	<!--Data List for Product-->
	<datalist id="product_list">
			<span >	</span>
	</datalist>	
	
	<!--Modal to Product-->
	<div class="modal fade" id="AddPaymentModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Payment</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddPayment" enctype="multipart/form-data" action="{{route('save_purchase_order_payment')}}"  method="post" >
						@csrf
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						
						<input type='text' class='form-control' id='purchase_order_bank' name='purchase_order_bank' list='purchase_order_bank_list' autocomplete='off' placeholder="Bank">
							<datalist id='purchase_order_bank_list'>
								<?php foreach ($purchase_payment_suggestion as $purchase_order_bank_cols) {?>
									<option value='<?=$purchase_order_bank_cols->purchase_order_bank;?>'>
								<?php } ?>
							</datalist>
							<label for="purchase_order_bank">Bank</label>
							<span class="valid-feedback" id="purchase_order_bankError"></span>
						</div>
						
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='date' class='form-control' id='purchase_order_date_of_payment' name='purchase_order_date_of_payment' value='<?=date('Y-m-d');?>'>
								<label for="purchase_order_date_of_payment">Date of Payment</label>
								<span class="valid-feedback" id="purchase_order_date_of_paymentError"></span>
							</div>
						 
						</div>
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="purchase_order_reference_no" id="purchase_order_reference_no" required placeholder="Reference No.">
								<label for="purchase_order_reference_no">Reference No.</label>
								<span class="valid-feedback" id="purchase_order_reference_noError"></span>
							</div>
							 
						</div>
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="purchase_order_payment_amount" id="purchase_order_payment_amount" required step=".01" placeholder="Amount">
								<label for="purchase_order_payment_amount">Amount</label>
								<span class="valid-feedback" id="purchase_order_payment_amountError"></span>
							</div>
							 
						</div>
						<!--
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="purchase_order_payment_withdraw_equivalent_amount" id="purchase_order_payment_withdraw_equivalent_amount" required step=".01" placeholder="Amount">
								<label for="purchase_order_payment_withdraw_equivalent_amount">Withdraw Equivalent Amount</label>
								<span class="valid-feedback" id="purchase_order_payment_withdraw_equivalent_amountError"></span>
							</div>
							 
						</div>-->
						
						<div class="row mb-3">
							<div class="col-sm-12">
							<label for="payment_image_reference" class="form-label">Upload</label>
							<input class="form-control" type="file" id="payment_image_reference" name="payment_image_reference">
							</div>
						
						<!--<img id="preview-image-before-upload" src="" alt="preview image" style="max-height: 250px;">-->
						 <input type="hidden" id="purchase_order_id_payment" name="purchase_order_id_payment" value="{{ $PurchaseOrderID }}">
						 <input type="hidden" id="purchase_order_payment_details_id" name="purchase_order_payment_details_id" value="">		
						</div>
						
						<div class="row mb-3">
							<div class="col-sm-12">
								<div class="img-holder" align="center" id="image_payment_div"></div>
							</div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_update_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-payment" value="0"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-payment"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>			
	
	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PurchaseOrderPaymentDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Payment?<br>
				</div>
				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">
				
				Bank: <span id="delete_purchase_order_bank"></span><br>
				Date Of Payment: <span id="delete_purchase_order_date_of_payment"></span><br>	
				Reference No.: <span id="delete_purchase_order_reference_no"></span><br>
				Amount: <span id="delete_purchase_order_payment_amount"></span><br>
				
				</div>
				</div>
				<div class="col-sm-8">
					<div class="delete_img-holder" align="center"></div>
				</div>
				</div>
				
                <div class="modal-footer footer_modal_bg">
				
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePurchaseOrderPaymentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
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
	
    </section>
</main>


	<!--Modal to Product Delivery-->
	<div class="modal fade" id="AddProductDeliveryModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product Withdrawal Details</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProductDelivery">

						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='date' class='form-control' id='purchase_order_delivery_date' name='purchase_order_delivery_date' value='<?=date('Y-m-d');?>' max="9999-12-31" required>
								<label for="purchase_order_delivery_date">Withdrawal Date</label>
								<span class="valid-feedback" id="purchase_order_delivery_dateError"></span>
							</div>
						 
						</div>

						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list_delivery" name="purchase_order_component_product_idx" id="purchase_order_component_product_idx" required autocomplete="off" placeholder="Product">
						<!--Data List for Product-->
							<datalist id="product_list_delivery">
							<span >	</span>
							
							</datalist>								
							<label for="product_delivery_idx">Product</label>
							<span class="valid-feedback" id="purchase_order_component_product_idxError"></span>
						 </div>
						
						
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="purchase_order_delivery_quantity" id="purchase_order_delivery_quantity" required step=".01" placeholder="Quantity">
								<label for="purchase_order_delivery_quantity">Quantity</label>
								<span class="valid-feedback" id="purchase_order_delivery_quantityError"></span>
							</div>
							 
							 
						</div>

						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="purchase_order_delivery_withdrawal_reference" id="purchase_order_delivery_withdrawal_reference" placeholder="Withdrawal Reference">
								<label for="purchase_order_delivery_withdrawal_reference">Withdrawal Reference</label>
							</div>
							 <span class="valid-feedback" id="purchase_order_delivery_withdrawal_referenceError"></span>
							 
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="purchase_order_delivery_hauler_details" id="purchase_order_delivery_hauler_details" placeholder="Hauler Details">
								<label for="purchase_order_delivery_hauler_details">Hauler Details</label>
							</div>
							 <span class="valid-feedback" id="purchase_order_delivery_hauler_detailsError"></span>
							 
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="purchase_order_delivery_remarks" id="purchase_order_delivery_remarks" placeholder="Ship To Account">
								<label for="purchase_order_delivery_remarks">Remarks</label>
							</div>
							 <span class="valid-feedback" id="purchase_order_delivery_remarksError"></span>
							 
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product_delivery" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product-delivery"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-so-save-product"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>		
			 
	<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="PurchaseOrderProductDeliveryDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Product Withdrawal?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Withdrawal Date: <span id="delete_delivery_purchase_order_delivery_date"></span><br>
				
				Product: <span id="delete_delivery_delete_product_name"></span><br>
				Quantity: <span id="delete_delivery_delete_purchase_order_delivery_quantity"></span><br>
				
				Withdrawal Reference: <span id="delete_delivery_purchase_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="delete_delivery_purchase_order_delivery_hauler_details"></span><br>
				
				Remarks: <span id="delete_delivery_purchase_order_delivery_remarks"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePurchaseOrderProdcutDeliveryConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
	
		<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="PurchaseOrderProductDeliveryViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Withdrawal Information<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Withdrawal Date: <span id="view_delivery_purchase_order_delivery_date"></span><br>
				
				Product: <span id="view_delivery_delete_product_name"></span><br>
				Quantity: <span id="view_delivery_delete_purchase_order_delivery_quantity"></span><br>
				
				Withdrawal Reference: <span id="view_delivery_purchase_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="view_delivery_purchase_order_delivery_hauler_details"></span><br>
				
				Remarks: <span id="view_delivery_purchase_order_delivery_remarks"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
	
	

	
@endsection

