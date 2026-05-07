@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">

		
		
          <div class="card">
			<ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="ph1-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph1" type="button" role="tab" aria-controls="ph1" aria-selected="true">Summary</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="ph2-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph2" type="button" role="tab" aria-controls="ph2" aria-selected="false" tabindex="-1">Product</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="ph3-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph3" type="button" role="tab" aria-controls="ph3" aria-selected="false" tabindex="-1">Volume Summary</button>
				</li>
			</ul>
			<div class="tab-content pt-2" id="borderedTabContent">
				<div class="tab-pane fade show active" id="bordered-ph1" role="tabpanel" aria-labelledby="ph1-tab">  
				
				<div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="">
					
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;"><button type="button" class="btn btn-success new_item bi bi-option" data-bs-toggle="modal" data-bs-target="#CreateReportModal">&nbsp;Options</button>
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
						
						<div class="col-sm-12">
							<div class="ms-2">
								<div class="fw-bold">BRANCH NAME: <span id="branch_name_report" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">BRANCH CODE: <span id="branch_code_report" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">TIN: <span id="branch_tin_report" style="font-weight: normal;"></span></div>			
							</div>
							<div class="ms-2">
								<div class="fw-bold">ADDRESS: <span id="branch_address_report" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">PERIOD: <span id="date_range_info" style="font-weight: normal;"></span></div>			
							</div>
						</div>
						</div>
						
						</div>
						
									<div class="table-responsive">
										<table class="table dataTable display nowrap cell-border"  id="sale_sorder_summary_table" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">No.</th>
													<th class="all">Date</th>
													<th class="all">Control Number</th>
													<th class="all">Supplier</th>
													<th class="none">Sales Order #</th>
													<th class="none">Sales Invoice #</th>
													<th class="all">Total Sales</th>
													<th class="all">VATable Sales</th>
													<th class="all">Withholding Tax</th>
													<th class="all">Total Payable</th>
													<th class="all">Withdrawal Status</th>
													<th class="all">Payment Status</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
												<tr>
													<td align="left" ></td>
													<td align="left"></td>
													<td align="left" ></td>
													<td align="left" ></td>
													<td align="left" ></td>
													<td align="left" >TOTAL:</td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_gross_amount" style="font-weight: normal;">0.00</span></td>
													
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_net_amount" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_withholding_tax" style="font-weight: normal;">0.00</span></td>
													
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_amount_due" style="font-weight: normal;">0.00</span></td>
													<td align="left" colspan="2"></td>
												</tr>
											</tfoot>
										</table>
									</div>		
				</div>									
            
			</div>
			<div class="tab-pane fade" id="bordered-ph2" role="tabpanel" aria-labelledby="ph2-tab">
			
			<div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;Purchase Order Product Summary</h5>
					<div class="d-flex justify-content-end" id="">
					
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;"><button type="button" class="btn btn-success new_item bi bi-option" data-bs-toggle="modal" data-bs-target="#CreateReportModal_P">&nbsp;Options</button>
				</div>
					
					</div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
						<div class="row">
						
						<div class="col-sm-12 d-flex justify-content-end">
							<div id="download_options_P"></div>&nbsp;
							<div id="save_options"></div>
						</div>
						
						</div>
						
						<!--<div class="row mb-2">
						
						<div class="col-sm-12">
							<div class="ms-2">
								<div class="fw-bold">BRANCH NAME: <span id="branch_name_report_p" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">BRANCH CODE: <span id="branch_code_report_p" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">TIN: <span id="branch_tin_report_p" style="font-weight: normal;"></span></div>			
							</div>
							<div class="ms-2">
								<div class="fw-bold">ADDRESS: <span id="branch_address_report_p" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">PERIOD: <span id="date_range_info_p" style="font-weight: normal;"></span></div>			
							</div>
						</div>
						</div>-->
					
						
						</div>
						
									<div class="table-responsive">
										<table class="table dataTable display nowrap cell-border"  id="sale_order_product_summary_table" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">No.</th>
													<th class="all">Date</th>
													<th class="all">Control Number</th>
													<th class="all">Supplier</th>
													<th class="all">Product Name</th>
													<th class="all">Price</th>
													<th class="all">Quantity</th>
													<th class="all">Amount</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
										</table>
									</div>		
				</div>
				
			
			</div>
			</div>

			<div class="tab-pane fade" id="bordered-ph3" role="tabpanel" aria-labelledby="ph3-tab">
			
			<div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;Volume Summary</h5>
					<div class="d-flex justify-content-end" id="">
					
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;"><button type="button" class="btn btn-success new_item bi bi-option" data-bs-toggle="modal" data-bs-target="#CreateReportModal_V">&nbsp;Options</button>
				</div>
					
					</div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
						<div class="row">
						
						<div class="col-sm-12 d-flex justify-content-end">
							<div id="download_options_P"></div>&nbsp;
							<div id="save_options"></div>
						</div>
						
						</div>
						
						<!--<div class="row mb-2">
						
						<div class="col-sm-12">
							<div class="ms-2">
								<div class="fw-bold">BRANCH NAME: <span id="branch_name_report_p" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">BRANCH CODE: <span id="branch_code_report_p" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">TIN: <span id="branch_tin_report_p" style="font-weight: normal;"></span></div>			
							</div>
							<div class="ms-2">
								<div class="fw-bold">ADDRESS: <span id="branch_address_report_p" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">PERIOD: <span id="date_range_info_p" style="font-weight: normal;"></span></div>			
							</div>
						</div>
						</div>-->
					
						
						</div>
						
									<div class="table-responsive">
										<table class="table dataTable display nowrap cell-border"  id="sale_order_product_volume_summary_table" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">No.</th>
													
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
		  
		  <br>
		  <br>

	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateReportModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Purchase Order</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="generate_report_form">

					  
						<div class="row mb-2">
						  <label for="company_header" class="col-sm-4 col-form-label">Branch</label>
						  <div class="col-sm-8">
						  
							<select class="form-select form-control" required="" name="company_header" id="company_header">
							<!---->
							<option label="All" data-id="All" value="All">
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
							@endforeach
							</select>
							
						  </div>
						</div>
					  
					  <div class="row mb-2">
						  <label for="supplier_idx" class="col-sm-4 col-form-label">Supplier's Name</label>
						  <div class="col-sm-8">
							<input class="form-control" list="supplier_name" name="supplier_name" id="supplier_idx"  autocomplete="off">
								<datalist id="supplier_name">
									<option label="All" data-id="All" value="All">
									 @foreach ($supplier_data as $supplier_data_cols)
											  <option label="{{$supplier_data_cols->supplier_name}}" data-id="{{$supplier_data_cols->supplier_id}}" value="{{$supplier_data_cols->supplier_name}}">
											  @endforeach
								</datalist>
							<span class="valid-feedback" id="supplier_idxError"></span>
						  </div>
						</div>
									
						<div class="row mb-2">
						  <label for="start_date" class="col-sm-4 col-form-label">Start Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="start_date" id="start_date" value="<?=date('Y-m-d');?>" max="9999-12-31" required onchange="setMaxonEndDate()">
							<span class="valid-feedback" id="start_dateError"></span>
						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="end_date" class="col-sm-4 col-form-label">End Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="end_date" id="end_date" value="<?=date('Y-m-d');?>" required onchange="CheckEndDateValidity()">
							<span class="valid-feedback" id="end_dateError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="generate_report"> Submit</button>
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>

<!--Product-->
	<div class="modal fade" id="CreateReportModal_P" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Purchase Order Product Summary</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="generate_report_form_P">
					  
						<div class="row mb-2">
						  <label for="company_header" class="col-sm-4 col-form-label">Branch</label>
						  <div class="col-sm-8">
						  
							<select class="form-select form-control" required="" name="company_header_P" id="company_header_P">
							<!---->
							<option label="All" data-id="All" value="All">
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
							@endforeach
							</select>
							
						  </div>
						</div>
					  
					  <div class="row mb-2">
						  <label for="supplier_idx" class="col-sm-4 col-form-label">Supplier's Name</label>
						  <div class="col-sm-8">
							<input class="form-control" list="supplier_name_P" name="supplier_name_P" id="supplier_idx_P"  autocomplete="off">
								<datalist id="supplier_name_P">
									<option label="All" data-id="All" value="All">
									 @foreach ($supplier_data as $supplier_data_cols)
											  <option label="{{$supplier_data_cols->supplier_name}}" data-id="{{$supplier_data_cols->supplier_id}}" value="{{$supplier_data_cols->supplier_name}}">
											  @endforeach
								</datalist>
							<span class="valid-feedback" id="supplier_idxError_P"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="supplier_idx" class="col-sm-4 col-form-label">Product</label>
						  <div class="col-sm-8">
							<input class="form-control" list="product_list" name="product_name" id="product_idx" required autocomplete="off" placeholder="Product">
								<span class="valid-feedback" id="product_idxError_P"></span>
						  </div>
						</div>
									
						<div class="row mb-2">
						  <label for="start_date" class="col-sm-4 col-form-label">Start Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="start_date_P" id="start_date_P" value="<?=date('Y-m-d');?>" max="9999-12-31" required onchange="setMaxonEndDate()">
							<span class="valid-feedback" id="start_dateError_P"></span>
						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="end_date" class="col-sm-4 col-form-label">End Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="end_date_P" id="end_date_P" value="<?=date('Y-m-d');?>" required onchange="CheckEndDateValidity()">
							<span class="valid-feedback" id="end_dateError_P"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="generate_report_P"> Submit</button>
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>

	<div class="modal fade" id="CreateReportModal_V" tabindex="-1">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
			<!-- HEADER -->
			<div class="modal-header bg-success text-white border-0 py-3 px-4">

				<div class="d-flex align-items-center">

						<!-- MOVING ICON -->
						<div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
							 style="width:60px;height:60px;">

							<i class="bi bi-bar-chart-line-fill text-white"
							   style="font-size:28px;"></i>

						</div>

						<!-- TITLE -->
						<div>

							<h4 class="modal-title fw-bold mb-1">
								Purchase Order Volume Summary
							</h4>

							<small class="opacity-75">
								Dynamic monthly product summary report
							</small>

						</div>

					</div>
			<!-- HEADER -->
			<!-- CLOSE BUTTON -->
			<button type="button"
					class="btn btn-light btn-sm rounded-circle shadow-sm"
					data-bs-dismiss="modal">

				<i class="bi bi-x-lg"></i>

			</button>

		</div>

				<!-- FORM -->
				<form id="generate_report_form_V">
					<div class="modal-body p-4">
						<!-- BRANCH -->
						<div class="card border-0 shadow-sm mb-3 rounded-4">
							<div class="card-body">
								<label class="form-label fw-semibold">
									<i class="bi bi-building me-2 text-success"></i>
									Branch
								</label>

								<select class="form-select form-select-lg rounded-3"
										name="company_header_V"
										id="company_header_V"
										required>
									<option value="All">
										All Branches
									</option>
									@foreach ($teves_branch as $teves_branch_cols)
										<option value="{{$teves_branch_cols->branch_id}}">
											{{$teves_branch_cols->branch_code}}
										</option>
									@endforeach
								</select>

							</div>
						</div>
						<!-- SUPPLIER -->
						<div class="card border-0 shadow-sm mb-3 rounded-4">
							<div class="card-body">
								<label class="form-label fw-semibold">
									<i class="bi bi-truck me-2 text-success"></i>
									Supplier
								</label>

								<input class="form-control form-control-lg rounded-3"
									   list="supplier_name_V"
									   name="supplier_name_V"
									   id="supplier_idx_V"
									   autocomplete="off"
									   placeholder="All Suppliers">

								<datalist id="supplier_name_V">
									<option value="All">All Suppliers</option>
									@foreach ($supplier_data as $supplier_data_cols)
										<option
											data-id="{{$supplier_data_cols->supplier_id}}"
											value="{{$supplier_data_cols->supplier_name}}">
										</option>
									@endforeach
								</datalist>

								<small class="text-muted">
									Leave blank to include all suppliers
								</small>

								<span class="valid-feedback"
									  id="supplier_idxError_V">
								</span>

							</div>

						</div>
						<!-- PRODUCT -->
						<div class="card border-0 shadow-sm mb-3 rounded-4">

							<div class="card-body">

								<label class="form-label fw-semibold">
									<i class="bi bi-box-seam me-2 text-success"></i>
									Product Filter
								</label>

								<input class="form-control form-control-lg rounded-3"
									   list="product_list"
									   name="product_name_V"
									   id="product_idx_V"
									   autocomplete="off"
									   placeholder="All Products">

								<small class="text-muted">
									Leave blank to include all products
								</small>

							</div>

						</div>

						<!-- DATE RANGE -->
						<div class="card border-0 shadow-sm mb-3 rounded-4">

							<div class="card-body">

								<!-- HEADER -->
								<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

									<!-- LABEL -->
									<label class="form-label fw-semibold mb-0">
										<i class="bi bi-calendar-range me-2 text-success"></i>
										Date Range
									</label>

									<!-- QUICK FILTER -->
									<div class="btn-group btn-group-sm">

										<button type="button"
												class="btn btn-outline-success"
												onclick="setCurrentMonth()">

											Current Month

										</button>

										<button type="button"
												class="btn btn-outline-primary"
												onclick="setCurrentYear()">

											Current Year

										</button>

										<button type="button"
												class="btn btn-outline-dark"
												onclick="setLast12Months()">

											Last 12 Months

										</button>

									</div>

								</div>

							<!-- DATE INPUTS -->
							<div class="row">

								<div class="col-md-6 mb-3 mb-md-0">

									<label class="small text-muted mb-1">
										Start Date
									</label>

									<input type="date"
										   class="form-control form-control-lg rounded-3"
										   name="start_date_V"
										   id="start_date_V"
										   value="<?=date('Y-m-01');?>"
										   required>

								</div>

								<div class="col-md-6">

									<label class="small text-muted mb-1">
										End Date
									</label>

									<input type="date"
										   class="form-control form-control-lg rounded-3"
										   name="end_date_V"
										   id="end_date_V"
										   value="<?=date('Y-m-d');?>"
										   required>

								</div>

							</div>

						</div>

			</div>

						<!-- INFO -->
						<div class="alert alert-success border-0 rounded-4 mt-4 mb-0">

							<div class="d-flex align-items-start">

								<i class="bi bi-info-circle-fill me-3 fs-5"></i>

								<div>

									<strong>
										Dynamic Monthly Summary
									</strong>

									<div class="small mt-1">
										Columns will automatically generate based
										on your selected date range.
									</div>

								</div>

							</div>

						</div>

					</div>

					<!-- FOOTER -->
					<div class="modal-footer border-0 px-4 pb-4">

						<div id="loading_data"
							 style="display:none;">

							<div class="spinner-border text-success"
								 role="status">

								<span class="visually-hidden">
									Loading...
								</span>

							</div>

						</div>

						<button type="button"
								class="btn btn-light rounded-3 px-4"
								data-bs-dismiss="modal">

							Cancel

						</button>

						<button type="submit"
								class="btn btn-success rounded-3 px-4 shadow-sm"
								id="generate_report_V">

							<i class="bi bi-file-earmark-bar-graph-fill me-2"></i>
							Generate Report

						</button>

					</div>

				</form>

			</div>
		</div>
	</div>

	<!--Data List for Product-->	
	<datalist id="product_list">
	
			<span >	</span>
	</datalist>	
    </section>
</main>


@endsection

