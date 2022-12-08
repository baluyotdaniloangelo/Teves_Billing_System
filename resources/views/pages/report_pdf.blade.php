@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="">
				</div>
					
					</div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
						<div class="row">
						
						<div class="col-sm-12">
							Billing Statement
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


    </section>
</main>


@endsection

