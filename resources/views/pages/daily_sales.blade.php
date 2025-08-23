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
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div id="chartarea">
								<canvas id="KWhChart" style="max-height: 400px; display: block; box-sizing: border-box; height: 360px; width: 660px;"></canvas>
							</div>
							
						</div>
						
						</div>
						
									<div class="table-responsive">
										<table class="table dataTable display nowrap cell-border"  id="billingstatementreport" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th class="all">Date</th>
													<th>1st Shift</th>
													<th>2nd Shift</th>
													<th>3rd Shift</th>
													<th>4th Shift</th>
													<!--<th>5th Shift</th>
													<th>6th Shift</th>-->
													<th>Fuel Sales</th>
													<th>Other Sales</th>
													<th class="all">MSC - SO</th>
													<th class="all">MSC - Discounts</th>
													<th class="all">MSC - Others</th>
													<th class="all">Theoretical</th>
													<th class="all"title="Total Cash On Hand">Cash</th>
													<th class="all"title="Total Non-cash">Non-Cash</th>
													<th class="all">Total Cash Sales</th>
													<th class="all">Short/Over</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											<tfoot>
											
												<tr>
													<td align="left" ></td>
													<td align="left">TOTAL</td>
													<td align="left" ></td>
													<td align="left" ></td>
													<td align="left" ></td>
													<td align="left" ></td>
													<!--<td align="left" ></td>
													<td align="left" ></td>-->
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_fuel_sales" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_other_sales" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_sales" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_discount" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_cashout_other" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_theoretical_sales" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_cash_tansaction" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_non_cash_payment" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_cash_sales" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_short_over" style="font-weight: normal;">0.00</span></td>
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
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Daily Sales Report</h5>
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
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
							@endforeach
							</select>
							
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

