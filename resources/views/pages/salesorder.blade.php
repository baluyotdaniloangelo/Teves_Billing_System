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
										<table class="table table-bordered dataTable" id="getSalesOrderList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>No.</th>
													<th>Date</th>
													<th>Control Number</th>
													<th>Account Name</th>
													<th>Payment Term</th>
													<th>Gross Amount</th>
													<th>Withholding Tax</th>
													<th>Net Amount</th>
													<th>Total Due</th>
													<th>Delivery Status</th>
													<th>Payment Status</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
												<tr>
													<th>No.</th>
													<th>Date</th>
													<th>Control Number</th>
													<th>Account Name</th>
													<th>Payment Term</th>
													<th>Gross Amount</th>
													<th>Withholding Tax</th>
													<th>Net Amount</th>
													<th>Total Due</th>
													<th>Delivery Status</th>
													<th>Payment Status</th>
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
                      <h5 class="modal-title">Create Sales Order</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					<form class="g-2 needs-validation" id="SalesOrderformNew">		
								<div class="row">
									
										<div class="col-md-3">
											<label for="company_header" class="form-label">Header/Company</label>
											<select class="form-select form-control" required="" name="company_header" id="company_header">
											<option value="GT">GT</option>
											<option value="Teves">Teves</option>
											</select>
										</div>
										
								</div>
									
								<hr>
								
								<div class="row">
									
										<div class="col-md-3">
										  <label for="sales_order_date" class="form-label">Date</label>
										  <input type="date" class="form-control" id="sales_order_date" name="sales_order_date" value="<?=date('Y-m-d');?>">
										</div>
										<div class="col-md-3">
										  <label for="dr_number" class="form-label">D.R Number</label>
										  <input type="text" class="form-control" id="dr_number" name="dr_number" >
										</div>
										
										<div class="col-md-3">
										  <label for="or_number" class="form-label">O.R Number</label>
										  <input type="text" class="form-control" id="or_number" name="or_number" >
										</div>
										
										<div class="col-md-3">
										  <label for="payment_term" class="form-label">Payment Term</label>
										  <input type="text" class="form-control" id="payment_term" name="payment_term">
										</div>	
										
								</div>
									
								<hr>
								
								<div class="row">
								<div class="col-md-4">
										<label for="client_id" class="form-label">Sold To</label>
	
										 <input class="form-control" list="client_name" name="client_name" id="client_id" required autocomplete="off">
											<datalist id="client_name">
											  @foreach ($client_data as $client_data_cols)
											  <option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
											  @endforeach
											</datalist>
																				
										<span class="valid-feedback" id="client_idxError"></span>
									</div>
									<div class="col-md-4">
										<label for="delivered_to" class="form-label">Delivered To</label>
										<input type="text" class="form-control" id="delivered_to" name="delivered_to" list="sales_order_delivered_to_list">
											<datalist id="sales_order_delivered_to_list">
												@foreach ($sales_order_delivered_to as $sales_order_delivered_to_cols)
													<option value="{{$sales_order_delivered_to_cols->sales_order_delivered_to}}">
												@endforeach
											  </datalist>
										<span class="valid-feedback" id="delivered_toError"></span>
									</div>
									
									<div class="col-md-4">
									  <label for="delivered_to_address" class="form-label">Delivered To Address</label>
									  <input type="text" class="form-control" id="delivered_to_address" name="delivered_to_address" list="delivered_to_address_list">
											<datalist id="delivered_to_address_list">
												@foreach ($sales_order_delivered_to_address as $sales_order_delivered_to_address_cols)
													<option value="{{$sales_order_delivered_to_address_cols->sales_order_delivered_to_address}}">
												@endforeach
											  </datalist>
									  <span class="valid-feedback" id="delivered_to_addressError"></span>
									</div>
									
								</div>
								
								<hr>
								
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
									  <label for="update_sales_order_date" class="form-label">Net Value</label>
									  <input type="number" class="form-control" id="sales_order_net_percentage" name="sales_order_net_percentage" step=".01" value="1.12">
									</div>
									
									<div class="col-md-6">
									  <label for="update_or_number" class="form-label">Less Value</label>
									  <input type="number" class="form-control" id="order_less_percentage" name="sales_order_less_percentage" step=".01" value="1">
									</div>
								</div>
								<hr>								
								<div class="row">
									<div class="col-md-4">
									  <label for="sales_order_date" class="form-label">Delivery Method</label>
									  <input type="text" class="form-control" id="delivery_method" name="delivery_method">
									</div>
									
									<div class="col-md-4">
									  <label for="dr_number" class="form-label">Hauler</label>
									  <input type="text" class="form-control" id="hauler" name="hauler" >
									</div>
									
									<div class="col-md-4">
									  <label for="or_number" class="form-label">Required Date</label>
									  <input type="date" class="form-control" id="required_date" name="required_date" value="<?=date('Y-m-d');?>">
									</div>
								</div>
								<hr>
								<div class="row">
								
									<div class="col-md-6">
									  <label for="dr_number" class="form-label">Instructions</label>
									  <textarea class="form-control" id="instructions" name="instructions" style="height: 38px;"></textarea>
									</div>
									
									<div class="col-md-6">
									  <label for="or_number" class="form-label">Notes</label>
									  <textarea class="form-control" id="note" name="note" style="height: 38px;"></textarea>
									</div>
					
								</div>
								
								<hr>
								<h6 class="modal-title">Payment Details</h6>
								<div align="right">
								<button type="button" class="btn btn-success new_item bi bi-plus-square" onclick="AddPaymentRow();" title="Add a Paymnet Option(1-3 items)"></button>
								</div>
								<br>
								<table class="table" id="table_payment">
								
									<thead>
										<tr class='report'>
										<th style="text-align:center !important;">Mode of Payment</th>
										<th style="text-align:center !important;">Date of Payment</th>
										<th style="text-align:center !important;">Reference No.</th>
										<th style="text-align:center !important;">Amount</th>
										<th style="text-align:center !important;">Action</th>
										
										</tr>
									</thead>
										
									<tbody id="table_payment_body_data">
										 <tr style="display: none;"><td>HIDDEN</td></tr>
									</tbody>
									
								</table>
								<div style="color:red;" id="table_paymentxError"></div>

								<hr>
								
					</div>
					<div class="modal-footer modal-footer_form">
							<div id="loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
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
											<label for="update_company_header" class="form-label">Header/Company</label>
											<select class="form-select form-control" required="" name="update_company_header" id="update_company_header">
											<option value="GT">GT</option>
											<option value="Teves">Teves</option>
											</select>
										</div>
										
									</div>
									
									<hr>
									
									
									<div class="row">
									
										<div class="col-md-3">
										  <label for="update_sales_order_date" class="form-label">Date</label>
										  <input type="date" class="form-control" id="update_sales_order_date" name="update_sales_order_date" value="<?=date('Y-m-d');?>">
										</div>
										<div class="col-md-3">
										  <label for="update_dr_number" class="form-label">D.R Number</label>
										  <input type="text" class="form-control" id="update_dr_number" name="update_dr_number" >
										</div>
										
										<div class="col-md-3">
										  <label for="update_or_number" class="form-label">O.R Number</label>
										  <input type="text" class="form-control" id="update_or_number" name="update_or_number" >
										</div>
										
										<div class="col-md-3">
										  <label for="update_payment_term" class="form-label">Payment Term</label>
										  <input type="text" class="form-control" id="update_payment_term" name="update_payment_term">
										</div>	
										
									</div>
									
								<hr>
								
								<div class="row">
								<div class="col-md-4">
										<label for="update_client_idx" class="form-label">Sold To</label>
										
										<input class="form-control" list="update_client_name" name="update_client_name" id="update_client_idx" required autocomplete="off">
											<datalist id="update_client_name">
											  @foreach ($client_data as $client_data_cols)
											  <option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
											  @endforeach
											</datalist>
										
										<span class="valid-feedback" id="update_client_idxError"></span>
									</div>
									<div class="col-md-4">
										<label for="update_delivered_to" class="form-label">Delivered To</label>
										<input type="text" class="form-control" id="update_delivered_to" name="update_delivered_to">
										<span class="valid-feedback" id="update_delivered_toError"></span>
									</div>
									
									<div class="col-md-4">
									  <label for="update_delivered_to_address" class="form-label">Delivered To Address</label>
									  <input type="text" class="form-control" id="update_delivered_to_address" name="update_delivered_to_address">
									  <span class="valid-feedback" id="update_delivered_to_addressError"></span>
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
									  <label for="update_sales_order_date" class="form-label">Delivery Method</label>
									  <input type="text" class="form-control" id="update_delivery_method" name="update_delivery_method">
									</div>
									
									<div class="col-md-4">
									  <label for="update_dr_number" class="form-label">Hauler</label>
									  <input type="text" class="form-control" id="update_hauler" name="update_hauler" >
									</div>
									
									<div class="col-md-4">
									  <label for="update_or_number" class="form-label">Required Date</label>
									  <input type="date" class="form-control" id="update_required_date" name="update_required_date" value="<?=date('Y-m-d');?>">
									</div>
								</div>
								<hr>
								<div class="row">
								
									<div class="col-md-6">
									  <label for="update_dr_number" class="form-label">Instructions</label>
									  <textarea class="form-control" id="update_instructions" name="update_instructions" style="height: 38px;"></textarea>
									</div>
									
									<div class="col-md-6">
									  <label for="update_or_number" class="form-label">Notes</label>
									  <textarea class="form-control" id="update_note" name="update_note" style="height: 38px;"></textarea>
									</div>
					
								</div>
								<hr>
								<h6 class="modal-title">Payment Details</h6>
								<div align="right">
								<button type="button" class="btn btn-success new_item bi bi-plus-square" onclick="UpdatePaymentRow();" title="Add a Paymnet Option(1-3 items)"></button>
								</div>
								<br>
								<table class="table" id="update_table_payment">
									<thead>
										<tr class='report'>
										<th style="text-align:center !important;">Mode of Payment</th>
										<th style="text-align:center !important;">Date of Payment</th>
										<th style="text-align:center !important;">Reference No.</th>
										<th style="text-align:center !important;">Amount</th>
										<th style="text-align:center !important;">Action</th>
										
										</tr>
									</thead>
										
									<tbody id="update_table_payment_body_data">
										 <tr style="display: none;"><td>HIDDEN</td></tr>
									</tbody>
									
								</table>
								<div style="color:red;" id="table_paymentxError"></div>
								
					</div>
					<div class="modal-footer modal-footer_form">
							<div id="upload_loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>						
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
				Date: <span id="confirm_delete_sales_order_date"></span><br>
				Control Number: <span id="confirm_delete_sales_control_number"></span><br>
				Client: <span id="confirm_delete_client_name"></span><br>
				DR Number: <span id="confirm_delete_dr_number"></span><br>
				OR Number: <span id="confirm_delete_or_number"></span><br>
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