@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="client_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="getclientList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Client Name</th>
													<th>Address</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
												<tr>
													<th>#</th>
													<th>Client Name</th>
													<th>Address</th>
													<th>Action</th>
												</tr>
											</tfoot>
											
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!-- Client Delete Modal-->
    <div class="modal fade" id="ClientDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Client?<br>
				</div>
				<div align="left"style="margin: 10px;">
				Client: <span id="confirm_delete_client_name"></span><br>
				Address: <span id="confirm_delete_client_address"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteClientConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
                  
                </div>
            </div>
        </div>
    </div>	
	
	<!--Modal to Create Client-->
	<div class="modal fade" id="CreateClientModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Client</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="ClientformNew">
					  
						<div class="row mb-2">
						  <label for="client_name" class="col-sm-3 col-form-label">Client Name</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="client_name" id="client_name" value="" required>
							<span class="valid-feedback" id="client_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="client_address" class="col-sm-3 col-form-label">Address</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="client_address" id="client_address" value="" required>
							<span class="valid-feedback" id="client_addressError"></span>
						  </div>
						</div>						
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-client"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-client"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	
	
	<!--Modal to Upadate Client-->
	<div class="modal fade" id="UpdateClientModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Client</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="ClientformEdit">
					  
						<div class="row mb-2">
						  <label for="update_client_name" class="col-sm-3 col-form-label">Client Name</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="update_client_name" id="update_client_name" value="" required>
							<span class="valid-feedback" id="update_client_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_client_address" class="col-sm-3 col-form-label">Address</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_client_address" id="update_client_address" value="" required>
							<span class="valid-feedback" id="update_client_addressError"></span>
						  </div>
						</div>					
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-client"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-client"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	

    </section>
</main>

@endsection