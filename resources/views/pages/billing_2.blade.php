@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
			  <div class="card">
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5><div class="d-flex justify-content-end" id="">
					<!--<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
						<button type="button" class="btn btn-success new_item bi-file-earmark-pdf" onclick="printCashierReportPDF()"></button>
					</div>	-->				
				  </div>
				</div>			  
		 
            <div class="card-body">	
						
						<div class="card-body">
						  <!--<h5 class="card-title">Bordered Tabs</h5>-->

						  <!-- Bordered Tabs -->
						 
							
						  <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
						    <li class="nav-item" role="presentation">
							  <button class="nav-link active" id="ph3-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph3" type="button" role="tab" aria-controls="ph3" aria-selected="false" tabindex="-1">Create</button>
							</li>
							<li class="nav-item" role="presentation">
							  <button class="nav-link " id="ph1-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph1" type="button" role="tab" aria-controls="ph1" aria-selected="true" title="List Per SO">SO List</button>
							</li>
							<li class="nav-item" role="presentation">
							  <button class="nav-link" id="ph2-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph2" type="button" role="tab" aria-controls="ph2" aria-selected="true" title="List Per Product">History</button>
							</li>
							
						  </ul>
						  <div class="tab-content pt-2" id="borderedTabContent">
							
							<div class="tab-pane fade show active" id="bordered-ph3" role="tabpanel" aria-labelledby="ph3-tab">
							<!--<div align="center" style="font-weight:bold;">Others Sales</div>-->
							 @include('pages.billing_transaction_v2_form')
							</div>
						  
							<div class="tab-pane fade  " id="bordered-ph1" role="tabpanel" aria-labelledby="ph1-tab">
							<!--<div align="center" style="font-weight:bold;">Fuel Sales</div>-->
							<div class="table-responsive">
										<table class="table table-bordered dataTable display" id="getBillingTransactionList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">#</th>
													<th class="all">Date</th>
													<th class="all">Time</th>
													<th class="all">Control Number</th>
													<th class="all">Driver's Name</th>
													<th class="all">S.O No.</th>
													<th class="all">Description</th>																	
													<th class="all">Product</th>
													<th class="none">Price : </th>
													<th class="none">Quantity : </th>
													<th class="none">Amount : </th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
	
											<tfoot>
												<tr>
													<th class="all">#</th>
													<th class="all">Date</th>
													<th class="all">Time</th>
													<th class="all">Control Number</th>
													<th class="all">Driver's Name</th>
													<th class="all">S.O No.</th>
													<th class="all">Description</th>																	
													<th class="all">Product</th>
													<th class="none">Price : </th>
													<th class="none">Quantity : </th>
													<th class="none">Amount : </th>
													<th>Action</th>
												</tr>
											</tfoot>
										</table>
									</div>
							</div>
							<div class="tab-pane fade" id="bordered-ph2" role="tabpanel" aria-labelledby="ph2-tab">
							<!--<div align="center" style="font-weight:bold;">Fuel Sales</div>-->
							<div class="table-responsive">
										<table class="table table-bordered dataTable display" id="getBillingTransactionList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">#</th>
													<th class="all">Date</th>
													<th class="all">Time</th>
													<th class="all">Control Number</th>
													<th class="all">Driver's Name</th>
													<th class="all">S.O No.</th>
													<th class="all">Description</th>																	
													<th class="all">Product</th>
													<th class="none">Price : </th>
													<th class="none">Quantity : </th>
													<th class="none">Amount : </th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
	
											<tfoot>
												<tr>
													<th class="all">#</th>
													<th class="all">Date</th>
													<th class="all">Time</th>
													<th class="all">Control Number</th>
													<th class="all">Driver's Name</th>
													<th class="all">S.O No.</th>
													<th class="all">Description</th>																	
													<th class="all">Product</th>
													<th class="none">Price : </th>
													<th class="none">Quantity : </th>
													<th class="none">Amount : </th>
													<th>Action</th>
												</tr>
											</tfoot>
										</table>
									</div>
							</div>
							
							
						</div>
						
						
				</div>									        
            </div>
          </div>

    </section>
</main>

@endsection
