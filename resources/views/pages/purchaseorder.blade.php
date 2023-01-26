@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="sales_order_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="getPurchaseOrderList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Control Number</th>
													<th>Supplier</th>
													<th>Total Payable</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Control Number</th>
													<th>Supplier</th>
													<th>Total Payable</th>
													<th>Action</th>											
												</tr>
											</tfoot>	
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>


	<!--Modal to Create Sales Order-->
	<div class="modal fade" id="CreateSalesOrderModal" tabindex="-1">
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Purchase Order</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					<form class="g-2 needs-validation" id="SalesOrderformNew">		

									<div class="row">


										<div class="col-md-3">
										  <label for="purchase_order_date" class="form-label">Date</label>
										  <input type="date" class="form-control" id="purchase_order_date" name="purchase_order_date" value="<?=date('Y-m-d');?>">
										</div>
									
										<div class="col-md-3">
										  <label for="purchase_supplier_name" class="form-label">Supplier's Name</label>
										  <input type="text" class="form-control" id="purchase_supplier_name" name="purchase_supplier_name" >
										</div>
										
										<div class="col-md-3">
										  <label for="purchase_supplier_tin" class="form-label" title="Supplier's Tax Identification">TIN</label>
										  <input type="text" class="form-control" id="purchase_supplier_tin" name="purchase_supplier_tin" >
										</div>
										
										<div class="col-md-3">
										  <label for="purchase_supplier_address" class="form-label">Payment Term</label>
										  <input type="text" class="form-control" id="purchase_supplier_address" name="purchase_supplier_address">
										</div>	
										
									</div>
									
								<hr>
								
								<div class="row">
								
									<div class="col-md-3">
										<label for="purchase_order_sales_order_number" class="form-label">Sales Order #</label>
										<input type="text" class="form-control" id="purchase_order_sales_order_number" name="purchase_order_sales_order_number">
										<span class="valid-feedback" id="purchase_order_sales_order_numberError"></span>
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_collection_receipt_no" class="form-label">Collection Receipt #</label>
									  <input type="text" class="form-control" id="purchase_order_collection_receipt_no" name="purchase_order_collection_receipt_no">
									  <span class="valid-feedback" id="purchase_order_collection_receipt_noError"></span>
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_official_receipt_no" class="form-label">Official Receipt #</label>
									  <input type="text" class="form-control" id="purchase_order_official_receipt_no" name="purchase_order_official_receipt_no">
									  <span class="valid-feedback" id="purchase_order_official_receipt_nooError"></span>
									</div>
										
									<div class="col-md-3">
									  <label for="purchase_order_delivery_receipt_no" class="form-label">Delivery Receipt #</label>
									  <input type="text" class="form-control" id="purchase_order_delivery_receipt_no" name="purchase_order_delivery_receipt_no">
									  <span class="valid-feedback" id="purchase_order_delivery_receipt_noError"></span>
									</div>
									
								</div>
								
								<hr>
								<div class="row">
								
								<h6 class="modal-title">Payment Details</h6>
								
									<div class="col-md-3">
									  <label for="purchase_order_bank" class="form-label">Bank</label>
									  <input type="text" class="form-control" id="purchase_order_bank" name="purchase_order_bank">
									</div>
									<div class="col-md-3">
									  <label for="purchase_order_date_of_payment" class="form-label">Date of Payment</label>
									  <input type="date" class="form-control" id="purchase_order_date_of_payment" name="purchase_order_date_of_payment" value="<?=date('Y-m-d');?>">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_reference_no" class="form-label">Reference No.</label>
									  <input type="text" class="form-control" id="purchase_order_reference_no" name="purchase_order_reference_no">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_payment_amount" class="form-label">Amount</label>
									  <input type="number" class="form-control" id="purchase_order_payment_amount" name="purchase_order_payment_amount">
									</div>
								
								</div>
								
								<hr>
								
								<div class="row">
								
									<div class="col-md-3">
									  <label for="purchase_order_delivery_method" class="form-label">Delivery Method</label>
									  <input type="text" class="form-control" id="purchase_order_delivery_method" name="purchase_order_delivery_method">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_hauler" class="form-label">Hauler</label>
									  <input type="text" class="form-control" id="purchase_order_hauler" name="purchase_order_hauler" >
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_date_of_pickup" class="form-label">Date of Pick-up</label>
									  <input type="date" class="form-control" id="purchase_order_date_of_pickup" name="purchase_order_date_of_pickup" value="<?=date('Y-m-d');?>">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_order_date_of_arrival" class="form-label">Date of Arrival</label>
									  <input type="date" class="form-control" id="purchase_order_date_of_arrival" name="purchase_order_date_of_arrival" value="<?=date('Y-m-d');?>">
									</div>
									
								</div>
								
								<hr>
								
								<h6 class="modal-title">Product</h6>
								<div align="right">
								<button type="button" class="btn btn-success new_item bi bi-cart-plus-fill" onclick="AddProductRow();" title="Add a Product(1-5 items)"></button>
								</div>
								<br>
								
								<table class="table" id="table_product_data">
								
									<thead>
										<tr class='report'>
										<th style="text-align:center !important;" width='40%'>Product</th>
										<th style="text-align:center !important;">Quantity</th>
										<th style="text-align:center !important;">Price</th>
										<th style="text-align:center !important;">Action</th>
										</tr>
									</thead>
										
									<tbody id="table_product_body_data">
										 <tr style="display: none;"><td>HIDDEN</td></tr>
									</tbody>
									
								</table>
								<div style="color:red;" id="product_idxError"></div>
								<hr>
								<div class="row">
									<div class="col-md-6">
									  <label for="update_purchase_order_date" class="form-label">Net Value</label>
									  <input type="number" class="form-control" id="sales_order_net_percentage" name="sales_order_net_percentage" step=".01">
									</div>
									
									<div class="col-md-6">
									  <label for="update_purchase_supplier_tin" class="form-label">Less Value</label>
									  <input type="number" class="form-control" id="order_less_percentage" name="sales_order_less_percentage" step=".01">
									</div>
								</div>
								<hr>								
								<div class="row">
								
								<h6 class="modal-title">Hauler Details</h6>
								
									<div class="col-md-3">
									  <label for="purchase_order_bank" class="form-label">Driver</label>
									  <input type="text" class="form-control" id="purchase_order_bank" name="purchase_order_bank">
									</div>
									<div class="col-md-3">
									  <label for="purchase_order_date_of_payment" class="form-label">Lory Plate #</label>
									  <input type="date" class="form-control" id="purchase_order_date_of_payment" name="purchase_order_date_of_payment" value="<?=date('Y-m-d');?>">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_loading_terminal" class="form-label">Loading Terminal</label>
									  <input type="text" class="form-control" id="purchase_loading_terminal" name="purchase_loading_terminal">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_terminal_address" class="form-label">Address</label>
									  <input type="number" class="form-control" id="purchase_terminal_address" name="purchase_terminal_address">
									</div>
								
								</div>
								
								<div class="row">
								
									<div class="col-md-3">
									  <label for="purchase_destination" class="form-label">Destination</label>
									  <input type="text" class="form-control" id="purchase_destination" name="purchase_destination">
									</div>
									<div class="col-md-3">
									  <label for="purchase_destination_address" class="form-label">Address</label>
									  <input type="text" class="form-control" id="purchase_destination_address" name="purchase_destination_address" value="">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_date_of_departure" class="form-label">Date of Departure</label>
									  <input type="text" class="form-control" id="purchase_date_of_departure" name="purchase_date_of_departure">
									</div>
									
									<div class="col-md-3">
									  <label for="purchase_date_of_arrival" class="form-label">Date of Arrival</label>
									  <input type="number" class="form-control" id="purchase_date_of_arrival" name="purchase_date_of_arrival">
									</div>
								
								</div>
								
								<div class="row">
								
									<div class="col-md-6">
									  <label for="purchase_order_instructions" class="form-label">Instructions</label>
									  <textarea class="form-control" id="purchase_order_instructions" name="purchase_order_instructions" style="height: 38px;"></textarea>
									</div>
									
									<div class="col-md-6">
									  <label for="purchase_order_note" class="form-label">Notes</label>
									  <textarea class="form-control" id="purchase_order_note" name="purchase_order_note" style="height: 38px;"></textarea>
									</div>
					
								</div>
								<hr>
								
								
							
					</div>
					<div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-sales-order"> Submit</button>
									<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon"> Reset</button>
						  
					</div>
					</form>		
                  </div>
              </div>
    </div>

	<!--Modal to Update Sales Order-->
	<div class="modal fade" id="UpdateSalesOrderModal" tabindex="-1">
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Sales Order</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					<form class="g-2 needs-validation" id="UpdateSalesOrderformUpdate">		
									<div class="row">
									
										<div class="col-md-3">
										  <label for="update_purchase_order_date" class="form-label">Date</label>
										  <input type="date" class="form-control" id="update_purchase_order_date" name="update_purchase_order_date" value="<?=date('Y-m-d');?>">
										</div>
										<div class="col-md-3">
										  <label for="update_purchase_supplier_name" class="form-label">D.R Number</label>
										  <input type="text" class="form-control" id="update_purchase_supplier_name" name="update_purchase_supplier_name" >
										</div>
										
										<div class="col-md-3">
										  <label for="update_purchase_supplier_tin" class="form-label">O.R Number</label>
										  <input type="text" class="form-control" id="update_purchase_supplier_tin" name="update_purchase_supplier_tin" >
										</div>
										
										<div class="col-md-3">
										  <label for="update_purchase_supplier_address" class="form-label">Payment Term</label>
										  <input type="text" class="form-control" id="update_purchase_supplier_address" name="update_purchase_supplier_address">
										</div>	
										
									</div>
									
								<hr>
								
								<div class="row">
								<div class="col-md-4">
										<label for="update_client_idx" class="form-label">Sold To</label>
										<select class="form-control form-select " name="update_client_idx" id="update_client_idx" required>
											<option selected="" disabled="" value="">Choose...</option>
												@foreach ($client_data as $client_data_cols)
											<option value="{{$client_data_cols->client_id}}">{{$client_data_cols->client_name}}</option>
												@endforeach
										</select>
										<span class="valid-feedback" id="update_client_idxError"></span>
									</div>
									<div class="col-md-4">
										<label for="update_purchase_order_sales_order_number" class="form-label">Delivered To</label>
										<input type="text" class="form-control" id="update_purchase_order_sales_order_number" name="update_purchase_order_sales_order_number">
										<span class="valid-feedback" id="update_purchase_order_sales_order_numberError"></span>
									</div>
									
									<div class="col-md-4">
									  <label for="update_purchase_order_sales_order_number_address" class="form-label">Delivered To Address</label>
									  <input type="text" class="form-control" id="update_purchase_order_sales_order_number_address" name="update_purchase_order_sales_order_number_address">
									  <span class="valid-feedback" id="update_purchase_order_sales_order_number_addressError"></span>
									</div>
									
								</div>
								
								<hr>
								
								<div align="right">
								<button type="button" class="btn btn-success new_item bi bi-cart-plus-fill" onclick="UpdateProductRow();" title="Add a Product(1-5 items)"></button>
								</div>
								<br>
								
								<table class="table" id="update_table_product_data">
								
									<thead>
										<tr class='report'>
										<th style="text-align:center !important;" width='40%'>Product</th>
										<th style="text-align:center !important;">Quantity</th>
										<th style="text-align:center !important;">Price</th>
										<th style="text-align:center !important;">Action</th>
										</tr>
									</thead>
										
									<tbody id="update_table_product_body_data">
										 <tr style="display: none;"><td>HIDDEN</td></tr>
									</tbody>
									
								</table>
								<div style="color:red;" id="update_product_idxError"></div>
								<hr>
								<div class="row">
									<div class="col-md-6">
									  <label for="update_sales_order_net_percentage" class="form-label">Net Value</label>
									  <input type="number" class="form-control" id="update_sales_order_net_percentage" name="update_sales_order_net_percentage" step=".01">
									</div>
									
									<div class="col-md-6">
									  <label for="update_sales_order_less_percentage" class="form-label">Less Value</label>
									  <input type="number" class="form-control" id="update_sales_order_less_percentage" name="update_sales_order_less_percentage" step=".01">
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-4">
									  <label for="update_purchase_order_date" class="form-label">Delivery Method</label>
									  <input type="text" class="form-control" id="update_delivery_method" name="update_delivery_method">
									</div>
									
									<div class="col-md-4">
									  <label for="update_purchase_supplier_name" class="form-label">Hauler</label>
									  <input type="text" class="form-control" id="update_hauler" name="update_hauler" >
									</div>
									
									<div class="col-md-4">
									  <label for="update_purchase_supplier_tin" class="form-label">Required Date</label>
									  <input type="date" class="form-control" id="update_required_date" name="update_required_date" value="<?=date('Y-m-d');?>">
									</div>
								</div>
								<hr>
								<div class="row">
								
									<div class="col-md-6">
									  <label for="update_purchase_supplier_name" class="form-label">Instructions</label>
									  <textarea class="form-control" id="update_instructions" name="update_instructions" style="height: 38px;"></textarea>
									</div>
									
									<div class="col-md-6">
									  <label for="update_purchase_supplier_tin" class="form-label">Notes</label>
									  <textarea class="form-control" id="update_note" name="update_note" style="height: 38px;"></textarea>
									</div>
					
								</div>
								<hr>
								<div class="row">
								
									<div class="col-md-3">
									  <label for="update_mode_of_payment" class="form-label">Mode of Payment</label>
									  <input type="text" class="form-control" id="update_mode_of_payment" name="update_mode_of_payment">
									</div>
									<div class="col-md-3">
									  <label for="update_date_of_payment" class="form-label">Date of Payment</label>
									  <input type="date" class="form-control" id="update_date_of_payment" name="update_date_of_payment" value="<?=date('Y-m-d');?>">
									</div>
									
									<div class="col-md-3">
									  <label for="update_reference_no" class="form-label">Reference No.</label>
									  <input type="text" class="form-control" id="update_reference_no" name="update_reference_no">
									</div>
									
									<div class="col-md-3">
									  <label for="update_payment_amount" class="form-label">Amount</label>
									  <input type="number" class="form-control" id="update_payment_amount" name="update_payment_amount">
									</div>
								
								</div>
								
					</div>
					<div class="modal-footer modal-footer_form">
						
						<button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-sales-order"> Submit</button>
						
						  
					</div>
					</form>		
                  </div>
              </div>
    </div>



	<!-- Sales Order Delete Modal-->
    <div class="modal fade" id="SalesOrderDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Sales Order?<br>
				</div>
				
				<div align="left"style="margin: 10px;">
				Date: <span id="confirm_delete_purchase_order_date"></span><br>
				Control Number: <span id="confirm_delete_sales_control_number"></span><br>
				Client: <span id="confirm_delete_client_name"></span><br>
				DR Number: <span id="confirm_delete_purchase_supplier_name"></span><br>
				OR Number: <span id="confirm_delete_purchase_supplier_tin"></span><br>
				Total Due: <span id="confirm_delete_total_due"></span><br>
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSalesOrderConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	

  </section>
</main>
@endsection