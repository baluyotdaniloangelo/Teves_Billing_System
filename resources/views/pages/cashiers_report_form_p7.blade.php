	<!--Part 1-->
	
	<div align="right">
			<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CRPH6_Modal"></button>
	</div>
	<br>
		<table class="table" id="">
			<thead>
					<tr class='report'>
						<th style="text-align:center !important;">Product</th>
						<th style="text-align:center !important;">Tank</th>
						<th style="text-align:center !important;">Beginning Inventory</th>
						<th style="text-align:center !important;">Sales in Liters</th>
						<th style="text-align:center !important;">Delivery</th>
						<th style="text-align:center !important;">Ending Inventory</th>
						<th style="text-align:center !important;">Book Stock</th>
						<th style="text-align:center !important;">Variance</th>
						<th style="text-align:center !important;" colspan="2">ACTION</th>
					</tr>
			</thead>
			<tbody id="table_product_inventory_body_data">
					<tr style="display: none;">
						<td>HIDDEN</td>
					</tr>
			</tbody>
		</table>

	<!--Modal to Create-->
	<div class="modal fade" id="CRPH6_Modal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product Inventory</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="CRPH6_form">
					  
					  <div class="row mb-2">
						  <label for="product_idx" class="col-sm-4 col-form-label">PRODUCT</label>
						  <div class="col-sm-8">
									<input class="form-control" list="product_name_inventory" name="product_name_inventory" id="product_idx_inventory" required autocomplete="off" onchange="LoadProductTank()">
									<datalist id="product_name_inventory">
										@foreach ($product_data as $product_data_cols)
											<span style="font-family: DejaVu Sans; sans-serif;">
											<option label="&#8369; {{$product_data_cols->product_price}} | {{$product_data_cols->product_name}}" data-id="{{$product_data_cols->product_id}}" data-price="{{$product_data_cols->product_price}}" value="{{$product_data_cols->product_name}}">
											</span>
										@endforeach
									</datalist>
									<span class="valid-feedback" id="product_idx_inventoryError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="product_tank_idx_inventor" class="col-sm-4 col-form-label">TANK</label>
						  <div class="col-sm-8">
									<input class="form-control" list="product_tank_list" name="product_tank_inventory" id="product_tank_idx_inventory" required autocomplete="off">
									<span class="valid-feedback" id="product_tank_idx_inventoryError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="beginning_reading_inventory" class="col-sm-4 col-form-label">BEGINNING INVENTORY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="beginning_inventory" aria-describedby="basic-addon1" name="beginning_inventory" id="beginning_inventory" required step=".01" onchange="inventory_tank()" >
							  <span class="valid-feedback" id="beginning_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="sales_in_liters_inventory" class="col-sm-4 col-form-label">SALES IN LITERS</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="sales_in_liters_inventory" aria-describedby="basic-addon1" name="sales_in_liters_inventory" id="sales_in_liters_inventory" required step=".01" onchange="inventory_tank()" >
							  <span class="valid-feedback" id="sales_in_liters_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="delivery_inventory" class="col-sm-4 col-form-label">DELIVERY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="delivery_inventory" id="delivery_inventory" required step=".01" onchange="inventory_tank()" >
							  <span class="valid-feedback" id="delivery_inventoryError"></span>						  
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="ending_inventory" class="col-sm-4 col-form-label" title="Manual Price">ENDING INVENTORY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="ending_inventory" id="ending_inventory" required step=".01" onchange="inventory_tank()" >
							  <span class="valid-feedback" id="ending_inventoryError"></span>						  
						  </div>
						</div>						
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">BOOK STOCK</label>
						  <div class="col-sm-8">
								 <span id="TotalBookStock">0.00</span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">VARIANCE</label>
						  <div class="col-sm-8">
								 <span id="TotalVariance">0.00</span>
						  </div>
						</div>
						
						<div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-CRPH6"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-CRPH6-save"> Reset</button>					  
						</div>
						
						</form><!-- End Multi Columns Form -->
						
                  </div>
                </div>
             </div>
		</div>		
		
	<!--Modal to Upadate-->
	<div class="modal fade" id="Update_CRPH6_Modal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="CRPH6_form_edit">
					  
					  <div class="row mb-2">
						  <label for="update_product_idx" class="col-sm-3 col-form-label">PRODUCT</label>
						  <div class="col-sm-9">
									<input class="form-control" list="update_product_name" name="update_product_name" id="update_product_idx" required autocomplete="off" onchange="UpdateTotalAmount()">
									<datalist id="update_product_name">
										@foreach ($product_data as $product_data_cols)
											<span style="font-family: DejaVu Sans; sans-serif;">
											<option label="&#8369; {{$product_data_cols->product_price}} | {{$product_data_cols->product_name}}" data-id="{{$product_data_cols->product_id}}" data-price="{{$product_data_cols->product_price}}" value="{{$product_data_cols->product_name}}">
											</span>
										@endforeach
									</datalist>
									<span class="valid-feedback" id="update_product_idxError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="update_beginning_reading_inventory" class="col-sm-3 col-form-label">BEGINNING INVENTORY</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="update_beginning_reading_inventory" id="update_beginning_reading_inventory" required step=".01" onchange="UpdateTotalAmount()" >
							  <span class="valid-feedback" id="update_beginning_reading_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_sales_in_liters_inventory" class="col-sm-3 col-form-label">SALES IN LITERS</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="update_sales_in_liters_inventory" id="update_sales_in_liters_inventory" required step=".01" onchange="UpdateTotalAmount()" >
							  <span class="valid-feedback" id="update_sales_in_liters_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_delivery_inventory" class="col-sm-3 col-form-label">DELIVERY</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="update_delivery_inventory" id="update_delivery_inventory" step=".01" onchange="UpdateTotalAmount()" >
							  <span class="valid-feedback" id="update_delivery_inventoryError"></span>						  
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="update_ending_inventory" class="col-sm-3 col-form-label" title="Msnual Price">ENDING INVENTORY</label>
						  <div class="col-sm-9">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="update_ending_inventory" id="update_ending_inventory" step=".01" onchange="UpdateTotalAmount()" >
							  <span class="valid-feedback" id="update_ending_inventoryError"></span>						  
						  </div>
						</div>						
						<!---->
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">BOOK STOCK</label>
						  <div class="col-sm-8">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="TotalInventory">0.00</span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">VARIANCE</label>
						  <div class="col-sm-8">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="TotalInventory">0.00</span>
						  </div>
						</div>
						
						<div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-CRPH6"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-CRPH6-update"> Reset</button>  
						</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	</div>

	<!--Data List for Product Tank-->
	<datalist id="product_tank_list">
		
	</datalist>		

	<!-- CRP1 Delete Modal-->
    <div class="modal fade" id="CRPH6DeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
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
				PRODUCT: <span id="CRPH6_delete_product_idx"></span><br>
				BEGINNING: <span id="CRPH6_delete_beginning_reading_inventory"></span><br>
				CLOSING: <span id="CRPH6_delete_sales_in_liters_inventory"></span><br>
				delivery_inventory: <span id="CRPH6_delete_delivery_inventory"></span><br>
				SALES IN LITERS: <span id="CRPH6_delete_product_order_quantity"></span><br>			
				PUMP PRICE: <span id="CRPH6_delete_ending_inventory"></span><br>
				PESO SALES: <span id="CRPH6_delete_order_total_amount"></span><br>
				</div>	
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteCRPH6Confirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
					
