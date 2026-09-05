	<!--Modal to Product-->
	<div class="modal fade" id="AddProductModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProduct">

						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list" name="product_name" id="product_idx" required autocomplete="off" onchange="TotalAmount()"placeholder="Date">
						  <label for="product_idx">Product</label>
						 </div>
						<span class="valid-feedback" id="product_idxError"></span>
						
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="product_manual_price" id="product_manual_price" value="" step=".01" onchange="TotalAmount()" placeholder="Custom Price">
								<label for="product_manual_price">Custom Price</label>
							</div>
						 
						</div>
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="order_quantity" id="order_quantity" required step=".01" onchange="TotalAmount()" placeholder="Quantity">
								<label for="order_quantity">Quantity</label>
							</div>
							 <span class="valid-feedback" id="order_quantityError"></span>
						</div>

						<div class="row mb-2">
						  <label class="col-sm-3 col-form-label">Amount</label>
						  <div class="col-sm-9">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="TotalAmount">0.00</span>
						  </div>
						</div>
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product" value="0"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-so-save-product"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>		

	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PurchaseOrderProductDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Product?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Product: <span id="bill_delete_product_name"></span><br>
				Quantity: <span id="bill_delete_order_quantity"></span><br>
				
				Total Amount: <span id="bill_delete_order_total_amount"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePurchaseOrderComponentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>

	<!--Data List for Product-->
	<datalist id="product_list">
			<span >	</span>
	</datalist>	