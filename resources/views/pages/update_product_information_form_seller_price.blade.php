	<!--Moda Product Seller Price Create-->
	<div class="modal fade" id="ProductPricePerSellerModal" tabindex="-1" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title" id="ProductPricePerSellerModal_title">Add Seller Price</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="ProductSellerPrice">
					  
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="branch_idx_seller" id="branch_idx_seller">
									@foreach ($teves_branch as $teves_branch_cols)
											<?php 
											$branch_id = $teves_branch_cols->branch_id;	
											?>
										<option value="{{$teves_branch_cols->branch_id}}">
												{{$teves_branch_cols->branch_code}}
										</option>
													
									@endforeach
								</select>
								<label for="branch_idx_seller">Branch</label>
								 <span class="valid-feedback" id="branch_idx_sellerError"></span>
							</div>
							
						</div>
						</div>
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input class="form-control" list="supplier_name" name="supplier_name" id="supplier_idx" required autocomplete="off" placeholder="Suppier">
											<datalist id="supplier_name">
											  @foreach ($supplier_data as $supplier_data_cols)
											  <option label="{{$supplier_data_cols->supplier_name}}" data-id="{{$supplier_data_cols->supplier_id}}" value="{{$supplier_data_cols->supplier_name}}">
											  @endforeach
											</datalist>
								<label for="supplier_idx">Suppier</label>
								 <span class="valid-feedback" id="supplier_idxError"></span>
							</div>
							
						</div>
						</div>

						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="seller_price" id="seller_price" value="" step=".01" placeholder="Seller's Price" required >
								<label for="seller_price">Seller's Price</label>
								<span class="valid-feedback" id="seller_priceError"></span>
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
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-seller-price"> Save</button>
						  <!--<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-seller-price"> Reset</button>		-->			  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="SuppliersProductPriceInfoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Seller Price?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Branch: <span id="branch_suppliers_price_delete"></span><br>
				Supplier: <span id="suppliers_name_suppliers_price_delete"></span><br>
				Seller's Price: <span id="sellers_price_suppliers_price_delete"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteSuppliersProductPriceInfoConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
			 	