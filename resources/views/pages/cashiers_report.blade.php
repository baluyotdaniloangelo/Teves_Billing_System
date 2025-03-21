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
									<div class="table-responsive">
									<table class="table dataTable display nowrap cell-border" id="getCashierReport" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<?php if($data->user_type=="Admin"){ ?><th>Encoder's Name</th><?php } ?>
													<th>Cashier's Name</th>
													<th>Branch</th>
													<th>Employee's On-Duty</th>
													<th>Shift</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											
										</table>
									</div>		
				</div>									        
            </div>
          </div>

	<!-- Client Delete Modal -->
    <div class="modal fade" id="CashiersReportDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Cashiers's Report?<br>
				</div>
				<div align="left"style="margin: 10px;">
				Report Date: <span id="confirm_delete_report_date"></span><br>
				Branch: <span id="confirm_delete_teves_branch"></span><br>
				Cashier's Name: <span id="confirm_delete_cashiers_name"></span><br>
				Forecourt Attendant: <span id="confirm_delete_forecourt_attendant"></span><br>
				Shift: <span id="confirm_delete_shift"></span><br>
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteCashiersReportConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
	
	<!--Modal to Create Client-->
	<div class="modal fade" id="CashierReportModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Cashier's Report</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="CashierReportformNew">
						<div class="row mb-2">
						  <label for="teves_branch" class="col-sm-3 col-form-label">Branch</label>
						  <div class="col-sm-9">
							<select class="form-select form-control" required name="teves_branch" id="teves_branch">
								@foreach ($teves_branch as $teves_branch_cols)
										<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
								@endforeach
							</select>
							<span class="valid-feedback" id="teves_branchError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="cashiers_name" class="col-sm-3 col-form-label">Cashier's on Duty</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="cashiers_name" id="cashiers_name" value="" required>
							<span class="valid-feedback" id="cashiers_nameError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="forecourt_attendant" class="col-sm-3 col-form-label">Employee's On-Duty</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="forecourt_attendant" id="forecourt_attendant" value="" required>
							<span class="valid-feedback" id="forecourt_attendantError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="report_date" class="col-sm-3 col-form-label">Report Date</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control " name="report_date" id="report_date" value="<?=date('Y-m-d');?>" required>
							<span class="valid-feedback" id="report_dateError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="shift" class="col-sm-3 col-form-label">Shift</label>
						  <div class="col-sm-9">
							<!--<input type="text" class="form-control " name="shift" id="shift" value="" required>-->
							<select class="form-select form-control" required="" name="shift" id="shift">
										<option value="1st Shift">1st Shift</option>
										<option value="2nd Shift">2nd Shift</option>
										<option value="3rd Shift">3rd Shift</option>
										<option value="4th Shift">4th Shift</option>
										<option value="5th Shift">5th Shift</option>
										<option value="6th Shift">6th Shift</option>
									</select>
							<span class="valid-feedback" id="shiftError"></span>
						  </div>
						</div>						
							
						<div class="row mb-2">
						  <label for="cashier_report_remarks" class="col-sm-3 col-form-label">Remarks</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="cashier_report_remarks" id="cashier_report_remarks">
							<span class="valid-feedback" id="cashier_report_remarksError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">				
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-cashiers-report"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-cashiers-report"> Reset</button>
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
    </section>
</main>

@endsection