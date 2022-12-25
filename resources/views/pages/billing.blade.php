@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="billing_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="getBillingTransactionList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Time</th>
													<th>Driver's Name</th>
													<th>P.O. No.</th>
													<th>Plate Number</th>																	
													<th>Product</th>
													<th>Price</th>
													<th>Quantity</th>
													<th>Amount</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
	
											<tfoot>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Time</th>
													<th>Driver's Name</th>
													<th>P.O. No.</th>
													<th>Plate Number</th>																	
													<th>Product</th>
													<th>Price</th>
													<th>Quantity</th>
													<th>Amount</th>
													<th>Action</th>
												</tr>
											</tfoot>
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!-- Site Delete Modal-->
    <div class="modal fade" id="BillDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Bill?<br>
				</div>
				<div align="left"style="margin: 10px;">
				Date: <span id="bill_delete_order_date"></span><br>
				Time: <span id="bill_delete_order_time"></span><br>
				
				PO #: <span id="bill_delete_order_po_number"></span><br>
				Client: <span id="bill_delete_client_name"></span><br>
				
				Plate #: <span id="bill_delete_plate_no"></span><br>
				Driver: <span id="bill_delete_drivers_name"></span><br>
				
				Product: <span id="bill_delete_product_name"></span><br>
				Quantity: <span id="bill_delete_order_quantity"></span><br>
				
				Total Amount: <span id="bill_delete_order_total_amount"></span><br>
				
				
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteBillConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	

	<!--Modal to Create Bill-->
	<div class="modal fade" id="CreateBillingModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Bill</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="BillingformNew">
					  
						<div class="row mb-2">
						  <label for="order_date" class="col-sm-3 col-form-label">Date</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control" name="order_date" id="order_date" value="<?=date('Y-m-d');?>" required>
							<span class="valid-feedback" id="order_dateError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="order_time" class="col-sm-3 col-form-label">Time</label>
						  <div class="col-sm-9">
							<input type="time" class="form-control " name="order_time" id="order_time" value="<?=date('H:i a');?>" required>
							<span class="valid-feedback" id="order_timeError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="order_po_number" class="col-sm-3 col-form-label">PO Number</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="order_po_number" id="order_po_number" value="" required>
							<span class="valid-feedback" id="order_po_numberError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="client_idx" class="col-sm-3 col-form-label">Client</label>
						  <div class="col-sm-9">
							<select class="form-control form-select " name="client_idx" id="client_idx" required>
							<option selected="" disabled="" value="">Choose...</option>
								@foreach ($client_data as $client_data_cols)
									<option value="{{$client_data_cols->client_id}}">{{$client_data_cols->client_name}}</option>
								@endforeach
							</select>
							<span class="valid-feedback" id="client_idxError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="plate_no" class="col-sm-3 col-form-label">Plate Number</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="plate_no" id="plate_no" value="" required>
							<span class="valid-feedback" id="plate_noError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="drivers_name" class="col-sm-3 col-form-label">Drivers Name</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="drivers_name" id="drivers_name" value="" required>
							<span class="valid-feedback" id="drivers_nameError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="product_idx" class="col-sm-3 col-form-label">Product</label>
						  <div class="col-sm-9">
							<select class="form-control form-select" aria-label="Product" name="product_idx" id="product_idx" required>
							  <option selected="" disabled="" value="">Choose...</option>
								@foreach ($product_data as $product_data_cols)
									<option value="{{$product_data_cols->product_id}}">{{$product_data_cols->product_name}}</option>
								@endforeach
							  </select>
							 <span class="valid-feedback" id="product_idxError"></span>
						</div>
						</div>	
						
						<div class="row mb-2">
						  <label for="order_quantity" class="col-sm-3 col-form-label">Quantity</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="order_quantity" id="order_quantity" required>
							  <span class="valid-feedback" id="order_quantityError"></span>
						  </div>
						</div>							
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-billing-transaction"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-billing"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	
	
	<!--Modal to Upadate Site-->
	<div class="modal fade" id="UpdateBillingModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Bill</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="BillingformEdit">
					  
						<div class="row mb-2">
						  <label for="update_order_date" class="col-sm-3 col-form-label">Date</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control" name="update_order_date" id="update_order_date" value="" required>
							<span class="valid-feedback" id="update_order_dateError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_order_time" class="col-sm-3 col-form-label">Time</label>
						  <div class="col-sm-9">
							<input type="time" class="form-control " name="update_order_time" id="update_order_time" value="" required>
							<span class="valid-feedback" id="update_order_timeError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_order_po_number" class="col-sm-3 col-form-label">PO Number</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_order_po_number" id="update_order_po_number" value="" required>
							<span class="valid-feedback" id="update_order_po_numberError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_client_idx" class="col-sm-3 col-form-label">Client</label>
						  <div class="col-sm-9">
							<select class="form-control form-select " name="update_client_idx" id="update_client_idx" required>
							<option selected="" disabled="" value="">Choose...</option>
								@foreach ($client_data as $client_data_cols)
									<option value="{{$client_data_cols->client_id}}">{{$client_data_cols->client_name}}</option>
								@endforeach
							</select>
							<span class="valid-feedback" id="update_client_idxError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_plate_no" class="col-sm-3 col-form-label">Plate Number</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_plate_no" id="update_plate_no" value="" required>
							<span class="valid-feedback" id="update_plate_noError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_drivers_name" class="col-sm-3 col-form-label">Drivers Name</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_drivers_name" id="update_drivers_name" value="" required>
							<span class="valid-feedback" id="update_drivers_nameError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_product_idx" class="col-sm-3 col-form-label">Product</label>
						  <div class="col-sm-9">
							<select class="form-control form-select" aria-label="Product" name="update_product_idx" id="update_product_idx" required>
							  <option selected="" disabled="" value="">Choose...</option>
								@foreach ($product_data as $product_data_cols)
									<option value="{{$product_data_cols->product_id}}">{{$product_data_cols->product_name}}</option>
								@endforeach
							  </select>
							 <span class="valid-feedback" id="update_product_idxError"></span>
						</div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_order_quantity" class="col-sm-3 col-form-label">Quantity</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="update_order_quantity" id="update_order_quantity" required>
							  <span class="valid-feedback" id="update_order_quantityError"></span>
						  </div>
						</div>							
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-billing-transaction"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-billing"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	

    </section>
</main>


@endsection

