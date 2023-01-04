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
	
	
<div class="modal fade" id="CreateSalesOrderModal" tabindex="-1" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Report</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
                     <form class="row g-3 needs-validation">
					   
							<div class="col-md-4">
								<label for="" class="form-label">Client</label>
								<select class="form-control form-select " name="client_idx" id="client_idx" required>
									<option selected="" disabled="" value="">Choose...</option>
										@foreach ($client_data as $client_data_cols)
									<option value="{{$client_data_cols->client_id}}">{{$client_data_cols->client_name}}</option>
										@endforeach
								</select>
								<span class="valid-feedback" id="client_idxError"></span>
							</div>
							
							<div class="col-md-2">
							  <label for="sales_order_date" class="form-label">Date</label>
							  <input type="date" class="form-control" id="sales_order_date" name="sales_order_date">
							</div>
							
							<div class="col-md-2">
							  <label for="dr_number" class="form-label">D.R Number</label>
							  <input type="text" class="form-control" id="dr_number" name="dr_number" >
							</div>
							
							<div class="col-md-2">
							  <label for="or_number" class="form-label">O.R Number</label>
							  <input type="text" class="form-control" id="or_number" name="or_number" >
							</div>
							
							<div class="col-md-2">
							  <label for="payment_term" class="form-label">Payment Term</label>
							  <input type="text" class="form-control" id="payment_term" name="payment_term">
							</div>
							
							<div class="col-md-12">
							  /*Item List Selection of Product*/
							</div>

							<div class="col-md-4">
							  <label for="sales_order_date" class="form-label">Delivery Method</label>
							  <input type="text" class="form-control" id="sales_order_date" name="sales_order_date">
							</div>
							
							<div class="col-md-4">
							  <label for="dr_number" class="form-label">Hauler</label>
							  <input type="text" class="form-control" id="dr_number" name="dr_number" >
							</div>
							
							<div class="col-md-4">
							  <label for="or_number" class="form-label">Required Date</label>
							  <input type="date" class="form-control" id="or_number" name="or_number" >
							</div>
							
							<div class="col-md-6">
							  <label for="dr_number" class="form-label">Instructions</label>
							  <textarea class="form-control" id="receivable_description" style="height: 50px;" required></textarea>
							</div>
							
							<div class="col-md-6">
							  <label for="or_number" class="form-label">Notes</label>
							  <textarea class="form-control" id="receivable_description" style="height: 50px;" required></textarea>
							</div>
							
							
							<div class="col-md-3">
							  <label for="sales_order_date" class="form-label">Mode of Payment</label>
							  <input type="text" class="form-control" id="sales_order_date" name="sales_order_date">
							</div>
							
							<div class="col-md-3">
							  <label for="sales_order_date" class="form-label">Mode of Payment</label>
							  <input type="text" class="form-control" id="sales_order_date" name="sales_order_date">
							</div>
							
							<div class="col-md-3">
							  <label for="sales_order_date" class="form-label">Reference No.</label>
							  <input type="text" class="form-control" id="sales_order_date" name="sales_order_date">
							</div>
							
							<div class="col-md-3">
							  <label for="sales_order_date" class="form-label">Amount</label>
							  <input type="text" class="form-control" id="sales_order_date" name="sales_order_date">
							</div>
							
							</form>
                    </div>
                    <div class="modal-footer modal-footer_form">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>


	
	<!--Modal to Create Sales Order-->
	
	

    </section>
</main>


@endsection

