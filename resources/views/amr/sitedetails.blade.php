@extends('layouts.layout')  
@section('content')  
<!-- Begin Page Content -->
<main id="main" class="main">

    <section class="section">
	  <div class="card">
	  
			<div class="card card-zero-btm">
            <div class="card-header">
              <h5 class="card-title bi bi-building">&nbsp;{{ $SiteData->site_name }}</h5>
			</div>

            </div>  
	  
            <div class="card-body">
              
              <!-- Bordered Tabs -->
			  
              <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link bi bi-info-circle{{ $status_tab }}" id="status-tab" data-bs-toggle="tab" data-bs-target="#bordered-status" type="button" role="tab" aria-controls="status" aria-selected="{{ $status_aria_selected }}" onclick="remember_sitetab('status')"> Status & Information</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link bi bi-cpu{{ $gateway_tab }}" id="sitegatewaylist-tab" data-bs-toggle="tab" data-bs-target="#bordered-sitegatewaylist" type="button" role="tab" aria-controls="sitegatewaylist" aria-selected="{{ $gateway_aria_selected }}" tabindex="-1" onclick="remember_sitetab('gateway')"> Gateway</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link bi bi-speedometer{{ $meter_tab }}" id="sitemeterlist-tab" data-bs-toggle="tab" data-bs-target="#bordered-sitemeterlist" type="button" role="tab" aria-controls="sitemeterlist" aria-selected="{{ $meter_aria_selected }}" tabindex="-1" onclick="remember_sitetab('meter')"> Meter</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="borderedTabContent">
                <div class="tab-pane fade {{ $status_tab }}" id="bordered-status" role="tabpanel" aria-labelledby="status-tab">
                  
				<div class="row">

				  <div class="col-lg-4">
				  <div class="card">
				  
						<div class="card-header mb-3">	
								<h5 class="card-title" align="center">Details</h5>								
						</div>
						
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
							<div class="tab-pane fade active show" id="bordered-justified-basic" role="tabpanel" aria-labelledby="status-tab">
							 
							<ol class="list-group list-group-numbered">
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Site Description</div>
								{{ $SiteData->site_name }}
							  </div>
							  
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Site Code</div>
								{{ $SiteData->site_code }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Type</div>
								{{ $SiteData->building_type }}
							  </div>
							  
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">IP Address Range</div>
								{{ $SiteData->device_ip_range }}
							  </div>
							  
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Netmask</div>
								{{ $SiteData->ip_netmask }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Network</div>
								{{ $SiteData->ip_network }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Gateway</div>
								{{ $SiteData->ip_gateway }}
							  </div>
							  
							</li>
							
						  </ol>

							 </div>
							

							<div class="tab-pane fade" id="bordered-justified-sap" role="tabpanel" aria-labelledby="sap-tab">
							  
							<ol class="list-group list-group-numbered">
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Business Entity</div>
								{{ $SiteData->business_entity }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Company Number</div>
								{{ $SiteData->company_no }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Service Charge Key</div>
								{{ $SiteData->service_charge_key }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Participation Group</div>
								{{ $SiteData->participation_group }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Settlement Unit:</div>
								{{ $SiteData->settlement_unit }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Settlement Variant Text</div>
								{{ $SiteData->settlement_variant_text }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Settlement Validity</div>
								{{ $SiteData->settlement_valid_from }} - {{ $SiteData->settlement_valid_to }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Date Created</div>
								{{ $SiteData->sap_created_on }} {{ $SiteData->sap_created_at }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">Last Edited</div>
								{{ $SiteData->sap_last_edited_on }} {{ $SiteData->sap_last_edited_at }}
							  </div>
							  
							</li>
							
							<li class="list-group-item d-flex justify-content-between align-items-start">
							  <div class="ms-2 me-auto">
								<div class="fw-bold">SAP Server</div>
								{{ $SiteData->sap_server }}
							  </div>
							  
							</li>
							
						  </ol>
						  
							  </div>
						  </div>
					 
					 </div>
						<!--
						<div class="card-footer">
						  
						</div>
						-->
					  </div>
				  </div>
				  
				<div class="col-lg-4">
				<div class="card-0">
				<div class="card-body">
				  
				  <h5 class="card-title" align="center">Gateway Status(sample)</h5>

				  <!-- Pie Chart -->
				  <canvas id="gateway" style="max-height: 400px; display: block; box-sizing: border-box; height: 400px; width: 719px;" width="719" height="400"></canvas>	  
				  <!-- End Pie CHart -->

				</div>
				</div>
				
				</div>				
				
				<div class="col-lg-4">
				<div class="card-0">
				<div class="card-body">
				  
				  <h5 class="card-title" align="center">Meter Status(sample)</h5>

				  <!-- Pie Chart -->
				  <canvas id="meter" style="max-height: 400px; display: block; box-sizing: border-box; height: 400px; width: 719px;" width="719" height="400"></canvas>	  
				  <!-- End Pie CHart -->

				</div>
				</div>
				
				</div>
		
				</div>
				
				</div>
				
				
                <div class="tab-pane fade {{ $gateway_tab }}" id="bordered-sitegatewaylist" role="tabpanel" aria-labelledby="sitegatewaylist-tab">
					<div class="card-body">
				 
			 			<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="gatewaylist" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Serial #</th>
													<th>IP Address</th>
													<th>MAC Address</th>
													<th>Location</th>																						
													<th>IDF</th>
													<th>Switch</th>
													<th>Port</th>
													<th>Status</th>
													<th>Update</th>
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
                
				  <div class="tab-pane fade {{ $meter_tab }}" id="bordered-sitemeterlist" role="tabpanel" aria-labelledby="sitemeterlist-tab">
                  Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
                </div>
              </div><!-- End Bordered Tabs -->

            </div>
          </div>
	  
	  
    
    </section>


	<!--Modal to Create Gateway-->
	<div class="modal fade" id="CreateGatewayModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Gateway</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-info bi bi-question navbar_icon" data-bs-toggle="modal" data-bs-target="#GatewayManual" id="manualbtn"></button>
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-2 needs-validation" id="gatewayform">
					  
						<div class="row mb-2">
						  <label for="gateway_sn" class="col-sm-3 col-form-label">Serial Number</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="gateway_sn" id="gateway_sn" value="" required>
							<span class="valid-feedback" id="gateway_snError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="gateway_mac" class="col-sm-3 col-form-label">MAC Address</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="gateway_mac" id="gateway_mac" value="" required>
							<span class="valid-feedback" id="gateway_macError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="connection_type" class="col-sm-3 col-form-label">Connection Type</label>
						  <div class="col-sm-9">
							<select class="form-control form-select " name="connection_type" id="connection_type">
							<option value="Ethernet">Ethernet</option>
							<option value="3g Modem">3G Modem</option>
							<option value="3g Modem">4G Modem</option>
							</select>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="gateway_ip" class="col-sm-3 col-form-label">IP Address/SIM #</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="gateway_ip" id="gateway_ip" value="" required>
							<span class="valid-feedback" id="gateway_ipError"></span>
						  </div>
						</div>	

						<div class="row mb-2">
						<label for="gateway_ip" class="col-sm-3 col-form-label">&nbsp;</label>
						<div class="col-md-3">
						  <label for="idf_number">IDF Name</label>
						  <input type="text" class="form-control " name="idf_number" id="idf_number" value="" >
						</div>
						
						<div class="col-md-3">
						  <label for="ip_network">Switch</label>
						  <input type="text" class="form-control " name="idf_switch" id="idf_switch" value="" >
						</div>
						
						<div class="col-md-3">
						  <label for="idf_number">Port</label>
						  <input type="text" class="form-control " name="idf_number" id="idf_port" value="" >
						</div>
						
						</div>
						
						<div class="row mb-2">
						  <label for="physical_location" class="col-sm-3 col-form-label">Physical Location</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="physical_location" id="physical_location" value="" required>
							<span class="valid-feedback" id="physical_locationError"></span>
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="gateway_description" class="col-sm-3 col-form-label">Description</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="gateway_description" id="gateway_description" value="">
							<span class="valid-feedback" id="gateway_descriptionError"></span>
						  </div>
						</div>
						
						
				
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-gateway"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-gateway"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
              </div>	

	<!-- Enabel/Disable CSV Update-->
    <div class="modal fade" id="CSVStatus" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
                <div class="modal-body warning_modal_bg" id="modal-body">
				CSV Update Enabled!
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Close</button>
					     
                </div>
            </div>
        </div>
    </div>
  </main>
 
@endsection

