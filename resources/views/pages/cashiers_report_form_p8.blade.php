	<!--Part 1-->
	
	<div align="right">
			<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CRPH8_Modal"></button>
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
			<tbody id="table_product_dipstick_inventory_body_data">
					<tr style="display: none;">
						<td>HIDDEN</td>
					</tr>
			</tbody>
		</table>

	<!--Modal to Create-->
	<div class="modal fade" id="CRPH8_Modal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product Dipstick Inventory</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="CRPH8_form">
					  
					  <div class="row mb-2">
						  <label for="dipstick_product_idx" class="col-sm-4 col-form-label">PRODUCT</label>
						  <div class="col-sm-8">
									<input class="form-control" list="dipstick_product_list_inventory" name="dipstick_product_name_inventory" id="dipstick_product_idx_inventory" required autocomplete="off" onchange="LoadProductTank_dipstick()">
									
									<span class="valid-feedback" id="dipstick_product_idx_inventoryError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="dipstick_product_tank_idx_inventor" class="col-sm-4 col-form-label">TANK</label>
						  <div class="col-sm-8">
									<input class="form-control" list="dipstick_product_tank_list" name="dipstick_product_tank_inventory" id="dipstick_product_tank_idx_inventory" required autocomplete="off">
									<span class="valid-feedback" id="dipstick_product_tank_idx_inventoryError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="dipstick_beginning_reading_inventory" class="col-sm-4 col-form-label">BEGINNING INVENTORY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="dipstick_beginning_inventory" aria-describedby="basic-addon1" name="dipstick_beginning_inventory" id="dipstick_beginning_inventory" required step=".01" onchange="inventory_tank_dipstick()" >
							  <span class="valid-feedback" id="dipstick_beginning_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="dipstick_sales_in_liters_inventory" class="col-sm-4 col-form-label">SALES IN LITERS</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="dipstick_sales_in_liters_inventory" aria-describedby="basic-addon1" name="dipstick_sales_in_liters_inventory" id="dipstick_sales_in_liters_inventory" required step=".01" onchange="inventory_tank_dipstick()" >
							  <span class="valid-feedback" id="dipstick_sales_in_liters_inventoryError"></span>						  
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="dipstick_sales_in_liters_inventory" class="col-sm-4 col-form-label">UGT PUMPING</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="dipstick_sales_in_liters_inventory" aria-describedby="basic-addon1" name="dipstick_sales_in_liters_inventory" id="dipstick_sales_in_liters_inventory" required step=".01" onchange="inventory_tank()" >
							  <span class="valid-feedback" id="dipstick_sales_in_liters_inventoryError"></span>						  
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="dipstick_delivery_inventory" class="col-sm-4 col-form-label">DELIVERY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="Delivery" aria-describedby="basic-addon1" name="dipstick_delivery_inventory" id="dipstick_delivery_inventory" required step=".01" onchange="inventory_tank_dipstick()" >
							  <span class="valid-feedback" id="dipstick_delivery_inventoryError"></span>						  
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="dipstick_ending_inventory" class="col-sm-4 col-form-label" title="Ending Inventory">ENDING INVENTORY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="dipstick_ending_inventory" id="dipstick_ending_inventory" required step=".01" onchange="inventory_tank_dipstick()" >
							  <span class="valid-feedback" id="dipstick_ending_inventoryError"></span>						  
						  </div>
						</div>						
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">BOOK STOCK</label>
						  <div class="col-sm-8">
								 <span id="dipstick_TotalBookStock">0.00</span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">VARIANCE</label>
						  <div class="col-sm-8">
								 <span id="dipstick_TotalVariance">0.00</span>
						  </div>
						</div>
						
						<div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-CRPH8"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-CRPH8-save"> Reset</button>					  
						</div>
						
						</form><!-- End Multi Columns Form -->
						
                  </div>
                </div>
             </div>
		</div>		
		
	<!--Modal to Upadate-->
	<div class="modal fade" id="Update_CRPH8_Modal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Product Dipstick Inventory</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="CRPH8_form_edit">
					  
					  <div class="row mb-2">
						  <label for="update_product_idx" class="col-sm-4 col-form-label">PRODUCT</label>
						  <div class="col-sm-8">
									<input class="form-control" list="product_list_inventory" name="update_product_name_inventory" id="update_product_idx_inventory" required autocomplete="off" onchange="LoadProductTank_Update()">
									
									<span class="valid-feedback" id="update_product_idx_inventoryError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="update_product_tank_idx_inventor" class="col-sm-4 col-form-label">TANK</label>
						  <div class="col-sm-8">
									<input class="form-control" list="update_product_tank_list" name="update_product_tank_inventory" id="update_product_tank_idx_inventory" required autocomplete="off">
									<span class="valid-feedback" id="update_product_tank_idx_inventoryError"></span>
							</div>	
						</div>	
						
						<div class="row mb-2">
						  <label for="beginning_reading_inventory" class="col-sm-4 col-form-label">BEGINNING INVENTORY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="update_beginning_inventory" aria-describedby="basic-addon1" name="update_beginning_inventory" id="update_beginning_inventory" required step=".01" onchange="update_inventory_tank()" >
							  <span class="valid-feedback" id="update_beginning_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_sales_in_liters_inventory" class="col-sm-4 col-form-label">SALES IN LITERS</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="update_sales_in_liters_inventory" aria-describedby="basic-addon1" name="update_sales_in_liters_inventory" id="update_sales_in_liters_inventory" required step=".01" onchange="update_inventory_tank()" >
							  <span class="valid-feedback" id="update_sales_in_liters_inventoryError"></span>						  
						  </div>
						</div>	
						
						<div class="row mb-2">
						  <label for="update_delivery_inventory" class="col-sm-4 col-form-label">DELIVERY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="update_delivery_inventory" aria-describedby="basic-addon1" name="update_delivery_inventory" id="update_delivery_inventory" required step=".01" onchange="update_inventory_tank()" >
							  <span class="valid-feedback" id="update_delivery_inventoryError"></span>						  
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="update_ending_inventory" class="col-sm-4 col-form-label" title="Manual Price">ENDING INVENTORY</label>
						  <div class="col-sm-8">
							  <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" name="update_ending_inventory" id="update_ending_inventory" required step=".01" onchange="update_inventory_tank()" >
							  <span class="valid-feedback" id="update_ending_inventoryError"></span>						  
						  </div>
						</div>						
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">BOOK STOCK</label>
						  <div class="col-sm-8">
								 <span id="update_TotalBookStock">0.00</span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label class="col-sm-4 col-form-label">VARIANCE</label>
						  <div class="col-sm-8">
								 <span id="update_TotalVariance">0.00</span>
						  </div>
						</div>
						
						<div class="modal-footer modal-footer_form">
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-CRPH8"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-CRPH8-update"> Reset</button>  
						</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	</div>

	<!--Data List for Product Tank-->
	<datalist id="product_list_inventory_dipstick">
		
	</datalist>		

	<!--Data List for Product Tank-->
	<datalist id="product_tank_list_dipstick">
		
	</datalist>		
	
	<datalist id="update_product_tank_list_dipstick">
	
	</datalist>		
	<!-- CRP1 Delete Modal-->
    <div class="modal fade" id="CRPH8DeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
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
				Product: <span id="delete_product_idx_inventory"></span><br>
				Tank: <span id="delete_product_tank_idx_inventory"></span><br>
				Beginning Inventory: <span id="delete_beginning_inventory"></span><br>
				Sales in liters: <span id="delete_sales_in_liters_inventory"></span><br>
				Delivery: <span id="delete_delivery_inventory"></span><br>
				Ending Inventory: <span id="delete_ending_inventory"></span><br>				
				Book Stock: <span id="CRPH8_delete_TotalBookStock"></span><br>
				Variance: <span id="CRPH8_delete_TotalVariance"></span><br>
				</div>	
				
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteCRPH8Confirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
					
