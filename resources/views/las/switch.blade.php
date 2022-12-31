@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card card-12-btm">
			  
				<div class="card-header" style="text-align:center;">
                             
				  
				  <div class="row">
					
						  <div class="col-sm-12">
						  
						  <h5 class="card-title bi bi-toggles2">&nbsp;{{ $title }}</h5>    
						  <!--OPTIONS HERE-->
							<?php
							if($data->user_type!="User"){
							?>
							<div class="d-flex justify-content-end" id="switch_option"></div>
							<?php 
							} 
							?>
						  </div>
						  
						  
						</div>
				  
				  </div>
				</div>			  
		 
            <div class="card-body">
				<div id="test_data"></div>
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="switchList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th nowrap>Switch Name</th>
													<th nowrap>Panel</th>
													<th>State</th>
													<th>Status</th>
													<th>On Time</th>																						
													<th>Off Time</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
												<tr>
													<th>#</th>
													<th nowrap>Switch Name</th>
													<th nowrap>Panel</th>
													<th>State</th>
													<th>Status</th>
													<th>On Time</th>																						
													<th>Off Time</th>
													<th>Action</th>
												</tr>
											</tfoot>	
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!--Switch Notice-->
	<div id="switch_notice_off" style="display: none;">
		<div class="switch_notice_background" align="center">
			<!--OFF-->
			<div class="switch_off_content" style="display: block;">
				<strong id="sw_off">[Switch Name] OFF</strong>
			</div>
		</div>
	</div>

	<div id="switch_notice_on" style="display: none;">
		<div class="switch_notice_background" align="center">
			<!--OFF-->
			<div class="switch_on_content" style="display: block;">
				<strong id="sw_on">[Switch Name] ON</strong>
			</div>
		</div>
	</div>
	
	<div id="switch_notice_send" style="display: none;">
		<div class="switch_notice_background" align="center">
			<!--OFF-->
			<div class="switch_send_content" style="display: block;">
				<strong id="sw_send">[Switch Name] Send</strong>
			</div>
		</div>
	</div>

	<!-- Switch Delete Modal-->
    <div class="modal fade" id="SwitchDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete <span id="switch_name_info"></span>?
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSwitchConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
                  
                </div>
            </div>
        </div>
    </div>	
	
	<!--Modal to Create Switch-->
	<div class="modal fade" id="CreateSwitchModal" tabindex="-1">
           <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Switch</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-2 needs-validation" id="switchform">
					  
						<div class="row mb-2">
						  <label for="switch_name" class="col-sm-3 col-form-label" title="Switch Name">Switch Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="switch_name" id="switch_name" value="" required>
							<span class="valid-feedback" id="switch_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="connection_type" class="col-sm-3 col-form-label">Time:</label>
						  <div class="col-sm-9">
				
							  <div class="input-group has-validation">
									
									<span class="input-group-text on_time_group_style" id="inputGroupPrepend2">ON</span>
									<input type="time" min="0" max="23" class="form-control " timeformat="24h" name="switch_on_time" id="switch_on_time" value="">			
									
									<span class="input-group-text off_time_group_style" id="inputGroupPrepend2">OFF</span>
									<input type="time" class="form-control " name="switch_off_time" id="switch_off_time" value="">					
									
									<div class="invalid-feedback" id="switch_timerError"></div>
 							  </div>
							  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="switch_module_id" class="col-sm-3 col-form-label">Module ID:</label>
						  <div class="col-sm-9">
							<input type="number" class="form-control " name="switch_module_id" id="switch_module_id" value="" required>
							<span class="valid-feedback" id="switch_module_idError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="switch_relay_no" class="col-sm-3 col-form-label">Relay #:</label>
						  <div class="col-sm-9">
							<input type="number" class="form-control " name="switch_relay_no" id="switch_relay_no" value="" required>
							<span class="valid-feedback" id="switch_relay_noError"></span>
						  </div>
						</div>
					
						<div class="row mb-2">
						  <label for="switch_panel_name" class="col-sm-3 col-form-label">Panel Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="switch_panel_name" id="switch_panel_name" value="" required>
							<span class="valid-feedback" id="switch_panel_nameError"></span>
						  </div>
						</div>	
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-switch"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-switch"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
                  </div>
	
	<!--Modal to Update Switch-->
	<div class="modal fade" id="UpdateSwitchModal" tabindex="-1">
           <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Switch</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-2 needs-validation" id="switchformupdate">
					  
						<div class="row mb-2">
						  <label for="switch_name" class="col-sm-3 col-form-label" title="Switch Name">Switch Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="update_switch_name" id="update_switch_name" value="" required>
							<span class="valid-feedback" id="update_switch_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="connection_type" class="col-sm-3 col-form-label">Time:</label>
						  <div class="col-sm-9">
				
							  <div class="input-group has-validation">
									
									<span class="input-group-text on_time_group_style" id="inputGroupPrepend2">ON</span>
									<input type="time" min="0" max="23" class="form-control " name="update_switch_on_time" id="update_switch_on_time" value="">			
									
									<span class="input-group-text off_time_group_style" id="inputGroupPrepend2">OFF</span>
									<input type="time" class="form-control " name="update_switch_off_time" id="update_switch_off_time" value="">					
									
									<div class="invalid-feedback" id="update_switch_timerError"></div>
 							  </div>
							  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="switch_module_id" class="col-sm-3 col-form-label">Module ID:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_switch_module_id" id="update_switch_module_id" value="" required>
							<span class="valid-feedback" id="update_switch_module_idError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="switch_relay_no" class="col-sm-3 col-form-label">Relay #:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_switch_relay_no" id="update_switch_relay_no" value="" required>
							<span class="valid-feedback" id="update_switch_relay_noError"></span>
						  </div>
						</div>
					
						<div class="row mb-2">
						  <label for="switch_panel_name" class="col-sm-3 col-form-label">Panel Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_switch_panel_name" id="update_switch_panel_name" value="" required>
							<span class="valid-feedback" id="update_switch_panel_nameError"></span>
						  </div>
						</div>	
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-switch"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-switch"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
                  </div>
	
	<!--Modal for Switch Information-->
	<div class="modal fade" id="InfoSwitchModal" tabindex="-1">
           <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Switch Information</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

						<ol class="list-group list-group-numbered">
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Switch Name</div>
							<div id="info_switch_name"></div>
						  </div>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Time</div>
							<div id="info_switch_schedule"></div>
						  </div>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Module ID</div>
							<div id="info_switch_module_id"></div>
						  </div>
						  
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Relay Number</div>
							<div id="info_switch_relay_no"></div>
						  </div>
						  
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Panel Name</div>
							<div id="info_switch_panel_name"></div>
						  </div>
						  
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Switch State</div>
							<div id="info_switch_state"></div>
						  </div>
						  
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
						  <div class="ms-2 me-auto">
							<div class="fw-bold">Heart Beat</div>
							<div id="info_heart_beat"></div>
						  </div>
						  
						</li>
						</ol>
						
                  </div>
                </div>
                </div>
	

    </section>
</main>


@endsection

