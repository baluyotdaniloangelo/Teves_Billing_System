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
				
				<ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
				
				<li class="nav-item" role="presentation"  data-bs-toggle="modal">
                  <button class="nav-link active" id="quotation-tab" data-bs-toggle="tab" data-bs-target="#bordered-quotation" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1" title="From Sales Order">Quotation</button>
                </li>
				
                <li class="nav-item" role="presentation">
                  <button class="nav-link " id="pending-salesorder-tab" data-bs-toggle="tab" data-bs-target="#bordered-salesorder-pending" type="button" role="tab" aria-controls="home" aria-selected="true" title="Pending Sales Order">Pending</button>
                </li>
                <li class="nav-item" role="presentation"  data-bs-toggle="modal">
                  <button class="nav-link" id="billed-tab" data-bs-toggle="tab" data-bs-target="#bordered-billed" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1" title="From Sales Odrer">Delivered</button>
                </li>
				
              
				</ul>		
				
				
				<div class="tab-content pt-2" id="borderedTabContent">
				
				<div class="tab-pane fade show active" id="bordered-quotation" role="tabpanel" aria-labelledby="billed-tab">
				<div class="d-flex justify-content-end" id="">
				</div>
									<div class="table-responsive">
										
										<table class="table dataTable display nowrap cell-border" id="getSalesOrderList_quotation" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">No.</th>
													<th class="all">Date</th>
													<th class="all">S.O. No.</th>
													<th class="all">Account Name</th>
													<th class="none">Payment Term : </th>
													<th class="none">Gross Amount : </th>
													<th class="none">Withholding Tax : </th>
													<th class="none">Net Amount : </th>
													<th class="none">Total Due : </th>
													<th class="all">Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
												
										</table>
										
									</div>
				</div>
				
                <div class="tab-pane fade" id="bordered-salesorder-pending" role="tabpanel" aria-labelledby="pending-salesorder-tab">				
				
									<div class="table-responsive">
										<table class="table dataTable display nowrap cell-border" id="getSalesOrderList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">No.</th>
													<th class="all">Date</th>
													<th class="all">S.O. No.</th>
													<th class="all">Account Name</th>
													<th class="none">Payment Term : </th>
													<th class="none">Gross Amount : </th>
													<th class="none">Withholding Tax : </th>
													<th class="none">Net Amount : </th>
													<th class="none">Total Due : </th>
													<th class="all">Delivery Status</th>
													<th class="all">Payment Status</th>
													<th class="all">Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
										</table>
									</div>	
				
				</div>

				<div class="tab-pane fade" id="bordered-billed" role="tabpanel" aria-labelledby="billed-tab">
				<div class="d-flex justify-content-end" id="">
				</div>
									<div class="table-responsive">
										
										<table class="table dataTable display nowrap cell-border" id="getSalesOrderList_delivered" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">No.</th>
													<th class="all">Date</th>
													<th class="all">S.O. No.</th>
													<th class="all">Account Name</th>
													<th class="none">Payment Term : </th>
													<th class="none">Gross Amount : </th>
													<th class="none">Withholding Tax : </th>
													<th class="none">Net Amount : </th>
													<th class="none">Total Due : </th>
													<th class="all">Delivery Status</th>
													<th class="all">Payment Status</th>
													<th class="all">Action</th>
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


	<!--Modal to Create Sales Order-->
	<div class="modal fade" id="CreateSalesOrderModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Sales Order</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					<form class="g-2 needs-validation" id="SalesOrderformNew">		
								
								<div class="row">
										
										<div class="col-md-12">
										
											<label for="company_header" class="form-label">Branch</label>
											<select class="form-select form-control" required="" name="company_header" id="company_header">
											@foreach ($teves_branch as $teves_branch_cols)
												<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
											@endforeach
											</select>
											
										</div>
								</div>
								<hr>
								<div class="row">								
								
										<div class="col-md-12">
										  <label for="sales_order_date" class="form-label">Date</label>
										  <input type="date" class="form-control" id="sales_order_date" name="sales_order_date" value="<?=date('Y-m-d');?>" required>
										  <span class="valid-feedback" id="sales_order_dateError"></span>
										</div>
								
								</div>
								<hr>
								<div class="row">
										<div class="col-md-12">
											<label for="client_id" class="form-label">Sold To</label>
		
											 <input class="form-control" list="client_name" name="client_name" id="client_id" required autocomplete="off" onChange="ClientInfo()">
												<datalist id="client_name">
												  @foreach ($client_data as $client_data_cols)
												  <option label="{{$client_data_cols->client_name}}" data-id='{{$client_data_cols->client_id}}' value="{{$client_data_cols->client_name}}">
												  @endforeach
												</datalist>
																					
											<span class="valid-feedback" id="client_idxError"></span>
										</div>
								</div>
								<hr>
								<div class="row">
										
										<div class="col-md-6">
											<label for="sales_order_payment_type" class="form-label">Payment Type</label>
											<select class="form-select form-control" required="" name="sales_order_payment_type" id="sales_order_payment_type">
												<option value="Receivable">Receivable</option>
												<option value="PBD">Paid Before Delivery</option>
											</select>
										</div>
										<div class="col-md-6">
											<label for="sales_order_invoice" class="form-label">With Sales Invoice?</label>
											<select class="form-select form-control" required="" name="sales_order_invoice" id="sales_order_invoice" onchange="check_withholding_tax()">
												<option value="1" selected>Yes</option>
												<option value="0">No</option>
											</select>
										</div>
								</div>
								<hr>
								<div class="row">
										<div class="col-md-6">
											<label for="sales_order_net_percentage" class="form-label">Net Value</label>
											<input type="number" class="form-control" id="sales_order_net_percentage" name="sales_order_net_percentage" disabled>
										</div>
										<div class="col-md-6">
											<label for="sales_order_withholding_tax" class="form-label">Withholding Tax</label>
											<input type="number" class="form-control" id="sales_order_withholding_tax" name="sales_order_withholding_tax" disabled>
										</div>
								</div>
								<hr>
								<div class="row">
										<div class="col-md-12">
											<label for="payment_term" class="form-label">Payment Term</label>
											 <input type="text" class="form-control" id="payment_term" name="payment_term">
										</div>
								</div>
								<hr>
								<div class="row mb-2">
										<div class="col-md-12">
											<div class="form-check form-switch">
											  <input class="form-check-input sales_order_quotation" type="checkbox" id="sales_order_quotation" name="sales_order_quotation">
											  <label class="form-check-label" for="flexSwitchCheckDefault">Quotation?</label>
											</div>
										</div>
								</div>
								<div class="row mb-2">
									<div class="col-md-12">
											<div class="form-check form-switch">
												<input class="form-check-input sales_order_quotation_hide_volume" type="checkbox" id="sales_order_quotation_hide_volume" name="sales_order_quotation_hide_volume">
												<label class="form-check-label" for="flexSwitchCheckDefault">Hide Volume?</label>
											</div>
									</div>
								</div>
								
					</div>
					<div class="modal-footer modal-footer_form">
							<div id="loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
							<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-sales-order"> Submit</button>
							<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon"> Reset</button>
					</div>
					</form>		
                  </div>
              </div>
    </div>

	<!-- Sales Order Delete Modal-->
    <div class="modal fade" id="SalesOrderPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Sales Order - Product<br>
				</div>
				
				<div align="left"style="margin: 10px;">
				Date: <span id="preview_product_sales_order_date"></span><br>
				S.O. No.: <span id="preview_product_sales_control_number"></span><br>
				Client: <span id="preview_product_client_name"></span><br>
				DR Number: <span id="preview_product_dr_number"></span><br>
				OR Number: <span id="preview_product_or_number"></span><br>
				Total Due: <span id="preview_product_total_due"></span><br>
				
				<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;">Item #</th>
							<th style="text-align:left !important;">Description</th>
							<th style="text-align:right !important;">Price</th>
							<th style="text-align:right !important;">Quantity</th>
							<!--<th style="text-align:center !important;">Amount</th>	-->
						</tr>
						</thead>
							<tbody id="table_sales_order_product_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
				
				
				</div>
                <div class="modal-footer footer_modal_bg">
                 	<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Close</button>
                  
                </div>
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
				S.O. No: <span id="confirm_delete_sales_control_number"></span><br>
				Client: <span id="confirm_delete_client_name"></span><br>
				DR Number: <span id="confirm_delete_dr_number"></span><br>
				OR Number: <span id="confirm_delete_or_number"></span><br>
				Total Due: <span id="confirm_delete_total_due"></span><br>
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSalesOrderConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SalesOrderQuotationApprovalModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Approve This Quotation?<br>
				</div>
				
				<div align="left"style="margin: 10px;">
				Date: <span id="confirm_approval_sales_order_date"></span><br>
				S.O. No: <span id="confirm_approval_sales_control_number"></span><br>
				Client: <span id="confirm_approval_client_name"></span><br>
				DR Number: <span id="confirm_approval_dr_number"></span><br>
				OR Number: <span id="confirm_approval_or_number"></span><br>
				Total Due: <span id="confirm_approval_total_due"></span><br>
				
				
				</div>
				
                <div class="modal-footer footer_modal_bg">
                    <b style="color:#FFF;">Take note: After approval, this item will be moved to Pending and become visible on the Receivable List.</b>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="approval_SalesOrderConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Confirm</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SalesOrderQuotationDisapprovalModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Disapprove This Sales Order?<br>
				</div>
				
				<div align="left"style="margin: 10px;">
				Date: <span id="confirm_disapproval_sales_order_date"></span><br>
				S.O. No: <span id="confirm_disapproval_sales_control_number"></span><br>
				Client: <span id="confirm_disapproval_client_name"></span><br>
				DR Number: <span id="confirm_disapproval_dr_number"></span><br>
				OR Number: <span id="confirm_disapproval_or_number"></span><br>
				Total Due: <span id="confirm_disapproval_total_due"></span><br>
				
				
				</div>
				
                <div class="modal-footer footer_modal_bg">
                    <b style="color:#FFF;">Take note: After disapproval, this item will be moved to Quotation List.</b>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="disapproval_SalesOrderConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Confirm</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
	
 	<!--Move Sales Order to  Receivables-->
	<div class="modal fade" id="SalesOrderDeliveredModal" tabindex="-1">
	
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Receivable</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
						<div class="row">
					<div class="col-lg-4">
					  
					  <ol class="list-group list-group-numbered">
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Client</div>
							<div id="client_name_receivables"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Address</div>
							<div id="client_address_receivables"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">TIN</div>
							<div id="client_tin_receivables"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Total Due</div>
							<div id="amount_receivables"></div>
						  </div>
						 
						</li>
						
					  </ol>					
					
					</div>
					<div class="col-lg-8">
									
					  <form class="g-2 needs-validation pt-4" id="ReceivableformAddFromSalesOrder">
						
						<div class="row mb-2">
						  <label for="receivable_billing_date" class="col-sm-3 col-form-label">Billing Date : </label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="receivable_billing_date" id="receivable_billing_date" value="" required>
							<span class="valid-feedback" id="receivable_billing_dateError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="receivable_payment_term" class="col-sm-3 col-form-label">Payment Term : </label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="receivable_payment_term" id="receivable_payment_term" value="">
							<span class="valid-feedback" id="receivable_payment_termError"></span>
						  </div>
						</div>							
						
						<div class="row mb-2">
						  <label for="receivable_description" class="col-sm-3 col-form-label">Description : </label>
						  <div class="col-sm-9">
							<textarea class="form-control" id="receivable_description" style="height: 50px;" required></textarea>
							<span class="valid-feedback" id="receivable_descriptionError"></span>
						  </div>
						</div>
						
						</div>
						
					</div>
					</div>
                    <div class="modal-footer modal-footer_form">
					Note: Once this Item moved to receivables, Status will no longer editable.
							<div id="update_loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="add-to-receivables"> Submit</button>
						  <!--<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon"> Reset</button>-->
						  
					</div>
					
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
  </section>
</main>
@endsection