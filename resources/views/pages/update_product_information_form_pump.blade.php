	<!--Modal Product Pump Create-->
	<div class="modal fade" id="AddProductPumpModal" tabindex="-1" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title" id="ProductPumpInformationModal_title">Add Pump Information</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProductPump">
					  
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="branch_idx_pump" id="branch_idx_pump">
									@foreach ($teves_branch as $teves_branch_cols)
											<?php 
											$branch_id = $teves_branch_cols->branch_id;	
											?>
										<option value="{{$teves_branch_cols->branch_id}}">
												{{$teves_branch_cols->branch_code}}
										</option>
													
									@endforeach
								</select>
								<label for="branch_idx_pump">Branch</label>
								 <span class="valid-feedback" id="branch_idx_pumpError"></span>
							</div>
							
						</div>
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-label="" name="pump_name" id="pump_name" value="" placeholder="Pump Name" required>
								<label for="pump_name">Pump Name</label>
								<span class="valid-feedback" id="pump_nameError"></span>
							</div>
							
							
						</div>
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="initial_reading" id="initial_reading" value="" step=".01" placeholder="Initial Reading" required>
								<label for="initial_reading">Initial Reading</label>
								<span class="valid-feedback" id="initial_readingError"></span>
							</div>
							
						</div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-pump"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-pump"> Reset</button>		  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	

	<!--Modal Product Pump Create-->
	<div class="modal fade" id="UpdateProductPumpModal" tabindex="-1" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title" id="ProductPumpInformationModal_title">Edit Pump Information</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProductPump">
					  
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="update_branch_idx_pump" id="update_branch_idx_pump">
									@foreach ($teves_branch as $teves_branch_cols)
											<?php 
											$branch_id = $teves_branch_cols->branch_id;	
											?>
										<option value="{{$teves_branch_cols->branch_id}}">
												{{$teves_branch_cols->branch_code}}
										</option>
													
									@endforeach
								</select>
								<label for="branch_idx_pump">Branch</label>
								 <span class="valid-feedback" id="update_branch_idx_pumpError"></span>
							</div>
							
						</div>
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="text" class="form-control" aria-label="" name="update_pump_name" id="update_pump_name" value="" placeholder="Pump Name" required>
								<label for="pump_name">Pump Name</label>
								<span class="valid-feedback" id="update_pump_nameError"></span>
							</div>
							
							
						</div>
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="update_initial_reading" id="update_initial_reading" value="" step=".01" placeholder="Initial Reading" required>
								<label for="initial_reading">Initial Reading</label>
								<span class="valid-feedback" id="update_initial_readingError"></span>
							</div>
							
						</div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-pump"> Update</button>
						 
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>			 

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PumpInfoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to delete this pump?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Branch: <span id="delete_pump_name_branch"></span><br>
				Pump Name: <span id="delete_pump_name"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePumpInfoConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
			 	