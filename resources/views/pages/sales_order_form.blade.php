@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	

    <section class="section">
		<div class="card">
		
            <div class="card-body">
			
				<h5 class="card-title" align="center">Sales Order Information</h5>
				<div class="row mb-2">
					<div class="col-sm-6">
					<h6>S.O. No : <span class="badge" id='control_no' style="background-color: orange !important; color:#000 !important; font-weight:bold;">{{ $sales_order_data[0]['sales_order_control_number'] }}</span></h6>
					</div>
					<div class="col-sm-6">
						<div class="d-flex justify-content-end" id="">
							<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -35px; position: absolute;">
								<a class="btn btn-secondary new_item bi bi-chevron-double-left form_button_icon" href="{{ route('salesorder') }}" title="Back">  
								  <span title="Back to Sales Order List">Back</span>
								</a>
								<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintSalesOrder">&nbsp;Print</button>
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
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <!-- nav-link active -->
				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='sales_order_information') { echo 'active'; } ?>" id="sales_order_information-tab" data-bs-toggle="tab" data-bs-target="#sales_order_information" type="button" role="tab" aria-controls="sales_order_information" aria-selected="true" title='View Sales Order Information'>Sales Order Information</button>
                </li>
				
				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='product') { echo 'active'; } ?>" id="product-tab" data-bs-toggle="tab" data-bs-target="#product" type="button" role="tab" aria-controls="product" aria-selected="true" title='Product List, Create, Update and Delete Product'>Product</button>
                </li>
				
				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='delivery') { echo 'active'; } ?>" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab" aria-controls="delivery" aria-selected="true" onclick="" title='Product Delivery List, Create, Update and Delete Delivery' <?=$sales_order_disabled_tab;?>>Delivery</button>
                </li>
				
                <li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='payment') { echo 'active'; } ?>" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false" tabindex="-1" title='Payment List, Create, Update and Delete Payment' <?=$sales_order_disabled_tab;?>>Payment</button>
                </li>
				
				<li class="nav-item" role="presentation">
                  <button class="nav-link <?php if($tab=='receivable') { echo 'active'; } ?>" id="receivable-tab" data-bs-toggle="tab" data-bs-target="#receivable" type="button" role="tab" aria-controls="receivable" aria-selected="false" tabindex="-1" title='Update Receivable Information' <?=$sales_order_disabled_tab;?>>Recievable Details</button>
                </li>
				
              </ul>
			  
              <div class="tab-content pt-2" id="myTabContent">
               
			   	<div class="tab-pane fade  <?php if($tab=='sales_order_information') { echo ' show active'; } ?>"" id="sales_order_information" role="tabpanel" aria-labelledby="sales_order_information-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-pencil-fill form_button_icon " data-bs-toggle="modal" data-bs-target="#UpdateSalesOrderModal" id="UpdateSalesOrderBTN"></button>
						
					</div>	
					
					</div>
					<!--Information Here-->
					<ul class="list-group list-group-flush">
							<li class="list-group-item"><b>Date:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_date'] }}</span></li>
							<li class="list-group-item"><b>Branch:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['branch_code'] }} - {{ $sales_order_data[0]['branch_name'] }}</span></li>
							<li class="list-group-item"><b>Sold To:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['client_name'] }}</span></li>
							<li class="list-group-item"><b>Payment Type:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_payment_type'] }}</span></li>
							<li class="list-group-item"><b>With Sales Invoice:</b><span id="" style="font-weight: normal;">&nbsp;<?php if($sales_order_invoice=='1'){ echo "YES";} else{ echo "NO";} ?></span></li>
							<li class="list-group-item"><b>Net Value:</b><span id="" style="font-weight: normal;"></span>&nbsp;&nbsp;{{ $sales_order_data[0]['sales_order_net_percentage'] }}</li>
							<li class="list-group-item"><b>Withholding Tax:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_withholding_tax'] }}</span></li>
							<li class="list-group-item"><b>D.R Number:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_dr_number'] }}</span></li>
							<li class="list-group-item"><b>P.O Number:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_po_number'] }}</span></li>
							<li class="list-group-item"><b>Sales Invoice:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_or_number'] }}</span></li>
							<li class="list-group-item"><b>Charge Invoice:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_charge_invoice'] }}</span></li>
							<li class="list-group-item"><b>Collection Receipt:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_collection_receipt'] }}</span></li>
							<li class="list-group-item"><b>Payment Term:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_payment_term'] }}</span></li>
							<li class="list-group-item"><b>Delivered To:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_delivered_to'] }}</span></li>
							<li class="list-group-item"><b>Delivered To Address:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}</span></li>
							<li class="list-group-item"><b>Delivery Method:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_delivery_method'] }}</span></li>
							<li class="list-group-item"><b>Hauler:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_hauler'] }}</span></li>
							<li class="list-group-item"><b>Required Date:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_required_date'] }}</span></li>
							<li class="list-group-item"><b>Instructions:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_instructions'] }}</span></li>
							<li class="list-group-item"><b>Notes:</b><span id="" style="font-weight: normal;">&nbsp;{{ $sales_order_data[0]['sales_order_note'] }}</span></li>
							<li class="list-group-item"></li>
					</ul>
						
						
                </div>
			   
			   <div class="tab-pane fade  <?php if($tab=='product') { echo ' show active'; } ?>"" id="product" role="tabpanel" aria-labelledby="product-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductModal" id="AddSalesOrderProductBTN"></button>
						
					</div>											
					</div>
					
						<table class="table table-striped" id="">
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
							<tbody id="table_sales_order_product_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>
				
				
                <div class="tab-pane fade  <?php if($tab=='delivery') { echo ' show active'; } ?>" id="delivery" role="tabpanel" aria-labelledby="delivery-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductDeliveryModal" id="addProductDeliveredBTN" onclick="ResetDeliveryForm()" title="Add Sales Order Delivery Items"></button>
						<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintSalesOrderDeliveyStatus" title="Print Sales Order Delivered Item / Status"></button>
					</div>											
					</div>
					
						<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;" class="">Action</th>
							<th style="text-align:center !important;">Item #</th>
							<th style="text-align:center !important;">Delivery Date</th>
							<th style="text-align:center !important;">Product</th>
							<th style="text-align:center !important;">Quantity</th>
						</tr>
						</thead>
							<tbody id="product_list_delivery_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>
				
                <div class="tab-pane fade <?php if($tab=='payment') { echo 'show active'; } ?>" id="payment" role="tabpanel" aria-labelledby="payment-tab">
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#AddPaymentModal" id="AddPaymentOrderProductBTN" onclick="ResetPaymentForm()"></button>
						<button type="button" class="btn new_item bi bi-images" data-bs-toggle="modal" data-bs-target="#ViewPaymentGalery" style="background-color: magenta;"></button>
					</div>					
					</div>
					
						<table class="table table-striped" id="">
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
							<tbody id="update_table_payment_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
                </div>
                
				<div class="tab-pane fade <?php if($tab=='receivable') { echo 'show active'; } ?>" id="receivable" role="tabpanel" aria-labelledby="receivable-tab">
		
				<div class="d-flex justify-content-end" id="">
		
							<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
							
								<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintSOA">&nbsp;SOA</button>
								<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintReceivable">&nbsp;Receivable</button>
								<!--<button type="button" class="btn btn-success new_item bi bi-printer" onclick="#"></button>-->
								
							</div>
							
						</div>
					<div class="row mb-2">
					
					<div class="col-sm-12">
					<h6>
						<div class="row mb-2">
							<label class="col-sm-4 col-form-label">Control Number :</label>
							<label class="col-sm-8 col-form-label" id='receivable_control_number_info'>{{ @$receivables_details['control_number'] }}</label>
							
							<?php
								if(@$receivables_details['control_number']===null){
									?>
									<script>
										location.reload();
									</script>
									<?php
								}
							?>
							
						</div>
					</h6>
					
					<form class="g-2 needs-validation pt-1" id="ReceivableformEditFromSalesOrder">
						
						<div class="row mb-2">
						  <label for="receivable_billing_date_SO" class="col-sm-4 col-form-label">Billing Date : </label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="receivable_billing_date_SO" id="receivable_billing_date_SO" value="" required>
							<span class="valid-feedback" id="receivable_billing_date_SO_Error"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="receivable_payment_term_SO" class="col-sm-4 col-form-label">Payment Term : </label>
						  <div class="col-sm-8">
							<input type="text" class="form-control " name="receivable_payment_term_SO" id="receivable_payment_term_SO" value="">
							<span class="valid-feedback" id="receivable_payment_term_SO_Error"></span>
						  </div>
						</div>							
						
						<div class="row mb-2">
						  <label for="receivable_description_SO" class="col-sm-4 col-form-label">Description : </label>
						  <div class="col-sm-8">
							<textarea class="form-control" id="receivable_description_SO" style="height: 50px;" required></textarea>
							<span class="valid-feedback" id="receivable_description_SO_Error"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">Amount : </label>
						  <label class="col-sm-8 col-form-label" id="receivable_amount_info"></label>
						</div>
					<div class="card-footer">
												<div class="row mb-3">
												<div class="col-sm-6" align="">
												<div id="update_loading_data_receivable" style="display:none;">
													<div class="spinner-border text-success" role="status">
														<span class="visually-hidden">Loading...</span>
													</div>
												</div>
												</div>
												<div class="col-sm-6" align="right">
												
												<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="SO-update-receivables" title="Update Receivable Information"> Update</button>
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
 
	<!--Modal to Create SO Product-->
	<div class="modal fade" id="AddProductModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product</h5>
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
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-so-save-product"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>		

	<!--Modal to Create SO Product-->
	<div class="modal fade" id="EditProductModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="EditSalesOrderComponentProduct">

						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list" name="edit_product_name" id="edit_product_idx" required autocomplete="off" onchange="UpdateTotalAmount()"placeholder="Date">
						  <label for="edit_product_idx">Product</label>
						 </div>
						<span class="valid-feedback" id="edit_product_idxError"></span>
									
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" name="edit_product_manual_price" id="edit_product_manual_price" value="" step=".01" onchange="UpdateTotalAmount()" placeholder="Custom Price">
								<label for="edit_product_manual_price">Custom Price</label>
							</div>
						 
						</div>
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="edit_order_quantity" id="edit_order_quantity" required step=".01" onchange="UpdateTotalAmount()" placeholder="Quantity">
								<label for="edit_order_quantity">Quantity</label>
							</div>
							 <span class="valid-feedback" id="edit_order_quantityError"></span>
							 
						</div>

						<div class="row mb-2">
						  <div class="col-sm-3 col-form-label">Amount</div>
						  <div class="col-sm-9">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="UpdateTotalAmount">0.00</span>
						  </div>
						</div>
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_update_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-product"> Update</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>		

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="SalesOrderComponentDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
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
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSalesOrderComponentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>

	<!--Data List for Product-->
	<datalist id="product_list">
			<span >	</span>
	</datalist>	
    </section>
	
	
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
					
					  <form class="g-3 needs-validation" id="AddPayment" enctype="multipart/form-data" action="{{route('sales_order_receivable_payment')}}"  method="post" >
						@csrf
						
						<div class="col-sm-12">
						<div class="form-floating mb-3">
							<select class="form-select" required name="receivable_mode_of_payment" id="receivable_mode_of_payment">
								<option value="Cash" selected>Cash</option>
								<option value="Bank Transfer">Bank Transfer</option>
								<option value="Cash Deposit">Cash Transfer</option>
								<option value="Credit Card">Credit Card</option>
								<option value="Debit Card">Debit Card</option>
								<option value="E-Wallet/Mobile Payments">E-Wallet/Mobile Payments</option>
								<option value="Dated Check">Dated Check</option>
								<option value="Post-Dated Check">Post-Dated Check</option>
								<option value="Post-Dated Check - Cleared">Post-Dated Check - Cleared</option>
								<option value="Overpayment">Overpayment</option>
								<option value="Payroll Deduction">Payroll Deduction</option>
							</select>
							<label for="receivable_mode_of_payment">Mode of Payment</label>
						</div>
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='date' class='form-control' id='receivable_date_of_payment' name='receivable_date_of_payment' value='<?=date('Y-m-d');?>' max="9999-12-31" >
								<label for="receivable_date_of_payment">Date</label>
								<span class="valid-feedback" id="receivable_date_of_paymentError"></span>
							</div>
						 
						</div>
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='time' class='form-control' id='receivable_time_of_payment' name='receivable_time_of_payment' value='<?=date('H:i');?>'>
								<label for="receivable_time_of_payment">Time of Payment</label>
								<span class="valid-feedback" id="receivable_time_of_paymentError"></span>
							</div>
						 
						</div>						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="receivable_reference" id="receivable_reference" required placeholder="Reference No.">
								<label for="receivable_reference">Reference No.</label>
								<span class="valid-feedback" id="receivable_referenceError"></span>
							</div>
							 
						</div>
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="receivable_payment_amount" id="receivable_payment_amount" required step=".01" placeholder="Amount">
								<label for="receivable_payment_amount">Amount</label>
								<span class="valid-feedback" id="receivable_payment_amountError"></span>
							</div>
							 
						</div>
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="receivable_payment_remarks" id="receivable_payment_remarks" placeholder="Remarks/Comments">
								<label for="receivable_payment_remarks">Remarks</label>
								<span class="valid-feedback" id="receivable_payment_remarksError"></span>
							</div>
							 
						</div>
						
						<div class="row mb-3">
							<div class="col-sm-12">
							<label for="payment_image_reference" class="form-label">Upload</label>
							<input class="form-control" type="file" id="payment_image_reference" name="payment_image_reference">
							</div>
						
						<!--<img id="preview-image-before-upload" src="" alt="preview image" style="max-height: 250px;">-->
						 <input type="hidden" id="receivable_idx_payment" name="receivable_idx_payment" value="{{ @$receivables_details['receivable_id'] }}">
						 <input type="hidden" id="receivable_payment_id" name="receivable_payment_id" value="">		
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
	
	<!-- Bill Delete Modal-->
    <div class="modal fade" id="SalesOrderPaymentDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
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
					Mode of Payment: <span id="delete_receivable_mode_of_payment"></span><br>
					Date: <span id="delete_receivable_date_of_payment"></span><br>	
					Reference No.: <span id="delete_receivable_reference"></span><br>
					Amount: <span id="delete_receivable_payment_amount"></span><br>
				</div>
				</div>
				<div class="col-sm-8">
					<div class="delete_img-holder" align="center"></div>
				</div>
				</div>
				
                <div class="modal-footer footer_modal_bg">
				
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSalesOrderPaymentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
	
	<!-- Bill Delete Modal-->
    <div class="modal fade" id="ViewOrderViewPaymentReferenceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Information</h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">
				
				Date: <span id="view_receivable_date_of_payment"></span><br>	
				Time: <span id="view_receivable_time_of_payment"></span><br>	
				Mode of Payment: <span id="view_receivable_mode_of_payment"></span><br>
				Reference No.: <span id="view_receivable_reference"></span><br>
				Remarks: <span id="view_receivable_payment_remarks"></span><br>
				Amount: <span id="view_receivable_payment_amount"></span><br>
				
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

	<!--Modal to Product Delivery-->
	<div class="modal fade" id="AddProductDeliveryModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product Delivery Details</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProductDelivery">

						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='date' class='form-control' id='sales_order_delivery_date' name='sales_order_delivery_date' value='<?=date('Y-m-d');?>' max="9999-12-31" required>
								<label for="sales_order_delivery_date">Delivery Date</label>
								<span class="valid-feedback" id="sales_order_delivery_dateError"></span>
							</div>
						 
						</div>

						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list_delivery" name="sales_order_component_product_idx" id="sales_order_component_product_idx" required autocomplete="off" placeholder="Product">
						<!--Data List for Product-->
							<datalist id="product_list_delivery">
							<span >	</span>
							
							</datalist>								
							<label for="sales_order_component_product_idx">Product</label>
							<span class="valid-feedback" id="sales_order_component_product_idxError"></span>
						 </div>
						
						
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="sales_order_delivery_quantity" id="sales_order_delivery_quantity" required step=".01" placeholder="Quantity">
								<label for="sales_order_delivery_quantity">Quantity</label>
								<span class="valid-feedback" id="sales_order_delivery_quantityError"></span>
							</div>
							 
							 
						</div>

						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="sales_order_delivery_withdrawal_reference" id="sales_order_delivery_withdrawal_reference" placeholder="Delivery Reference">
								<label for="sales_order_delivery_withdrawal_reference">Delivery Reference</label>
							</div>
							 <span class="valid-feedback" id="sales_order_delivery_withdrawal_referenceError"></span>
							 
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="sales_order_delivery_hauler_details" id="sales_order_delivery_hauler_details" placeholder="Hauler Details">
								<label for="sales_order_delivery_hauler_details">Hauler Details</label>
							</div>
							 <span class="valid-feedback" id="sales_order_delivery_hauler_detailsError"></span>
							 
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-describedby="basic-addon1" name="sales_order_delivery_remarks" id="sales_order_delivery_remarks" placeholder="Ship To Account">
								<label for="sales_order_delivery_remarks">Remarks</label>
							</div>
							 <span class="valid-feedback" id="sales_order_delivery_remarksError"></span>
							 
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
    <div class="modal fade" id="SalesOrderProductDeliveryDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Product Delivery?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Delivery Date: <span id="delete_delivery_sales_order_delivery_date"></span><br>
				
				Product: <span id="delete_delivery_delete_product_name"></span><br>
				Quantity: <span id="delete_delivery_delete_sales_order_delivery_quantity"></span><br>
				
				Delivery Reference: <span id="delete_delivery_sales_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="delete_delivery_sales_order_delivery_hauler_details"></span><br>
				
				Remarks: <span id="delete_delivery_sales_order_delivery_remarks"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSalesOrderProdcutDeliveryConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
	
		<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="SalesOrderProductDeliveryViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Delivery Information<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Delivery Date: <span id="view_delivery_sales_order_delivery_date"></span><br>
				
				Product: <span id="view_delivery_delete_product_name"></span><br>
				Quantity: <span id="view_delivery_delete_sales_order_delivery_quantity"></span><br>
				
				Delivery Reference: <span id="view_delivery_sales_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="view_delivery_sales_order_delivery_hauler_details"></span><br>
				
				Remarks: <span id="view_delivery_sales_order_delivery_remarks"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
	
</main>

@endsection

