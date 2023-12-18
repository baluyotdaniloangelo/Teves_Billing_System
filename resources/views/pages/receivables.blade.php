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
									<div class="table-responsive" style="">
										<table class="table table-bordered dataTable display" id="getReceivablesList" width="100%" cellspacing="0">
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
													<th class="all">Status</th>
													<th class="all">Generate</th>
													<th class="all">Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
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
													<th class="all">Status</th>
													<th class="all">Generate</th>
													<th class="all">Action</th>												
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

	<!--Modal to Update Receivables-->
	<div class="modal fade" id="UpdateReceivablesModal" tabindex="-1">
	
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Receivable</h5>
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
						  
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Billing Date</div>
							<div id="billing_receivables"></div>
						  </div>
						 
						</li>
						
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Control No.</div>
							<div id="control_no_receivables"></div>
						  </div>
						 
						</li>
						
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
									
					  <form class="g-2 needs-validation pt-4" id="ReceivableformEdit">
						
						<div class="row mb-2">
						  <label for="billing_date" class="col-sm-3 col-form-label">Header/Company : </label>
						  <div class="col-sm-9">
							<select class="form-select form-control" required="" name="company_header" id="company_header">
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_name}} | {{$teves_branch_cols->branch_tin}}</option>
							@endforeach		
							</select>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="billing_date" class="col-sm-3 col-form-label">Billing Date : </label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="billing_date" id="billing_date" value="" required>
							<span class="valid-feedback" id="billing_dateError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="start_date" class="col-sm-3 col-form-label">Period:</label>
						  <div class="col-sm-9">
						  
						  <div class="input-group">
									<input type="date" class="form-control" name="start_date" id="start_date" required readonly>
									<input type="date" class="form-control" name="end_date" id="end_date" required readonly>
							<span class="valid-feedback" id="end_dateError"></span>
							<span class="valid-feedback" id="start_dateError"></span>		
							</div>

						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="less_per_liter" class="col-sm-3 col-form-label" title="Applicable to All Product with Liter as a unit of measurement">Discount Per Liter</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="less_per_liter" id="less_per_liter" value="" required>
							<span class="valid-feedback" id="less_per_literError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="less_per_liter" class="col-sm-3 col-form-label" title="Applicable to All Product with Liter as a unit of measurement">Net Value</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="net_value_percentage" id="net_value_percentage" value="1.12">
							<span class="valid-feedback" id=""></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="less_per_liter" class="col-sm-3 col-form-label" title="Applicable to All Product with Liter as a unit of measurement">VAT Value</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="vat_value_percentage" id="vat_value_percentage" value="12">
							<span class="valid-feedback" id=""></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="less_per_liter" class="col-sm-3 col-form-label" title="Applicable to All Product with Liter as a unit of measurement">Withholding Tax</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="withholding_tax_percentage" id="withholding_tax_percentage" value="1">
							<span class="valid-feedback" id="less_per_literError"></span>
						  </div>
						</div>						
						
						<div class="row mb-2">
						  <label for="payment_term" class="col-sm-3 col-form-label">Payment Term : </label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="payment_term" id="payment_term" value="">
							<span class="valid-feedback" id="payment_termError"></span>
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
							<div id="update_loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-receivables"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
			 
<div class="modal fade" id="PayReceivablesModal" tabindex="-1">
	
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Payment</h5>
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
							<div id="Pay_client_name_receivables"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Address</div>
							<div id="Pay_client_address_receivables"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">TIN</div>
							<div id="Pay_client_tin_receivables"></div>
						  </div>
						 
						</li>
						  
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Billing Date</div>
							<div id="Pay_billing_receivables"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Billing Period</div>
							<div id="Pay_billing_period"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Control No.</div>
							<div id="Pay_control_no_receivables"></div>
						  </div>
						 
						</li>
						
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Total Due</div>
							<div id="Pay_amount_receivables"></div>
						  </div>
						 
						</li>
						
					  </ol>					
					
					</div>
					<div class="col-lg-8">
								<div align="right">
								<button type="button" class="btn btn-success new_item bi bi-plus-square" onclick="NewPaymentRow();" title="Add a Paymnet Option(1-3 items)"></button>
								</div>
								<br>
								<table class="table" id="receivable_payment_table">
									<thead>
										<tr class='report'>
										
										<th style="text-align:center !important;">Date</th>
										<th style="text-align:center !important;">Mode of Payment</th>
										<th style="text-align:center !important;">Reference No.</th>
										<th style="text-align:center !important;">Amount</th>
										<th style="text-align:center !important;">Action</th>
										</tr>
									</thead>
										
									<tbody id="receivable_payment_table_body_data">
										 <tr style="display: none;"><td>HIDDEN</td></tr>
									</tbody>
									
								</table>
								<div style="color:red;" id="table_paymentxError"></div>
					  
					</div>
						
					</div>
					</div>
					
                    <div class="modal-footer modal-footer_form">
						<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-receivables-payment" value=""> Submit</button>
						<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon"> Reset</button>	
					</div>
					<!-- End Multi Columns Form -->
                  
				  </div>
                </div>
             </div>
			


 	<!--Move Sales Order to  Receivables-->
	<div class="modal fade" id="UpdateReceivablesFromSalesOrderModal" tabindex="-1">
	
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Receivable(From Sales Order)</h5>
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
							<div id="client_name_receivables_SO"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Address</div>
							<div id="client_address_receivables_SO"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">TIN</div>
							<div id="client_tin_receivables_SO"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">CONTROL NO.</div>
							<div id="control_no_receivables_SO"></div>
						  </div>
						 
						</li>
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Total Due</div>
							<div id="amount_receivables_SO"></div>
						  </div>
						 
						</li>
						
					  </ol>					
					
					</div>
					<div class="col-lg-8">
									
					  <form class="g-2 needs-validation pt-4" id="ReceivableformEditFromSalesOrder">
						
						<div class="row mb-2">
						  <label for="receivable_billing_date_SO" class="col-sm-3 col-form-label">Billing Date : </label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="receivable_billing_date_SO" id="receivable_billing_date_SO" value="" required>
							<span class="valid-feedback" id="receivable_billing_date_SO_Error"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="receivable_payment_term_SO" class="col-sm-3 col-form-label">Payment Term : </label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="receivable_payment_term_SO" id="receivable_payment_term_SO" value="">
							<span class="valid-feedback" id="receivable_payment_term_SO_Error"></span>
						  </div>
						</div>							
						
						<div class="row mb-2">
						  <label for="receivable_description_SO" class="col-sm-3 col-form-label">Description : </label>
						  <div class="col-sm-9">
							<textarea class="form-control" id="receivable_description_SO" style="height: 50px;" required></textarea>
							<span class="valid-feedback" id="receivable_description_SO_Error"></span>
						  </div>
						</div>
						
						</div>
						
					</div>
					</div>
                    <div class="modal-footer modal-footer_form">
							<div id="update_loading_data_SO" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="SO-update-receivables"> Submit</button>
						  <!--<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon"> Reset</button>-->
						  
					</div>
					
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>			
    </section>
</main>
@endsection

