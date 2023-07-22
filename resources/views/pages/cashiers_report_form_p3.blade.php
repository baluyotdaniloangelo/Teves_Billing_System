	<!--Part 3-->	
	<div align="right">
		<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CRPH3_Modal"></button>
	</div>
	<br>						
	<table class="table" id="">
		<thead>
			<tr class='report'>
				<th style="text-align:center !important;" width='40%'>Product</th>
				<th style="text-align:center !important;">Quantity</th>
				<th style="text-align:center !important;">Price</th>
				<th style="text-align:center !important;">Amount</th>
				<th style="text-align:center !important;" colspan='2'>Action</th>
			</tr>
		</thead>		
		<tbody id="table_product_data_msc">
			<tr style="display: none;">
				<td>HIDDEN</td></tr>
			</tbody>	
	</table>
							
	<!--Modal to Create Other Sales-->
	<div class="modal fade" id="CRPH3_Modal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Miscellaneous Sales</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="CRPH3_form">
					  
					  <div class="row mb-2">
						  <label for="product_idx_PH3" class="col-sm-3 col-form-label">PRODUCT</label>
						  <div class="col-sm-9">
									<input class="form-control" list="product_name_PH3" name="product_name_PH3" id="product_idx_PH3" required autocomplete="off" onchange="TotalAmount_PH3()">
									<datalist id="product_name_PH3">
										@foreach ($product_data as $product_data_cols)
											<span style="font-family: DejaVu Sans; sans-serif;">
											<option label="&#8369; {{$product_data_cols->product_price}} | {{$product_data_cols->product_name}}" data-id="{{$product_data_cols->product_id}}" data-price="{{$product_data_cols->product_price}}" value="{{$product_data_cols->product_name}}">
											</span>
										@endforeach
									</datalist>
									<span class="valid-feedback" id="product_idx_PH3Error"></span>
									</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="order_quantity_PH3" class="col-sm-3 col-form-label">QUANTITY</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder=""  name="order_quantity_PH3" id="order_quantity_PH3" required step=".01" onchange="TotalAmount_PH3()" >
							  <span class="valid-feedback" id="order_quantity_PH3Error"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="product_manual_price_PH3" class="col-sm-3 col-form-label" title="Msnual Price">UNIT PRICE</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" name="product_manual_price_PH3" id="product_manual_price_PH3" step=".01" onchange="TotalAmount_PH3()" >
							  <span class="valid-feedback" id="product_manual_price_PH3Error"></span>						  
						  </div>
						</div>						
						<!---->
						<div class="row mb-2">
						  <label class="col-sm-3 col-form-label">Total Amount</label>
						  <div class="col-sm-9">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="TotalAmount_PH3">0.00</span>
						  </div>
						</div>
						
						<div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-CRPH3"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-CRPH3-save"> Reset</button>					  
						</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>				
	</div>
	
	<!--Modal to Create Other Sales-->
	<div class="modal fade" id="Update_CRPH3_Modal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Miscellaneous Sales</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="CRPH3_form_edit">
					  
					  <div class="row mb-2">
						  <label for="update_product_idx_PH3" class="col-sm-3 col-form-label">PRODUCT</label>
						  <div class="col-sm-9">
									<input class="form-control" list="update_product_name_PH3" name="update_product_name_PH3" id="update_product_idx_PH3" required autocomplete="off" onchange="UpdateTotalAmount_PH3()">
									<datalist id="update_product_name_PH3">
										@foreach ($product_data as $product_data_cols)
											<span style="font-family: DejaVu Sans; sans-serif;">
											<option label="&#8369; {{$product_data_cols->product_price}} | {{$product_data_cols->product_name}}" data-id="{{$product_data_cols->product_id}}" data-price="{{$product_data_cols->product_price}}" value="{{$product_data_cols->product_name}}">
											</span>
										@endforeach
									</datalist>
									<span class="valid-feedback" id="update_product_idx_PH3Error"></span>
									</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="update_order_quantity_PH3" class="col-sm-3 col-form-label">QUANTITY</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder=""  name="update_order_quantity_PH3" id="update_order_quantity_PH3" required step=".01" onchange="UpdateTotalAmount_PH3()" >
							  <span class="valid-feedback" id="update_order_quantity_PH3Error"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_product_manual_price_PH3" class="col-sm-3 col-form-label" title="Msnual Price">UNIT PRICE</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" name="update_product_manual_price_PH3" id="update_product_manual_price_PH3" step=".01" onchange="UpdateTotalAmount_PH3()" >
							  <span class="valid-feedback" id="update_product_manual_price_PH3Error"></span>						  
						  </div>
						</div>						
						<!---->
						<div class="row mb-2">
						  <label class="col-sm-3 col-form-label">Total Amount</label>
						  <div class="col-sm-9">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="UpdateTotalAmount_PH3">0.00</span>
						  </div>
						</div>
						
						<div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-CRPH3"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-CRPH3-update"> Reset</button>					  
						</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>		
	</div>
	
	<!-- CRP2 Delete Modal-->
    <div class="modal fade" id="CRPH3DeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Item?<br>
				</div>
				<div align="left"style="margin: 10px;">
				PRODUCT: <span id="delete_product_idx_PH3"></span><br>
				QUANTITY: <span id="delete_order_quantity_PH3"></span><br>
				PRICE: <span id="delete_product_manual_price_PH3"></span><br>
				PESO SALES: <span id="delete_TotalAmount_PH3"></span><br>
				</div>	
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteCRPH3Confirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>							  
