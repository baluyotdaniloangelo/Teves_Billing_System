@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
<section class="section">

<div class="card">

    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#noncash">Non-Cash Report</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cashdrop">Cash Drop</button>
        </li>
    </ul>

    <div class="tab-content pt-3">

        <!-- ================= NON CASH ================= -->
        <div class="tab-pane fade show active" id="noncash">

            <div class="card">
			
                <div class="card-body">
					<div class="d-flex justify-content-end mb-3">
						<button class="btn btn-success"
							data-bs-toggle="modal"
							data-bs-target="#NonCashReportModal">
							Options
						</button>
					</div>
					<hr class="my-2">
                    <!-- ACTION BUTTONS -->
                    <div class="d-flex justify-content-end mb-2">
                        <div id="download_options"></div>
                        <div id="save_options" class="ms-2"></div>
                    </div>

                    <!-- BRANCH DETAILS -->
                    <div class="mb-3">
						<div id="hide_branch_details">
							<div><strong>BRANCH NAME:</strong> <span id="branch_name_report"></span></div>
							<div><strong>BRANCH CODE:</strong> <span id="branch_code_report"></span></div>
							<div><strong>TIN:</strong> <span id="branch_tin_report"></span></div>
							<div><strong>ADDRESS:</strong> <span id="branch_address_report"></span></div>
						</div>
							<div><strong>PERIOD:</strong> <span id="date_range_info"></span></div>
                    </div>

                    

                    <!-- CHART -->
                    <div class="mb-3" id="chartarea_noncash">
                        <canvas id="chart_noncash" style="max-height:400px;"></canvas>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap" id="table_noncash">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>Shift</th>
                                    <th>Station In Charge</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><strong>TOTAL</strong></td>
                                    <td>₱ <span id="total_non_cash">0.00</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>

        <!-- ================= CASH DROP ================= -->
        <div class="tab-pane fade" id="cashdrop">

            <div class="card">
                
                <div class="card-body">

					<div class="d-flex justify-content-end mb-3">
						<button class="btn btn-success"
							data-bs-toggle="modal"
							data-bs-target="#CashDropReportModal">
							Options
						</button>
					</div>
					<hr class="my-2">
					
                    <!-- ACTION BUTTONS -->
                    <div class="d-flex justify-content-end mb-2">
                        <div id="download_options_cashdrop"></div>
                    </div>

                    <!-- BRANCH DETAILS -->
                    <!-- BRANCH DETAILS -->
                    <div class="mb-3">
						<div id="hide_branch_details_cashdrop">
							<div><strong>BRANCH NAME:</strong> <span id="branch_name_report_cashdrop"></span></div>
							<div><strong>BRANCH CODE:</strong> <span id="branch_code_report_cashdrop"></span></div>
							<div><strong>TIN:</strong> <span id="branch_tin_report_cashdrop"></span></div>
							<div><strong>ADDRESS:</strong> <span id="branch_address_report_cashdrop"></span></div>
						</div>
							<div><strong>PERIOD:</strong> <span id="date_range_info_cashdrop"></span></div>
                    </div>

                    <!-- CHART -->
                    <div class="mb-3" id="chartarea_cashdrop">
                        <canvas id="chart_cashdrop" style="max-height:400px;"></canvas>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap" id="table_cashdrop">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>Shift</th>
                                    <th>Station In Charge</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td><strong>TOTAL</strong></td>
                                    <td>₱ <span id="grand_total_cash_drop">0.00</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>


	<!--Modal to Create Client-->
	<div class="modal fade" id="NonCashReportModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Non-cash Report</h5>
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
							<option value="All">All Branches</option>
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
							@endforeach
							</select>
							
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">Payment Type</label>
							<div class="col-sm-8">
								<select class="form-select" name="payment_type" id="payment_type">
									<option value="All">All</option>
									<option value="limitless">Limitless Payment</option>
									<option value="credit_debit">Credit / Debit</option>
									<option value="gcash">GCASH</option>
									<option value="check">Check</option>
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

	<!--Modal to Create Client-->
	<div class="modal fade" id="CashDropReportModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Cash Drop Report</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="generate_report_cash_drop_form">

						<div class="row mb-2">
						  <label for="company_header_cash_drop" class="col-sm-4 col-form-label">Branch</label>
						  <div class="col-sm-8">
						  
							<select class="form-select form-control" required="" name="company_header_cash_drop" id="company_header_cash_drop">
							<option value="All">All Branches</option>
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
							@endforeach
							</select>
							
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="start_date" class="col-sm-4 col-form-label">Start Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="start_date_cash_drop" id="start_date_cash_drop" value="<?=date('Y-m-d');?>" max="9999-12-31" required onchange="setMaxonEndDate_cash_drop()">
							<span class="valid-feedback" id="start_date_cash_dropError"></span>
						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="end_date_cash_drop" class="col-sm-4 col-form-label">End Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="end_date_cash_drop" id="end_date_cash_drop" value="<?=date('Y-m-d');?>" required onchange="CheckEndDateValidity_cash_drop()">
							<span class="valid-feedback" id="end_date_cash_dropError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_cash_drop" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="generate_report_cash_drop"> Submit</button>
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
			 <br>
			 <br>
</section>
</main>

@endsection