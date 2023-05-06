@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="supplier_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="getsupplierList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Supplier Name</th>
													<th>Address</th>
													<th>TIN</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											<tfoot>
												<tr>
													<th>#</th>
													<th>Supplier Name</th>
													<th>Address</th>
													<th>TIN</th>
													<th>Action</th>
												</tr>
											</tfoot>
											
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!-- supplier Delete Modal-->
    <div class="modal fade" id="supplierDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This supplier?<br>
				</div>
				<div align="left"style="margin: 10px;">
				Supplier: <span id="confirm_delete_supplier_name"></span><br>
				Address: <span id="confirm_delete_supplier_address"></span><br>
				TIN: <span id="confirm_delete_supplier_tin"></span><br>
				</div>
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletesupplierConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
	
	<!--Modal to Create supplier-->
	<div class="modal fade" id="CreatesupplierModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Supplier</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">	
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="supplierformNew">
					  
						<div class="row mb-2">
						  <label for="supplier_name" class="col-sm-3 col-form-label">Supplier Name</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="supplier_name" id="supplier_name" value="" required>
							<span class="valid-feedback" id="supplier_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="supplier_address" class="col-sm-3 col-form-label">Address</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="supplier_address" id="supplier_address" value="" required>
							<span class="valid-feedback" id="supplier_addressError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="supplier_tin" class="col-sm-3 col-form-label">TIN</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="supplier_tin" id="supplier_tin" value="" required>
							<span class="valid-feedback" id="supplier_tinError"></span>
						  </div>
						</div>						
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-supplier"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-supplier"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	
	
	<!--Modal to Upadate supplier-->
	<div class="modal fade" id="UpdatesupplierModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Supplier</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="supplierformEdit">
					  
						<div class="row mb-2">
						  <label for="update_supplier_name" class="col-sm-3 col-form-label">Supplier Name</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="update_supplier_name" id="update_supplier_name" value="" required>
							<span class="valid-feedback" id="update_supplier_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_supplier_address" class="col-sm-3 col-form-label">Address</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_supplier_address" id="update_supplier_address" value="" required>
							<span class="valid-feedback" id="update_supplier_addressError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="update_supplier_tin" class="col-sm-3 col-form-label">TIN</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_supplier_tin" id="update_supplier_tin" value="" required>
							<span class="valid-feedback" id="update_supplier_tinError"></span>
						  </div>
						</div>						
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-supplier"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-supplier"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	

    </section>
</main>

@endsection