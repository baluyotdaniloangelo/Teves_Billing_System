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
													<th>#</th>
													<th>Date</th>
													<th>Control Number</th>
													<th>Client</th>
													<th>D.R. Number</th>
													<th>O.R. Number</th>
													<th>Payment Term</th>
													<th>Total Due</th>
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
													<th>Client</th>
													<th>D.R. Number</th>
													<th>O.R. Number</th>
													<th>Payment Term</th>
													<th>Total Due</th>
													<th>Action</th>												
												</tr>
											</tfoot>	
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!-- Site Delete Modal-->
    <div class="modal fade" id="ReceivableDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Receivable?<br>
				</div>
				
				<div align="left"style="margin: 10px;">
				
				Billing Date: <span id="confirm_delete_billing_date"></span><br>
				Control Number: <span id="confirm_delete_control_number"></span><br>
				OR Number: <span id="confirm_delete_or_no"></span><br>
				Client: <span id="confirm_delete_client_info"></span><br>
				Description: <span id="confirm_delete_description"></span><br>
				Amount: <span id="confirm_delete_amount"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteReceivableConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	

<div class="modal fade" id="CreateSalesOrderModal" tabindex="-1">
				<form class="g-2 needs-validation" id="SalesOrderformNew">
                <div class="modal-dialog modal-fullscreen">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Create Receivable</h5>
                      <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
					
                    <div class="modal-body">
						<div class="row">
							
							<div class="col-lg-6">
									<div class="row">
									<div class="col-md-6">
										<label for="" class="form-label">Client</label>
										<select class="form-control form-select " name="client_idx" id="client_idx" required>
											<option selected="" disabled="" value="">Choose...</option>
												@foreach ($client_data as $client_data_cols)
											<option value="{{$client_data_cols->client_id}}">{{$client_data_cols->client_name}}</option>
												@endforeach
										</select>
										<span class="valid-feedback" id="client_idxError"></span>
									</div>
									
									<div class="col-md-6">
									  <label for="sales_order_date" class="form-label">Date</label>
									  <input type="date" class="form-control" id="sales_order_date" name="sales_order_date" value="<?=date('Y-m-d');?>">
									</div>
									
								</div>
								<hr>
								<div class="col-lg-6">
									<div class="row">
									<div class="col-md-6">
										<label for="" class="form-label">Delivered To</label>
										<input type="text" class="form-control" id="delivered_to" name="delivered_to">
										<span class="valid-feedback" id="client_idxError"></span>
									</div>
									
									<div class="col-md-6">
									  <label for="sales_order_date" class="form-label">Delivered To Address</label>
									  <input type="text" class="form-control" id="delivered_to_address" name="delivered_to_address">
									</div>
									
								</div>
								<hr>
								<div class="row">
									
									<div class="col-md-4">
									  <label for="dr_number" class="form-label">D.R Number</label>
									  <input type="text" class="form-control" id="dr_number" name="dr_number" >
									</div>
									
									<div class="col-md-4">
									  <label for="or_number" class="form-label">O.R Number</label>
									  <input type="text" class="form-control" id="or_number" name="or_number" >
									</div>
									
									<div class="col-md-4">
									  <label for="payment_term" class="form-label">Payment Term</label>
									  <input type="text" class="form-control" id="payment_term" name="payment_term">
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
									  <textarea class="form-control" id="instructions" name="instructions" style="height: 50px;"></textarea>
									</div>
									
									<div class="col-md-6">
									  <label for="or_number" class="form-label">Notes</label>
									  <textarea class="form-control" id="note" name="note" style="height: 50px;"></textarea>
									</div>
					
								</div>
								<hr>
								<div class="row">
								
									<div class="col-md-3">
									  <label for="sales_order_date" class="form-label">Mode of Payment</label>
									  <input type="text" class="form-control" id="mode_of_payment" name="mode_of_payment">
									</div>
									<div class="col-md-3">
									  <label for="sales_order_date" class="form-label">Date of Payment</label>
									  <input type="text" class="form-control" id="date_of_payment" name="date_of_payment" value="<?=date('Y-m-d');?>">
									</div>
									
									<div class="col-md-3">
									  <label for="sales_order_date" class="form-label">Reference No.</label>
									  <input type="text" class="form-control" id="reference_no" name="reference_no">
									</div>
									
									<div class="col-md-3">
									  <label for="sales_order_date" class="form-label">Amount</label>
									  <input type="text" class="form-control" id="payment_amount" name="payment_amount">
									</div>
								
								</div>
								<hr>
								<br>
								<br>
							</div>
							
							<div class="col-lg-6">
								<div align="right"><input type="button" value="Add" onclick="AddProductRow();" class="btn btn-primary"/></div>
								<hr>
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
							</div>
						</div>
                    </div>
                    <div class="modal-footer">
						<button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-sales-order"> Submit</button>
						<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon"> Reset</button>
                    </div>
					
                  </div>
                </div>
				</form>
              </div>
    </section>
</main>
@endsection