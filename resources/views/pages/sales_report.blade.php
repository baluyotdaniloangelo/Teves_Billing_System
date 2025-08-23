@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="">
					
					<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;"><button type="button" class="btn btn-success new_item bi bi-option" data-bs-toggle="modal" data-bs-target="#CreateSalesReportFuelModal">&nbsp;Options</button>
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
							<table id="sale_sorder_summary_table" class="table dataTable display nowrap cell-border" style="width:100%">
								<thead></thead>
								<tbody></tbody>
							</table>
						</div>
						
				</div>									
            </div>
          </div>
		  <br>
		  <br>

	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateSalesReportFuelModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Sales Order</h5>
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
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="generate_sales_report_fuel"> Submit</button>
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>


    </section>
</main>


@endsection

