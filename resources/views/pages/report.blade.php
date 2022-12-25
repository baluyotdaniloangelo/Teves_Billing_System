@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="">
					
					
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;"><button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateReportModal"></button>
				</div>
					
					</div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
						<div class="row">
						
						<div class="col-sm-12 d-flex justify-content-end">
							<div id="download_options"></div>&nbsp;
							<div id="save_options"></div>
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-6">
							<div class="ms-2">
								<div class="fw-bold">Client: <span id="client_name_report" style="font-weight: normal;"></span></div>
							</div>
							
							<div class="ms-2">
								<div class="fw-bold">Address: <span id="client_address_report" style="font-weight: normal;"></span></div>
							</div>
							
						</div>
						
						<div class="col-sm-6">
							
							<div class="ms-2">
								<div class="fw-bold">P.O Period: <span id="po_info" style="font-weight: normal;"></span></div>			
							</div>
							
							<div class="ms-2">
								<div class="fw-bold">Billing Date: <span id="billing_date_info" style="font-weight: normal;"></span></div>
							</div>
	
						</div>
						</div>
				
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="billingstatementreport" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Time</th>
													<th>Driver's Name</th>
													<th>P.O No.</th>
													<th>Plate Number</th>
													<th>Product</th>
													<th>Price</th>
													<th>Quantity</th>
													<th>Amount</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											<tfoot>
											<tr class="" >
												<td align="right" colspan="9"><b>Total Payable:</b></td>
												<td align="center" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  <span id="grand_total_amount" style="font-weight: normal;"></span></td>
											</tr>
											</tfoot>
										</table>
									</div>		
				</div>									
            </div>
          </div>

	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateReceivablesModal" tabindex="-1">
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Receivable</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
						<div class="row">
					<div class="col-lg-4">
					  
					  <ol class="list-group list-group-numbered">
						
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Account Name</div>
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
							<?php echo date('m/d/y'); ?>
						  </div>
						 
						</li>
						
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Control No.</div>
							***Auto Generated after Save
						  </div>
						 
						</li>
						
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Amount</div>
							<div id="amount_receivables"></div>
						  </div>
						 
						</li>
						
					  </ol>					
					
					</div>
					<div class="col-lg-8">
									
					  <form class="g-2 needs-validation" id="ReceivableformNew">
						<!--
						<div class="row mb-2">
						  <label for="tin_number" class="col-sm-3 col-form-label">Tin Number : </label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="tin_number" id="tin_number" value="" required>
							<span class="valid-feedback" id="tin_numberError" title="Required"></span>
						  </div>
						</div>
						-->
						<div class="row mb-2">
						  <label for="or_number" class="col-sm-3 col-form-label">O.R No. : </label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="or_number" id="or_number" value="" required>
							<span class="valid-feedback" id="or_numberError"></span>
						  </div>
						</div>	

						<div class="row mb-2">
						  <label for="payment_term" class="col-sm-3 col-form-label">Payment Term : </label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="payment_term" id="payment_term" value="" required>
							<span class="valid-feedback" id="payment_termError"></span>
						  </div>
						</div>							
						
						<div class="row mb-2">
						  <label for="receivable_description" class="col-sm-3 col-form-label">Description : </label>
						  <div class="col-sm-9">
							<textarea class="form-control" id="receivable_description" style="height: 100px;" required></textarea>
							<span class="valid-feedback" id="receivable_descriptionError"></span>
						  </div>
						</div>			
						
						</div>
						
					</div>
					</div>
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-receivables"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>

	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateReportModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Report</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="generate_report_form">
					  
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
						  <label for="start_date" class="col-sm-3 col-form-label">Start Date</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="start_date" id="start_date" value="<?=date('Y-m-d');?>" required>
							<span class="valid-feedback" id="start_dateError"></span>
						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="end_date" class="col-sm-3 col-form-label">End Date</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="end_date" id="end_date" value="<?=date('Y-m-d');?>" required>
							<span class="valid-feedback" id="end_dateError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="generate_report"> Submit</button>
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>

    </section>
</main>


@endsection

