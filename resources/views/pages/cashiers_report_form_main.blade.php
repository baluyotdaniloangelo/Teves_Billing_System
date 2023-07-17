@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
			  <div class="card">
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="cashier_report_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									
						<form class="g-2 needs-validation" id="CashierReportformNew">
						
						<div class="row mb-2">
						  <label for="teves_branch" class="col-sm-3 col-form-label">Branch</label>
						  <div class="col-sm-4">
							<select class="form-select form-control" required="" name="teves_branch" id="teves_branch">
								<?php $teves_branch = $CashiersReportData[0]['teves_branch']; ?>
								<option value="GT" <?php if($teves_branch=='GT'){ echo "selected";} else{} ?>>GT</option>
								<option value="Teves" <?php if($teves_branch=='Teves'){ echo "selected";} else{} ?>>Teves</option>
							</select>
							
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="forecourt_attendant" class="col-sm-3 col-form-label">Forecourt Attendant</label>
						  <div class="col-sm-4">
							<input type="text" class="form-control" name="forecourt_attendant" id="forecourt_attendant" value="{{ $CashiersReportData[0]['forecourt_attendant'] }}">
							<span class="valid-feedback" id="forecourt_attendantError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="report_date" class="col-sm-3 col-form-label">Report Date</label>
						  <div class="col-sm-4">
							<input type="date" class="form-control " name="report_date" id="report_date" value="{{ $CashiersReportData[0]['report_date'] }}" required>
							<span class="valid-feedback" id="report_dateError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="shift" class="col-sm-3 col-form-label">Shift</label>
						  <div class="col-sm-4">
							<input type="text" class="form-control " name="shift" id="shift" value="{{ $CashiersReportData[0]['shift'] }}">
							<span class="valid-feedback" id="shiftError"></span>
						  </div>
						</div>						
									
						</div>
						 <div class="text-center">				
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-cashiers-report"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-cashiers-report"> Reset</button>
						  </div>
						  </form><!-- End Multi Columns Form -->
						<hr>
						
						<div class="card-body">
						  <!--<h5 class="card-title">Bordered Tabs</h5>-->

						  <!-- Bordered Tabs -->
						  <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
							<li class="nav-item" role="presentation">
							  <button class="nav-link active" id="ph1-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph1" type="button" role="tab" aria-controls="ph1" aria-selected="true">Product</button>
							</li>
							<li class="nav-item" role="presentation">
							  <button class="nav-link" id="ph2-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph2" type="button" role="tab" aria-controls="ph2" aria-selected="false" tabindex="-1">Others</button>
							</li>
							<li class="nav-item" role="presentation">
							  <button class="nav-link" id="ph3-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph3" type="button" role="tab" aria-controls="ph3" aria-selected="false" tabindex="-1">Miscellaneous</button>
							</li>
							<li class="nav-item" role="presentation">
							  <button class="nav-link" id="ph3-tab" data-bs-toggle="tab" data-bs-target="#bordered-ph4" type="button" role="tab" aria-controls="ph4" aria-selected="false" tabindex="-1"> OTHERS (Discounts, misload, return,sweldo,snack,change,lubes p.o)</button>
							</li>
						  </ul>
						  <div class="tab-content pt-2" id="borderedTabContent">
							<div class="tab-pane fade show active" id="bordered-ph1" role="tabpanel" aria-labelledby="ph1-tab">
							  @include('pages.cashiers_report_form_p1')
							</div>
							<div class="tab-pane fade" id="bordered-ph2" role="tabpanel" aria-labelledby="ph2-tab">
							  @include('pages.cashiers_report_form_p2')
							</div>
							<div class="tab-pane fade" id="bordered-ph3" role="tabpanel" aria-labelledby="ph3-tab">
							  @include('pages.cashiers_report_form_p3')
							</div>
							<div class="tab-pane fade" id="bordered-ph4" role="tabpanel" aria-labelledby="ph4-tab">
							  @include('pages.cashiers_report_form_p4')
							</div>
						  </div><!-- End Bordered Tabs -->

						</div>
						
						
				</div>									        
            </div>
          </div>

    </section>
</main>

@endsection