@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="receivable_option"></div>					  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									
				<ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="billing-tab" data-bs-toggle="tab" data-bs-target="#bordered-billing" type="button" role="tab" aria-controls="home" aria-selected="true" title="From Billing">Billing</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="salesorder-tab" data-bs-toggle="tab" data-bs-target="#bordered-salesorder" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1" title="From Sales Oder">Sales Order</button>
                </li>
              
				</ul>					
				
				<div class="tab-content pt-2" id="borderedTabContent">
				
                <div class="tab-pane fade show active" id="bordered-billing" role="tabpanel" aria-labelledby="billing-tab">
											<div class="table-responsive" style="">
												<table class="table dataTable display nowrap cell-border" id="getReceivablesListBilling" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th class="all">No.</th>
														<th class="all">Date</th>
														<th class="none">Start Date:</th>
														<th class="none">End Date:</th>
														<th class="all">Control Number</th>
														<th class="all">Account Name</th>
														<th class="none">Description : </th>
														<th class="none">Total Sales : </th>
														<th class="none">Withholding Tax : </th>
														<th class="none">Total Amount Due : </th>
														<th class="none">Total Amount Rendered : </th>
														<th class="none">Remaining Balance : </th>
														<th class="all">Status</th>
														<th class="all">Generate</th>
														<th class="all">Action</th>
													</tr>
												</thead>				
												
												<tbody>
													
												</tbody>
												
												</table>  
											</div>
				</div>
				
                <div class="tab-pane fade" id="bordered-salesorder" role="tabpanel" aria-labelledby="salesorder-tab">
											<div class="table-responsive" style="">
												<table class="table dataTable display  cell-border" id="getReceivablesListSales" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th class="all">No.</th>
														<th class="all">Date</th>
														<th class="all">Control Number</th>
														<th class="all">Account Name</th>
														<th class="none">Description : </th>
														<th class="none">Total Sales : </th>
														<th class="none">Withholding Tax : </th>
														<th class="none">Total Amount Due : </th>
														<th class="none">Total Amount Rendered : </th>
														<th class="none">Remaining Balance : </th>
														<!--<th class="all">Status</th>-->
														<th class="all">Payment Status</th>
														<th class="all">Delivery Status</th>
														<th class="all">Generate</th>
														<th class="all" nowrap>Action</th>
													</tr>
												</thead>				
												
												<tbody>
													
												</tbody>
												
												
											</table>
										</div>
				</div>
				
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
				<!--OR Number: <span id="confirm_delete_or_no"></span><br>-->
				Client: <span id="confirm_delete_client_info"></span><br>
				Description: <span id="confirm_delete_description"></span><br>
				Amount: <span id="confirm_delete_amount"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteReceivableConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
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
			


<!--   fdfff -->	
	<div class="modal fade" id="BillingReceivableLockModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Lock Status</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					   <form class="g-3 needs-validation" id="BillingReceivableLockStatusForm">
					  
						<ul class="list-group list-group-flush">
						
							<li class="list-group-item"><b>Date:</b>&nbsp;<span id="lock_receivable_billing_date" style="font-weight: normal;"></span></li>
							<li class="list-group-item"><b>Control Number:</b>&nbsp;<span id="lock_receivable_confirm_control_number" style="font-weight: normal;"></span></li>
							<li class="list-group-item"><b>Client:</b>&nbsp;<span id="lock_receivable_client_info" style="font-weight: normal;"></span></li>
							<li class="list-group-item"><b>Amount:</b>&nbsp;<span id="lock_receivable_amount" style="font-weight: normal;"></span></li>
						
						</ul>
						<br>
						<div class="row mb-2">
						  <div class="col-sm-12">
							<div class="form-check form-switch">
							  <input class="form-check-input lock_billing_information" type="checkbox" id="lock_billing_information" name="lock_billing_information">
							  <label class="form-check-label" for="flexSwitchCheckDefault">Receivable Information</label>
							</div>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <div class="col-sm-12">
							<div class="form-check form-switch">
							  <input class="form-check-input lock_billing_item" type="checkbox" id="lock_billing_item" name="lock_billing_item">
							  <label class="form-check-label" for="flexSwitchCheckDefault">Billing</label>
							</div>
						  </div>
						</div>

						<div class="row mb-2">
						  <div class="col-sm-12">
							<div class="form-check form-switch">
							  <input class="form-check-input lock_billing_payment_item" type="checkbox" id="lock_billing_payment_item" name="lock_billing_payment_item">
							  <label class="form-check-label" for="flexSwitchCheckDefault">Payment</label>
							</div>
						  </div>
						</div>
						
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="datetime-local" class="form-control" id="receivable_unlock_expiration" name="receivable_unlock_expiration" value="" required>
								<label for="receivable_unlock_expiration">Auto Lock at</label>
								<span class="valid-feedback" id="receivable_unlock_expirationError"></span>
							</div>
						 
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <b>Take Note:Upon unlocking the above setting, this may prevent the user from editing or deleting those items.</b>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="LockConfirmed" value=""> Confirm</button>	
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>	
	
	
	
	
	</section>
</main>
@endsection

