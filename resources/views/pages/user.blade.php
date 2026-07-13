@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">
	
          <div class="card">
		  
			  <div class="card card-12-btm">
			  
				<div class="card-header" style="text-align:center;">
                             
				  
				  <div class="row">
					
						  <div class="col-sm-12">
						  
						  <h5 class="card-title bi bi-person-lines-fill">&nbsp;{{ $title }}</h5>    
						  <!--OPTIONS HERE-->
							<div class="d-flex justify-content-end" id="user_option"></div>
						  </div>
						  
						  
						</div>
				  
				  </div>
				</div>			  
		 
            <div class="card-body">
				
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table dataTable display nowrap cell-border" id="userList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="all">#</th>
													<th class="all" >Name</th>
													<th class="">Job Title</th>
													<th  class="all">User Name</th>
													<th>User Type</th>
													<th>Status</th>
													<th>Email Address</th>
													<th class="none">Date Created:</th>
													<th class="none">Date Updated:</th>	
													<th class="all">Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
											
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!-- Switch Delete Modal-->
    <div class="modal fade" id="UserDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete this User?</div>
				<div style="margin:10px;">
				User Real Name: <span id="user_real_name_info_confirm"></span><br>
				Username: <span id="user_name_info_confirm"></span><br>
				User Type: <span id="user_type_info_confirm"></span><br>
				</div>
                <div class="modal-footer footer_modal_bg">         
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteUserConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i>Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i>Cancel</button>
                </div>
            </div>
        </div>
    </div>	

	<!--Modal to Create User-->
	<div class="modal fade" id="CreateUserModal" tabindex="-1">
           <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create User</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-2 needs-validation" id="CreateUserform">
					  
						<div class="row mb-2">
						  <label for="user_real_name" class="col-sm-3 col-form-label" title="Switch Name">Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="user_real_name" id="user_real_name" value="" required>
							<span class="valid-feedback" id="user_real_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="user_job_title" class="col-sm-3 col-form-label">Job Title:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="user_job_title" id="user_job_title" value="">
							<span class="valid-feedback" id="user_job_titleError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="user_name" class="col-sm-3 col-form-label">User Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="user_name" id="user_name" value="" required>
							<span class="valid-feedback" id="user_nameError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="user_name" class="col-sm-3 col-form-label">Email Address:</label>
						  <div class="col-sm-9">
							<input type="email" class="form-control " name="user_email_address_management" id="user_email_address_management" value="" required>
							<span class="valid-feedback" id="user_email_address_managementError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="user_password" class="col-sm-3 col-form-label">Password:</label>
						  <div class="col-sm-9">
							<input type="password" class="form-control " name="user_password" id="user_password" value="" required>
							<span class="valid-feedback" id="user_passwordError"></span>
						  </div>
						</div>
					
						<div class="row mb-2">
						  <label for="user_type" class="col-sm-3 col-form-label">User Type:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="user_type" id="user_type" onchange="ChangeAccessType_Add()">
								<option selected="" disabled="" value="">Choose...</option>
								<option value="Admin">Admin</option>
								<option value="Supervisor">Supervisor</option>
								<option value="Accounting_Staff">Accounting Staff</option>
								<option value="Encoder">Encoder</option>
								</select>
							<span class="valid-feedback" id="user_typeError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="user_status" class="col-sm-3 col-form-label">User Status:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="user_status" id="user_status">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
								</select>
							<span class="valid-feedback" id="user_statusError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="user_access" class="col-sm-3 col-form-label">Branch Access:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="user_access" id="user_access">
								<option value="BYBRANCH" selected>Assign by Branch</option>
								<option value="ALL">All</option>
								</select>
							<span class="valid-feedback" id="user_accessError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="user_product_access" class="col-sm-3 col-form-label">Branch Access:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="user_product_access" id="user_product_access">
								<option value="BYCategory" selected>By Category</option>
								<option value="ALL">All</option>
								</select>
							<span class="valid-feedback" id="user_product_accessError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-user"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-user"> Clear Form</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
                  </div>
	
	<!--Modal to Create User-->
	<div class="modal fade" id="UpdateUserModal" tabindex="-1">
           <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update User</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-2 needs-validation" id="UpdateUserform">
					  
						<div class="row mb-2">
						  <label for="update_user_real_name" class="col-sm-3 col-form-label" title="Switch Name">Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="update_user_real_name" id="update_user_real_name" value="" required>
							<span class="valid-feedback" id="update_user_real_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_user_job_title" class="col-sm-3 col-form-label">Job Title:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_user_job_title" id="update_user_job_title" value="">
							<span class="valid-feedback" id="update_user_job_titleError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_user_name" class="col-sm-3 col-form-label">User Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="update_user_name" id="update_user_name" value="" required>
							<span class="valid-feedback" id="update_user_nameError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="user_name" class="col-sm-3 col-form-label">Email Address:</label>
						  <div class="col-sm-9">
							<input type="email" class="form-control " name="update_user_email_address_management" id="update_user_email_address_management" value="" required>
							<span class="valid-feedback" id="update_user_email_address_managementError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="update_user_password" class="col-sm-3 col-form-label">Password:</label>
						  <div class="col-sm-9">
							<input type="password" placeholder="Optional" class="form-control " name="update_user_password" id="update_user_password" value="">
							<span class="valid-feedback" id="update_user_passwordError"></span>
						  </div>
						</div>
					
						<div class="row mb-2">
						  <label for="update_user_type" class="col-sm-3 col-form-label">User Type:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="update_user_type" id="update_user_type" onchange="ChangeAccessType_Update()">
								<option selected="" disabled="" value="">Choose...</option>
								<option value="Admin">Admin</option>
								<option value="Supervisor">Supervisor</option>
								<option value="Accounting_Staff">Accounting Staff</option>
								<option value="Encoder">Encoder</option>
								</select>
							<span class="valid-feedback" id="update_user_typeError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_user_status" class="col-sm-3 col-form-label">User Status:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="update_user_status" id="update_user_status">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
								</select>
							<span class="valid-feedback" id="update_user_statusError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_user_access" class="col-sm-3 col-form-label">Branch Access:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="update_user_access" id="update_user_access">
								<option value="BYBRANCH" selected>Assign by Branch</option>
								<option value="ALL">All</option>
								</select>
							<span class="valid-feedback" id="update_user_accessError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_user_product_access" class="col-sm-3 col-form-label">Branch Access:</label>
						  <div class="col-sm-9">
								<select class="form-select form-control" required="" name="update_user_product_access" id="update_user_product_access">
								<option value="BYCategory" selected>By Category</option>
								<option value="ALL">All</option>
								</select>
							<span class="valid-feedback" id="update_user_product_accessError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-user"> Submit</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
                  </div>
	
	
			<!--Modal to Create User-->
<div class="modal fade" id="SiteUserAccessModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:60px;height:60px;">

                        <i class="bi bi-building-lock fs-3"></i>

                    </div>

                    <div>

                        <h4 class="modal-title fw-bold mb-0">
                            User Site Access
                        </h4>

                        <small class="opacity-75">
                            Manage user access to company branches
                        </small>

                    </div>

                </div>

                <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <!-- BODY -->
<div class="modal-body p-4">

    <!-- USER INFORMATION -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-person-circle text-primary me-2"></i>
                User Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted text-uppercase">
                        Full Name
                    </small>

                    <div class="fw-semibold fs-5"
                         id="user_real_name_info_site_access">
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted text-uppercase">
                        Username
                    </small>

                    <div class="fw-semibold"
                         id="user_name_info_site_access">
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted text-uppercase">
                        User Type
                    </small>

                    <div>

                        <span class="badge bg-primary fs-6 px-3 py-2"
                              id="user_type_info_site_access">
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- BRANCH ACCESS -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-building-check text-success me-2"></i>

                    Branch Access

                </h5>

                <span class="badge bg-success rounded-pill px-3 py-2"
                      id="selected_site_count">

                    Manage Access

                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle"
                       id="UserSiteAccessList"
                       width="100%">

                    <thead class="table-light">

                        <tr>

                            <th width="5%"></th>

                            <th width="8%">#</th>

                            <th width="20%">Branch Code</th>

                            <th>Branch Name</th>

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                    <tfoot>

                        <tr>

                            <th></th>

                            <th>#</th>

                            <th>Branch Code</th>

                            <th>Branch Name</th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">

                <button type="button"
                        class="btn btn-light rounded-3"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-2"></i>

                    Cancel

                </button>

                <button type="button"
                        class="btn btn-success rounded-3 shadow-sm"
                        id="update-user-site-access">

                    <i class="bi bi-save-fill me-2"></i>

                    Save Access

                </button>

            </div>

        </div>

    </div>

</div>

	
<div class="modal fade" id="ProductUserAccessModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:60px;height:60px;">

                        <i class="bi bi-shield-lock fs-3"></i>

                    </div>

                    <div>

                        <h4 class="modal-title fw-bold mb-0">
                            User Product Access
                        </h4>

                        <small class="opacity-75">
                            Manage encoder access to product categories
                        </small>

                    </div>

                </div>

                <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <!-- BODY -->
<!-- BODY -->
<div class="modal-body p-4">

    <!-- USER INFORMATION -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">

                <i class="bi bi-person-circle text-primary me-2"></i>

                User Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted text-uppercase">
                        Full Name
                    </small>

                    <div class="fw-semibold fs-5"
                         id="user_real_name_info_product_access">
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted text-uppercase">
                        Username
                    </small>

                    <div class="fw-semibold"
                         id="user_name_info_product_access">
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted text-uppercase">
                        User Type
                    </small>

                    <div>

                        <span class="badge bg-primary fs-6 px-3 py-2"
                              id="user_type_info_product_access">
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- PRODUCT CATEGORY ACCESS -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-box-seam text-success me-2"></i>

                    Product Category Access

                </h5>

                <span class="badge bg-success rounded-pill px-3 py-2"
                      id="selected_product_count">

                    Manage Access

                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle"
                       id="UserProductAccessList"
                       width="100%">

                    <thead class="table-light">

                        <tr>

                            <th width="5%"></th>

                            <th width="8%">#</th>

                            <th>Product Category</th>

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                    <tfoot>

                        <tr>

                            <th></th>

                            <th>#</th>

                            <th>Product Category</th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">

                <button type="button"
                        class="btn btn-light rounded-3"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-2"></i>

                    Cancel

                </button>

                <button type="button"
                        class="btn btn-success rounded-3 shadow-sm"
                        id="update-user-product-access">

                    <i class="bi bi-save-fill me-2"></i>

                    Save Access

                </button>

            </div>

        </div>

    </div>

</div>

    </section>
</main>


@endsection

