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
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="billingstatementreport" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Driver's Name</th>
													<th>P.O No.</th>
													<th>Plate Number</th>
													<th>Product</th>
													<th>Quantity</th>
													<th>Price</th>
													<th>Amount</th>
													<th>Time</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateReportModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
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
						  <label for="start_date" class="col-sm-3 col-form-label">Start</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="start_date" id="start_date" value="" required>
							<span class="valid-feedback" id="start_dateError"></span>
						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="end_date" class="col-sm-3 col-form-label">End Date</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="end_date" id="end_date" value="" required>
							<span class="valid-feedback" id="end_dateError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="generate_report"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>

    </section>
</main>


@endsection

