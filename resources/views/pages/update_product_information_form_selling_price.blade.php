	<!--Moda Product selling Price Create-->
	<div class="modal fade" id="ProductPricePerClientModal" tabindex="-1" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title" id="ProductPricePerClientModal_title">Add Selling Price</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="ProductClientPrice">
					  
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="branch_idx_selling" id="branch_idx_selling">
									@foreach ($teves_branch as $teves_branch_cols)
											<?php 
											$branch_id = $teves_branch_cols->branch_id;	
											?>
										<option value="{{$teves_branch_cols->branch_id}}">
												{{$teves_branch_cols->branch_code}}
										</option>
													
									@endforeach
								</select>
								<label for="branch_idx_selling">Branch</label>
								 <span class="valid-feedback" id="branch_idx_sellingError"></span>
							</div>
							
						</div>
						</div>
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input class="form-control" list="client_name_selling" name="client_name_selling" id="client_idx_selling" required autocomplete="off" placeholder="Client">
									<datalist id="client_name_selling">
										@foreach ($client_data as $client_data_cols)
										<option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
										@endforeach
									</datalist>	
								<label for="client_idx_selling">Client</label>
								 <span class="valid-feedback" id="client_idx_sellingError"></span>
							</div>
							
						</div>
						</div>

						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="selling_price" id="selling_price" value="" step=".01" placeholder="Selling Price" required>
								<label for="selling_price">Selling Price</label>
								<span class="valid-feedback" id="selling_priceError"></span>
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
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-selling-price"> Save</button>
						  <!--<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-selling-price"> Reset</button>		-->			  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="ProductSellingPriceInfoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to delete this selling price?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Branch: <span id="branch_client_selling_price_delete"></span><br>
				Client: <span id="client_name_selling_price_delete"></span><br>
				Selling Price: <span id="client_selling_price_delete"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSellingPriceInfoConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
			 	