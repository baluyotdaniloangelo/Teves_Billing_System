@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">

        <div class="row mb-2">
			
			<div class="col-sm-4">
			
			<div class="card">
            <div class="card-body">
				<h5 class="card-title" align="center">Update Sales Order Information</h5>
				<hr>
				<form class="g-3 needs-validation" id="UpdateSalesOrderformUpdate">
					
												<div class="row mb-2">
												  <div class="col-sm-12">
												  <label for="sales_order_date" class="form-label">Date</label>
													<input type="date" class="form-control" id="sales_order_date" name="sales_order_date" value="{{ $sales_order_data[0]['sales_order_date'] }}" required>
													<span class="valid-feedback" id="sales_order_dateError"></span>
												  </div>
												</div>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
												   <label for="company_header" class="form-label">Branch</label>
												 	<select class="form-select form-control" required="" name="company_header" id="company_header" onchange="UpdateBranch()">
													<?php $branch_idx = $sales_order_data[0]['company_header']; ?>
										
													@foreach ($teves_branch as $teves_branch_cols)
													<?php 
													$branch_id = $teves_branch_cols->branch_id;
													
													?>
													<option value="{{$teves_branch_cols->branch_id}}" <?php if($branch_id==$branch_idx){ echo "selected";} else{} ?>>
														{{$teves_branch_cols->branch_code}}
													</option>
													
													@endforeach
													
									
													</select>
												  </div>
												</div>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
												   <label for="client_id" class="form-label">Sold To</label>
													<input class="form-control" list="client_name" name="client_name" id="client_id" required autocomplete="off" value="{{ $sales_order_data[0]['client_name'] }}" onchange="ClientInfo()">
														<datalist id="client_name">
														  @foreach ($client_data as $client_data_cols)
														  <option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
														  @endforeach
														</datalist>							
													<span class="valid-feedback" id="client_idxError"></span>
												  </div>
												</div>

												<hr>
												
												<div class="row mb-2">
									  
														<label for="sales_order_net_percentage" class="col-sm-3 col-form-label">Net Value</label>
														<div class="col-sm-9">			  
															<input type="number" class="form-control" id="sales_order_net_percentage" name="sales_order_net_percentage" value="{{ $sales_order_data[0]['sales_order_net_percentage'] }}" >
														</div>
												
												</div>
												<div class="row mb-2">
									  
														<label for="sales_order_less_percentage" class="col-sm-3 col-form-label">Less Value</label>
														<div class="col-sm-9">			  
															<input type="number" class="form-control" id="sales_order_less_percentage" name="sales_order_less_percentage" value="{{ $sales_order_data[0]['sales_order_less_percentage'] }}" >
														</div>
												
												</div>
												
												<hr>
												<div class="row mb-2">
												  <label for="dr_number" class="col-sm-3 col-form-label">D.R Number</label>
												  <div class="col-sm-9">			  
													<input type="text" class="form-control" id="dr_number" name="dr_number" value="{{ $sales_order_data[0]['sales_order_dr_number'] }}" >
												  </div>
												</div>
												
												<div class="row mb-2">
													<label for="or_number" class="col-sm-3 col-form-label">P.O Number</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="or_number" name="or_number" value="{{ $sales_order_data[0]['sales_order_or_number'] }}" >
													</div>
												</div>
												
												<div class="row mb-2">
													<label for="or_number" class="col-sm-3 col-form-label">Payment Term</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="payment_term" name="payment_term" value="{{ $sales_order_data[0]['sales_order_payment_term'] }}" >
													</div>
												</div>
												<hr>
												<div class="row mb-2">
													
													<div class="col-sm-12">
													<label for="delivered_to" class="form-label">Delivered To</label>
													<input type="text" class="form-control" id="delivered_to" name="delivered_to" list="sales_order_delivered_to_list" value="{{ $sales_order_data[0]['sales_order_delivered_to'] }}">
														<datalist id="sales_order_delivered_to_list">
															@foreach ($sales_order_delivered_to as $sales_order_delivered_to_cols)
																<option value="{{$sales_order_delivered_to_cols->sales_order_delivered_to}}">
															@endforeach
														  </datalist>
													<span class="valid-feedback" id="delivered_toError"></span>
													</div>
												</div>
												
												<div class="row mb-2">
												  
												  <div class="col-sm-12">
												  <label for="delivered_to_address" class="form-label">Delivered To Address</label>
												  <input type="text" class="form-control" id="delivered_to_address" name="delivered_to_address" list="delivered_to_address_list" value="{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}">
														<datalist id="delivered_to_address_list">
															@foreach ($sales_order_delivered_to_address as $sales_order_delivered_to_address_cols)
																<option value="{{$sales_order_delivered_to_address_cols->sales_order_delivered_to_address}}">
															@endforeach
														  </datalist>
												  <span class="valid-feedback" id="delivered_to_addressError"></span>
												  </div>
												</div>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
												  <label for="sales_order_date" class="form-label">Delivery Method</label>
													<input type="text" class="form-control" id="delivery_method" name="delivery_method" value="{{ $sales_order_data[0]['sales_order_delivery_method'] }}">
												  </div>
												</div>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
												  <label for="hauler" class="form-label">Hauler</label>
												  <input type="text" class="form-control" id="hauler" name="hauler" value="{{ $sales_order_data[0]['sales_order_hauler'] }}">
												  </div>
												</div>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
												  <label for="required_date" class="form-label">Required Date</label>
												  <input type="date" class="form-control" id="required_date" name="required_date" value="{{ $sales_order_data[0]['sales_order_required_date'] }}">
												  </div>
												</div>
												
												<hr>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
													<label for="instructions" class="form-label">Instructions</label>
													<textarea class="form-control" id="instructions" name="instructions" style="height: 38px;">{{ $sales_order_data[0]['sales_order_instructions'] }}</textarea>
												  </div>
												  
												  
												</div>
												
												<div class="row mb-2">
												  <div class="col-sm-12">
													<label for="note" class="form-label">Notes</label>
													<textarea class="form-control" id="note" name="note" style="height: 38px;">{{ $sales_order_data[0]['sales_order_note'] }}</textarea>
												  </div>
												</div>													
				</form>
             
            </div>
            <div class="card-footer">
												<div class="row mb-3">
												<div class="col-sm-6" align=''>
												<div id="loading_data_update_so" style="display:none;">
													<div class="spinner-border text-success" role="status">
														<span class="visually-hidden">Loading...</span>
													</div>
												</div>
												</div>
												<div class="col-sm-6" align='right'>
												<a class="btn btn-secondary btn-sm new_item bi bi-chevron-double-left form_button_icon" href="{{ route('salesorder') }}" title="Back">  
												  <span title="Back to Sales Order List">Back</span>
												</a>
												<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-sales-order" title='Update Sales Order information'> Update</button>
												</div>
												</div>	
            </div>
          </div>
			</div>
			<div class="col-sm-8">
			
			<div class="card">
            <div class="card-body">
              <h5 class="card-title">Product</h5>
				<div class="d-flex justify-content-end" id="">
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<a class="btn btn-secondary new_item bi bi-chevron-double-left form_button_icon" href="{{ route('salesorder') }}" title="Back">  
						  <span title="Back to Sales Order List">Back</span>
						</a>
						<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductModal" id="AddSalesOrderProductBTN">&nbsp;Add</button>
						<button type="button" class="btn btn-dark new_item bi-printer-fill form_button_icon" id="PrintSalesOrder">&nbsp;Print</button>
						<!--<button type="button" class="btn btn-success new_item bi bi-printer" onclick="#"></button>-->
						
					</div>					
				  </div>

			<table class="table table-striped" id="">
			<thead>
					<tr class='report'>
						<th style="text-align:center !important;">Action</th>
						<th style="text-align:center !important;">Item #</th>
						<th style="text-align:center !important;">Description</th>
						<th style="text-align:center !important;">Price</th>
						<th style="text-align:center !important;">Quantity</th>
						<th style="text-align:center !important;">Unit</th>
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
		@foreach ($product_data as $product_data_cols)
			<span style="font-family: DejaVu Sans; sans-serif;">
				<option label="&#8369; {{$product_data_cols->product_price}} | {{$product_data_cols->product_name}}" data-id="{{$product_data_cols->product_id}}" data-price="{{$product_data_cols->product_price}}" value="{{$product_data_cols->product_name}}">
			</span>
		@endforeach
	</datalist>		
    </section>
</main>


@endsection

