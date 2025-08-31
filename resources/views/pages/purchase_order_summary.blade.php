@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
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
						
						<!--<div class="row mb-2">
						<div class="col-sm-12">
						
							<div id="chartarea">
								<canvas id="KWhChart" style="max-height: 400px; display: block; box-sizing: border-box; height: 360px; width: 660px;"></canvas>
							</div>
							
						</div>
						-->
						
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


    </section>
</main>


@endsection

