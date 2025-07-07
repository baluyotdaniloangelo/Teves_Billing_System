	<!--Modal to Update Purchase Order-->
	<div class="modal fade" id="UpdatePurchaseOrderModal" data-bs-backdrop="static" tabindex="-1">
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    
					<div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Purchase Order Information</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
					
                    <div class="modal-body">
					<form class="g-2 needs-validation" id="PurchaseOrderformUpdate">
					<div class="row mb-1">
					
						<div class="col-sm-3">
						<div class="form-floating mb-1">
							<input type="date" class="form-control" id="update_purchase_order_date" name="update_purchase_order_date" placeholder="Date">
							<label for="update_purchase_order_date">Date</label>
						</div>
						</div>
						
						<div class="col-sm-3">
						<div class="form-floating mb-1">
							<select class="form-select form-control" required="" name="update_company_header" id="update_company_header" onchange="UpdateBranch()">
									@foreach ($teves_branch as $teves_branch_cols)
										<option value="{{$teves_branch_cols->branch_id}}">
											{{$teves_branch_cols->branch_code}}
										</option>
									@endforeach
								</select>
								<label for="update_company_header">Branch</label>
						</div>
						</div>
						
						<div class="col-sm-6">
							<div class="form-floating mb-1">
								<input class="form-control" list="update_supplier_name_list" name="update_supplier_name" id="update_supplier_idx" required autocomplete="off" onChange="SupplierInfo()" placeholder="Supplier's Name">
										<datalist id="update_supplier_name_list">
											@foreach ($supplier_data as $supplier_data_cols)
											  <option label="{{$supplier_data_cols->supplier_name}}" data-id="{{$supplier_data_cols->supplier_id}}" value="{{$supplier_data_cols->supplier_name}}">
											@endforeach
										</datalist>
							</div>
								<span class="valid-feedback" id="update_supplier_idxError"></span>
						</div>
						
						</div>
						
						<div class="row mb-1">
						
							<div class="col-sm-4">
							<div class="form-floating mb-1">
								<input type="text" class="form-control" id="update_purchase_order_net_percentage" name="update_purchase_order_net_percentage" placeholder="Net Value">
								<label for="update_purchase_order_net_percentage">Net Value</label>
							</div>
							</div>
							
							<div class="col-sm-4">
							<div class="form-floating mb-1">
								<select class="form-select form-control" required="" name="update_purchase_order_invoice" id="update_purchase_order_invoice" onchange="check_withholding_tax()">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
								</select>
								<label for="update_purchase_order_invoice">With Sales Invoice?</label>
							</div>
							</div>
							
							<div class="col-sm-4">
							<div class="form-floating mb-1">
								<input type="text" class="form-control" id="update_purchase_order_less_percentage" name="update_purchase_order_less_percentage" placeholder="Less Value">
								<label for="update_purchase_order_less_percentage">Less Value</label>
							</div>
							</div>
						
						</div>
							
						<div class="row mb-1">
									<div class="col-md-3">
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="update_purchase_order_sales_order_number" name="update_purchase_order_sales_order_number" placeholder="Sales Order #">
										<span class="valid-feedback" id="update_purchase_order_sales_order_numberError"></span>
										<label for="update_purchase_order_sales_order_number" class="form-label">Sales Order #</label>
									</div>
									</div>
									
									<div class="col-md-3"> 
									<div class="form-floating mb-1">
									  <input type="text" class="form-control" id="update_purchase_order_collection_receipt_no" name="update_purchase_order_collection_receipt_no" placeholder="Collection Receipt #">
									  <span class="valid-feedback" id="update_purchase_order_collection_receipt_noError"></span>
									  <label for="update_purchase_order_collection_receipt_no" class="form-label">Collection Receipt #</label>
									</div>
									</div>
									
									<div class="col-md-3">
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="update_purchase_order_official_receipt_no" name="update_purchase_order_official_receipt_no" placeholder="Sales Invoice #">
										<span class="valid-feedback" id="update_purchase_order_official_receipt_nooError"></span>
										<label for="update_purchase_order_official_receipt_no" class="form-label">Sales Invoice #</label>
									</div>
									</div>
									
									<div class="col-md-3">
									<div class="form-floating mb-1">	
										<input type="text" class="form-control" id="update_purchase_order_delivery_receipt_no" name="update_purchase_order_delivery_receipt_no" placeholder="Delivery Receipt #">
										<span class="valid-feedback" id="update_purchase_order_delivery_receipt_noError"></span>
										<label for="update_purchase_order_delivery_receipt_no" class="form-label">Delivery Receipt #</label>
									</div>
									</div>
									
									
									</div>
									
									<div class="row mb-1">
								
									<div class="col-md-3">
									<div class="form-floating mb-1">	
									  <input type="text" class="form-control" id="update_purchase_order_delivery_method" name="update_purchase_order_delivery_method" placeholder="Delivery Method">
									  <label for="update_purchase_order_delivery_method" class="form-label">Delivery Method</label>
									</div>
									</div>
									
									<div class="col-md-9">
									<div class="form-floating mb-1">	
									  <input type="text" class="form-control" id="update_purchase_loading_terminal" name="update_purchase_loading_terminal" list="purchase_loading_terminal_list" placeholder="Loading Terminal">
										<datalist id="purchase_loading_terminal_list">
											@foreach ($purchase_data_suggestion as $purchase_loading_terminal_cols)
												<option value="{{$purchase_loading_terminal_cols->purchase_loading_terminal}}">
											@endforeach
										</datalist>
										<label for="update_purchase_loading_terminal" class="form-label">Loading Terminal</label>
									</div>
									</div>
									
									</div>
									
									<div class="row mb-1">
								
									<div class="col-md-3">
									<div class="form-floating mb-1">	
									  <input type="text" class="form-control" id="update_hauler_operator" name="update_hauler_operator" placeholder="Hauler's Name">
									  <label for="update_hauler_operator" class="form-label">Hauler's Name</label>
									</div>
									</div>
									
									<div class="col-md-9">
									<div class="form-floating mb-1">	
									  <input type="text" class="form-control" id="update_lorry_driver" name="update_lorry_driver" list="lorry_driver_list" placeholder="Driver's Name">
											<datalist id="lorry_driver_list">
												@foreach ($purchase_data_suggestion as $lorry_driver_cols)
													<option value="{{$lorry_driver_cols->lorry_driver}}">
												@endforeach
											  </datalist>
										<label for="update_lorry_driver" class="form-label">Driver's Name</label>
									</div>
									</div>
									
									</div>
									<!--Start Row-->
									<div class="row mb-1">
								
									<div class="col-md-3">
									<div class="form-floating mb-1">	
										<input type="text" class="form-control" id="update_plate_number" name="update_plate_number" list="plate_number_list"placeholder="Plate Number">
										<label for="update_plate_number" class="form-label">Plate Number</label>
									</div>
									</div>
									
									<div class="col-md-9">
									<div class="form-floating mb-1">	
									  <input type="text" class="form-control" id="update_purchase_destination" name="update_purchase_destination" list="purchase_destination_list" placeholder="Destination">
											<datalist id="purchase_destination_list">
												@foreach ($purchase_data_suggestion as $purchase_destination_cols)
													<option value="{{$purchase_destination_cols->purchase_destination}}">
												@endforeach
											</datalist>
										<label for="update_purchase_destination" class="form-label">Destination</label>
									</div>
									</div>
									
									</div>
									<!--End Row-->
									
									<!--Start Row-->
									<div class="row mb-1">
								
									<div class="col-md-6">
									<div class="form-floating mb-1">	
										<textarea class="form-control" id="update_purchase_order_instructions" name="update_purchase_order_instructions" style="height: 50px;" placeholder="Instructions"></textarea>
										<label for="update_purchase_order_instructions" class="form-label">Instructions</label>
									</div>
									</div>
									
									<div class="col-md-6">
									<div class="form-floating mb-1">	
										<textarea class="form-control" id="update_purchase_order_note" name="update_purchase_order_note" style="height: 50px;" placeholder="Notes"></textarea>
										<label for="update_purchase_order_note" class="form-label">Notes</label>
									</div>
									</div>
									
									</div>
									<!--End Row-->
						
					</div>
					
					<div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-purchase-order"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-purchase-order-form"> Reset</button>					  
					</div>
					</form>
                </div>
             </div>
	</div>
