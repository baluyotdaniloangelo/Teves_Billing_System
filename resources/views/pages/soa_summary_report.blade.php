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
								<div class="fw-bold">ACCOUNT NAME: <span id="client_name_report" style="font-weight: normal;"></span></div>
							</div>
							<div class="ms-2">
								<div class="fw-bold">TIN: <span id="client_tin_receivables" style="font-weight: normal;"></span></div>			
							</div>
							<div class="ms-2">
								<div class="fw-bold">ADDRESS: <span id="client_address_report" style="font-weight: normal;"></span></div>
							</div>
							
						</div>
						
						<div class="col-sm-6">
							
							<div class="ms-2">
								<div class="fw-bold">SALES ORDER PERIOD: <span id="po_info" style="font-weight: normal;"></span></div>			
							</div>
							
						</div>
						</div>
						
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="billingstatementreport" width="100%" cellspacing="0" style="font-size:12px !important">
											<thead>
												<tr>
													<th>#</th>
													<th>Transaction Date</th>
													<th>Reference No.</th>
													<th>Description</th>
													<th>Amount</th>
													<th>Remaining Balance</th>
												</tr>
											</thead>				
											
											<tbody>
											
											<tfoot>
											
											
										</table>
									</div>		
				</div>									
            </div>
          </div>

	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateReportModal" tabindex="-1">
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Report</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="generate_report_form">

					  
						<div class="row mb-2">
						  <label for="company_header" class="col-sm-4 col-form-label">Header/Company</label>
						  <div class="col-sm-8">
						  
							<select class="form-select form-control" required="" name="company_header" id="company_header">
							@foreach ($teves_branch as $teves_branch_cols)
								<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
							@endforeach
							</select>
							
						  </div>
						</div>
					  
						<div class="row mb-2">
						  <label for="client_idx" class="col-sm-4 col-form-label">Client</label>
						  <div class="col-sm-8">
							<input class="form-control" list="client_name" name="client_name" id="client_id" required autocomplete="off">
								<datalist id="client_name">
									@foreach ($client_data as $client_data_cols)
										<option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
									@endforeach
								</datalist>
							<span class="valid-feedback" id="client_idxError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="start_date" class="col-sm-4 col-form-label">Start Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="start_date" id="start_date" value="<?=date('Y-m-d');?>" required>
							<span class="valid-feedback" id="start_dateError"></span>
						  </div>
						</div>						
								
						<div class="row mb-2">
						  <label for="end_date" class="col-sm-4 col-form-label">End Date</label>
						  <div class="col-sm-8">
							<input type="date" class="form-control " name="end_date" id="end_date" value="<?=date('Y-m-d');?>" required>
							<span class="valid-feedback" id="end_dateError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="less_per_liter" class="col-sm-4 col-form-label" title="Applicable to All Product with Liter as unit of measurement">Discount Per Liter</label>
						  <div class="col-sm-8">
							<input type="text" class="form-control " name="less_per_liter" id="less_per_liter" value="">
							<span class="valid-feedback" id="less_per_literError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="net_value_percentage" class="col-sm-4 col-form-label" title="Net Value">Net Value</label>
						  <div class="col-sm-8">
							<input type="text" class="form-control " name="net_value_percentage" id="net_value_percentage" value="1.12">
							<span class="valid-feedback" id="net_value_percentage_taxError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="vat_value_percentage" class="col-sm-4 col-form-label" title="Net Value">VAT Value</label>
						  <div class="col-sm-8">
							<input type="text" class="form-control " name="vat_value_percentage" id="vat_value_percentage" value="12">
							<span class="valid-feedback" id="vat_value_percentageError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="withholding_tax_percentage" class="col-sm-4 col-form-label" title="Withholding Tax">Withholding Tax</label>
						  <div class="col-sm-8">
							<input type="text" class="form-control " name="withholding_tax_percentage" id="withholding_tax_percentage" value="1">
							<span class="valid-feedback" id="withholding_taxError"></span>
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

