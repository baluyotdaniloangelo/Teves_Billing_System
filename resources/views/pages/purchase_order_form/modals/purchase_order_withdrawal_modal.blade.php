	<!--Modal to Product Delivery-->
	<div class="modal fade" id="AddProductDeliveryModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product Withdrawal Details</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProductDelivery">

						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='date' class='form-control' id='purchase_order_delivery_date' name='purchase_order_delivery_date' value='<?=date('Y-m-d');?>' max="9999-12-31" required>
								<label for="purchase_order_delivery_date">Withdrawal Date</label>
								<span class="valid-feedback" id="purchase_order_delivery_dateError"></span>
							</div>
						 
						</div>

						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list_delivery" name="purchase_order_component_product_idx" id="purchase_order_component_product_idx" required autocomplete="off" placeholder="Product">
						<!--Data List for Product-->
							<datalist id="product_list_delivery">
							<span >	</span>
							
							</datalist>								
							<label for="product_delivery_idx">Product</label>
							<span class="valid-feedback" id="purchase_order_component_product_idxError"></span>
						 </div>
						
						
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="purchase_order_delivery_quantity" id="purchase_order_delivery_quantity" required step=".01" placeholder="Quantity">
								<label for="purchase_order_delivery_quantity">Quantity</label>
								<span class="valid-feedback" id="purchase_order_delivery_quantityError"></span>
							</div>
							 
							 
						</div>

						<div class="col-sm-12">
							
							<div class="form-floating mb-3">
							<textarea class="form-control" id="purchase_order_delivery_withdrawal_reference" name="purchase_order_delivery_withdrawal_reference" style="height: 50px;" placeholder="Withdrawal Reference" title="Please press Enter to create new lines for proper text formatting and improved readability."></textarea>
							<label for="purchase_order_delivery_withdrawal_reference" class="form-label">Withdrawal Reference</label>
							<span class="valid-feedback" id="purchase_order_delivery_withdrawal_referenceError"></span>
							</div>
							
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
							<textarea class="form-control" id="purchase_order_delivery_hauler_details" name="purchase_order_delivery_hauler_details" style="height: 50px;" placeholder="Hauler Details" title="Please press Enter to create new lines for proper text formatting and improved readability."></textarea>
							<label for="purchase_order_delivery_withdrawal_reference" class="form-label">Hauler Details</label>
							<span class="valid-feedback" id="purchase_order_delivery_hauler_detailsError"></span>
							</div> 
							 
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
							<textarea class="form-control" id="purchase_order_delivery_remarks" name="purchase_order_delivery_remarks" style="height: 50px;" placeholder="Remarks" title="Please press Enter to create new lines for proper text formatting and improved readability."></textarea>
							<label for="purchase_order_delivery_withdrawal_reference" class="form-label">Remarks</label>
							<span class="valid-feedback" id="purchase_order_delivery_remarks"></span>
							</div> 
							 
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product_delivery" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product-delivery"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-so-save-product"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>		
			 
	<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="PurchaseOrderProductDeliveryDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Product Withdrawal?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Withdrawal Date: <span id="delete_delivery_purchase_order_delivery_date"></span><br>
				
				Product: <span id="delete_delivery_delete_product_name"></span><br>
				Quantity: <span id="delete_delivery_delete_purchase_order_delivery_quantity"></span><br>
				
				Withdrawal Reference: <span id="delete_delivery_purchase_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="delete_delivery_purchase_order_delivery_hauler_details"></span><br>
				
				Remarks: <span id="delete_delivery_purchase_order_delivery_remarks"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePurchaseOrderProdcutDeliveryConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
	
		<!-- Product Delivery Delete Modal-->
    <div class="modal fade" id="PurchaseOrderProductDeliveryViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Withdrawal Information<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Withdrawal Date: <span id="view_delivery_purchase_order_delivery_date"></span><br>
				
				Product: <span id="view_delivery_delete_product_name"></span><br>
				Quantity: <span id="view_delivery_delete_purchase_order_delivery_quantity"></span><br>
				
				Withdrawal Reference: <span id="view_delivery_purchase_order_delivery_withdrawal_reference"></span><br>
				Hauler Details: <span id="view_delivery_purchase_order_delivery_hauler_details"></span><br>
				
				Remarks: <span id="view_delivery_purchase_order_delivery_remarks"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>