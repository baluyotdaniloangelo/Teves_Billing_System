@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title bi bi-building">{{ $title }}</h5>           
				  </div>
				</div>			  
		 
            <div class="card-body">
				
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="siteList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Driver's Name</th>
													<th>P.O. No.</th>
													<th>Plate Number</th>																						
													<th>Product</th>
													<th>Liters</th>
													<th>Price</th>
													<th>Amount</th>
													<th>Time</th>
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

	<!-- Site Delete Modal-->
    <div class="modal fade" id="SiteDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete <span id="site_description_info"></span>?
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSiteConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
                  
                </div>
            </div>
        </div>
    </div>	

	<!-- Site Delete Modal-->
    <div class="modal fade" id="SiteDeleteModalConfirmed" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
                <div class="modal-body warning_modal_bg" id="modal-body">
				Successfully Deleted <span id="site_description_info_confirmed"></span>!
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Close</button>
					     
                </div>
            </div>
        </div>
    </div>
	
	<!--Modal to Create Site-->
	<div class="modal fade" id="CreateSiteModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Site</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-info bi bi-question navbar_icon" data-bs-toggle="modal" data-bs-target="#SiteManual" id="manualbtn"></button>
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-3 needs-validation" id="siteform">
					  
						<div class="row mb-2">
					  
							<div class="col-md-6">
							<label for="business_entity" class="form-label">Business Entity</label>	
							<input type="text" class="form-control" name="business_entity" id="business_entity" value="" required>
							<span class="valid-feedback" id="business_entityError" title="Required"></span>
							</div>
						
							<div class="col-md-6">
					 
							<label for="site_code" class="form-label">Site Code</label>
							<input type="text" class="form-control " name="site_code" id="site_code" value="" required>
							<span class="valid-feedback" id="site_codeError"></span>
							</div>
						
					    </div>
					  
						<div class="col-md-12 mb-2" class="form-label">
							<label for="site_description">Site Description</label>
							<input type="text" class="form-control " name="site_description" id="site_description" value="" required>
							<span class="valid-feedback" id="site_descriptionError"></span>
						</div>
					   
					    <div class="row mb-2">
					  
							<div class="col-md-6">
							<label for="building_type" class="form-label">Type</label>
							<select class="form-control form-select " id="building_type">
							<option value="MALL">Mall</option>
							<option value="CPG">CPG</option>
							</select>
							</div>
						
							<div class="col-md-6">
							<label for="site_cut_off" class="form-label">Cut Off</label>
							<input type="number" class="form-control " name="site_cut_off" value="" min='1' max='31' readonly>
							</div>
						
						</div>
					   
						<div class="col-12 mb-2">
							<label for="device_ip_range">IP Address Range</label>
							<input type="text" class="form-control " name="device_ip_range" value="" >
						</div>
						
					    <div class="row mb-2">
					  
						<div class="col-4">
						  <label for="ip_netmask">Netmask</label>
						  <input type="text" class="form-control " name="ip_netmask" value="" >
						</div>
						<div class="col-md-4">
						  <label for="ip_network">Network</label>
						  <input type="text" class="form-control " name="ip_network" value="" >
						</div>
						<div class="col-md-4">
						  <label for="ip_gateway">Gateway</label>
						  <input type="text" class="form-control " name="ip_gateway" value="" >
						</div>	
						
						</div>
								
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success bi bi-save-fill navbar_icon btn-sm" id="save-site"> Submit</button>
						  <button type="reset" class="btn btn-primary bi bi-backspace-fill navbar_icon btn-sm" id="clear-site"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
              </div>
	
	
	<!--Modal to Update Site-->
	<div class="modal fade" id="UpdateSiteModal" tabindex="-1">
	
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Site</h5>
			  <div class="btn-group" role="group" aria-label="Basic outlined example">		
				<button type="button" class="btn btn-info bi bi-question navbar_icon" data-bs-toggle="modal" data-bs-target="#SiteManual" id="manualbtn"></button>
                <button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
              </div>
					</div>
                    <div class="modal-body">
					<div class="row">

					<div class="col-lg-6">
					            <div class="card-body">
			
			<ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-basic" type="button" role="tab" aria-controls="basic" aria-selected="true">CAMR</button>
                </li>
               
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="sap-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-sap" type="button" role="tab" aria-controls="sap" aria-selected="false" tabindex="-1">SAP</button>
                </li>
              </ul>
			  
			  <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="tab-pane fade active show" id="bordered-justified-basic" role="tabpanel" aria-labelledby="home-tab">
                 
				<ol class="list-group list-group-numbered">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Site Description</div>
                    <span id="site_details_site_desciption"></span>
                  </div>
                </li>
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Site Code</div>
                    <span id="site_details_site_code"></span>
                  </div>
                  
                </li>
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Business Entity</div>
                    <span id="site_details_business_entity"></span>
                  </div>
                  
                </li>
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Type</div>
                    <span id="site_details_building_type"></span>
                  </div>
                  
                </li>
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">IP Address Range</div>
                    <span id="site_details_device_ip_range"></span>
                  </div>
                  
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Netmask</div>
                    <span id="site_details_ip_network"></span>
                  </div>
                  
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Network</div>
                    <span id="site_details_ip_netmask"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Gateway</div>
                    <span id="site_details_ip_gateway"></span>
                  </div>
                  
                </li>
				
              </ol>

				 </div>
				
                <div class="tab-pane fade" id="bordered-justified-sap" role="tabpanel" aria-labelledby="sap-tab">
                  
				<ol class="list-group list-group-numbered">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Business Entity</div>
                    <span id="site_details_sap_business_entity"></span>
                  </div>
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Company Number</div>
                    <span id="site_details_company_no"></span>
                  </div>
                  
                </li>
				
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Service Charge Key</div>
                    <span id="site_details_service_charge_key"></span>
                  </div>
                  
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Participation Group</div>
                    <span id="site_details_participation_group"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Settlement Unit:</div>
                    <span id="site_details_settlement_unit"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Settlement Variant Text</div>
                    <span id="site_details_settlement_variant_text"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Settlement Validity</div>
                    <span id="site_details_sap_validity"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Date Created</div>
                    <span id="site_details_sap_created_at"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Last Edited</div>
                    <span id="site_details_sap_last_edited_at"></span>
                  </div>
                  
                </li>
				
				<li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">SAP Server</div>
                    <span id="site_details_sap_server"></span>
                  </div>
                  
                </li>
				
              </ol>
			  
				  </div>
              </div>
         
		 </div>
					</div>
					<div class="col-lg-6">
					
					  <form class="row g-3 needs-validation" id="siteform">
					  
					   	<div class="col-md-6">
							<label for="business_entity" class="form-label">Business Entity</label>	
							<input type="text" class="form-control" name="update_business_entity" id="update_business_entity" value="" required>
							<span class="valid-feedback" id="update_business_entityError" title="Required"></span>
						</div>
						
						<div class="col-md-6">
					 
							<label for="site_code" class="form-label">Site Code</label>
							<input type="text" class="form-control " name="update_site_code" id="update_site_code" value="" required>
							<span class="valid-feedback" id="update_site_codeError"></span>
						</div>
						
						<div class="col-md-12" >
							<label for="site_description">Site Description</label>
							<input type="text" class="form-control " name="update_site_description" id="update_site_description" value="" required>
							<span class="valid-feedback" id="update_site_descriptionError"></span>
						</div>
					   
					   
						<div class="col-md-6">
							<label for="building_type" class="form-label">Type</label>
							<select class="form-control form-select " id="update_building_type">
							<option value="MALL">Mall</option>
							<option value="CPG">CPG</option>
							</select>
						</div>
						
						<div class="col-md-6">
							<label for="site_cut_off" class="form-label">Cut Off</label>
							<input type="number" class="form-control " name="update_site_cut_off" id="update_site_cut_off" value="" min='1' max='31' readonly>
						</div>
				   
						<div class="col-12">
							<label for="device_ip_range">IP Address Range</label>
							<input type="text" class="form-control " name="update_device_ip_range" id="update_device_ip_range" value="" >
						</div>
						<div class="col-12">
						  <label for="ip_netmask">Netmask</label>
						  <input type="text" class="form-control " name="update_ip_netmask" id="update_ip_netmask" value="" >
						</div>
						<div class="col-md-12">
						  <label for="ip_network">Network</label>
						  <input type="text" class="form-control " name="update_ip_network" id="update_ip_network" value="" >
						</div>
						<div class="col-md-12">
						  <label for="ip_gateway">Gateway</label>
						  <input type="text" class="form-control " name="update_ip_gateway" id="update_ip_gateway" value="" >
						</div>
         
					</div>
				  
				  </div>
                </div>
					<div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success bi bi-save-fill navbar_icon btn-sm" id="update-site"> Submit</button>
						  <button type="reset" class="btn btn-primary bi bi-backspace-fill navbar_icon btn-sm" id="clear-site"> Reset</button>
						  
						
					</div>
					</form>
					<!-- End Multi Columns Form -->
				</div>
				</div>
	</div>
	
	<!--Site Manual-->	
	<div class="modal fade" id="SiteManual" tabindex="-1">
							<div class="modal-dialog modal-xl">
							  <div class="modal-content ">
								<div class="modal-header modal-header_manual">
								  <h5 class="modal-title">Manual</h5>
								</div>
								<div class="modal-body">
					
					              <!-- Accordion without outline borders -->
								  <div class="accordion accordion-flush" id="accordionFlushExample">
									<div class="accordion-item">
									  <h2 class="accordion-header" id="flush-headingOne">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
										  Accordion Item #1
										</button>
									  </h2>
									  <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
									  </div>
									</div>
									<div class="accordion-item">
									  <h2 class="accordion-header" id="flush-headingTwo">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
										  Accordion Item #2
										</button>
									  </h2>
									  <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
									  </div>
									</div>
									<div class="accordion-item">
									  <h2 class="accordion-header" id="flush-headingThree">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
										  Accordion Item #3
										</button>
									  </h2>
									  <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
									  </div>
									</div>
								  </div><!-- End Accordion without outline borders -->

								</div>
								<div class="modal-footer modal-footer_manual">
								<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#CreateSiteModal" id="CloseManual"><i class="bi bi-x-circle navbar_icon"></i> Close</button>
								</div>
							  </div>
							</div>
						  </div>
	<!--End of Modal Manual-->	
    </section>
</main>


@endsection

