	<!--Modal to Update Sales ORder-->
	<div class="modal fade" id="UpdateSalesOrderModal" data-bs-backdrop="static" tabindex="-1">
              <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    
					<div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Sales Order Information</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
					
                    <div class="modal-body">
					<form class="g-2 needs-validation" id="UpdateSalesOrderformUpdate">
					<div class="row mb-2">
					
						<div class="col-sm-3">
						<div class="form-floating mb-1">
							<input type="date" class="form-control" id="sales_order_date" name="sales_order_date" value="{{ $sales_order_data[0]['sales_order_date'] }}" required max="9999-12-31" >
							<label for="sales_order_date">Date</label>
						</div>
						</div>
						
						<div class="col-sm-3">
						<div class="form-floating mb-1">
							<select class="form-select form-control" required="" name="company_header" id="company_header" onchange="UpdateBranch()">
									<?php $branch_idx = $sales_order_data[0]['company_header']; ?>
									@foreach ($teves_branch as $teves_branch_cols)
									<?php 
										$branch_id = $teves_branch_cols->branch_id;
									?>
										<option value="{{$teves_branch_cols->branch_id}}" <?php if($branch_id==$branch_idx){ echo "selected";} else{} ?>>
											{{$teves_branch_cols->branch_code}}
										</option>
									@endforeach
								</select>
								<label for="company_header">Branch</label>
						</div>
						</div>
						
						<div class="col-sm-6">
							<div class="form-floating mb-1">
								<input class="form-control" list="client_name" name="client_name" id="client_id" required autocomplete="off" value="{{ $sales_order_data[0]['client_name'] }}" onchange="ClientInfo()">
									<datalist id="client_name">
										@foreach ($client_data as $client_data_cols)
										<option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
										@endforeach
									</datalist>	
									<label for="client_name">Sold To</label>
							</div>
								<span class="valid-feedback" id="client_idxError"></span>
						</div>
						
						</div>
						
					<div class="row mb-2">
						
						<div class="col-sm-3">
						<div class="form-floating mb-1">
								<?php $sales_order_payment_type = $sales_order_data[0]->sales_order_payment_type; ?>
								<select class="form-select form-control" required="" name="sales_order_payment_type" id="sales_order_payment_type"> 
									<option value="Receivable" <?php if($sales_order_payment_type=='Receivable'){ echo "selected";} else{} ?>>Receivable</option>
									<option value="PBD" <?php if($sales_order_payment_type=='PBD'){ echo "selected";} else{} ?>>Paid Before Delivery</option>
								</select>
								<label for="sales_order_payment_type">Payment Type</label>
						</div>
						</div>
						
						<div class="col-sm-3">
							<div class="form-floating mb-1">
									
										<select class="form-select form-control" required="" name="sales_order_invoice" id="sales_order_invoice" onchange="check_withholding_tax()"> 
											<option value="1" <?php if($sales_order_invoice=='1'){ echo "selected";} else{} ?>>Yes</option>
											<option value="0" <?php if($sales_order_invoice=='0'){ echo "selected";} else{} ?>>No</option>
										</select>
									<label for="sales_order_invoice">With Sales Invoice?</label>
							</div>
						</div>
						
						<div class="col-sm-3">
							<div class="form-floating mb-1">
									<input type="number" class="form-control" id="sales_order_net_percentage" name="sales_order_net_percentage" value="{{ $sales_order_data[0]['sales_order_net_percentage'] }}" disabled>
									<label for="sales_order_net_percentage">Net Value</label>
							</div>
						</div>
						
						<div class="col-sm-3">
							<div class="form-floating mb-1">
									<input type="number" class="form-control" id="sales_order_withholding_tax" name="sales_order_withholding_tax" value="{{ $sales_order_data[0]['sales_order_withholding_tax'] }}" disabled>
									<label for="sales_order_withholding_tax">Withholding Tax</label>
							</div>
						</div>
			
					</div>
					
					<div class="row mb-2">
					
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="dr_number" name="dr_number" value="{{ $sales_order_data[0]['sales_order_dr_number'] }}" >
									<label for="dr_number">D.R Number</label>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="sales_order_po_number" name="sales_order_po_number" value="{{ $sales_order_data[0]['sales_order_po_number'] }}" >
									<label for="sales_order_po_number">P.O Number</label>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="sales_order_or_number" name="sales_order_or_number" value="{{ $sales_order_data[0]['sales_order_or_number'] }}" >
									<label for="sales_order_or_number">Sales Invoice</label>
							</div>
						</div>
						
					</div>
					
					<div class="row mb-2">
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="sales_order_charge_invoice" name="sales_order_charge_invoice" value="{{ $sales_order_data[0]['sales_order_charge_invoice'] }}" >
									<label for="sales_order_charge_invoice">Charge Invoice</label>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="sales_order_collection_receipt" name="sales_order_collection_receipt" value="{{ $sales_order_data[0]['sales_order_collection_receipt'] }}" >
									<label for="sales_order_collection_receipt">Collection Receipt</label>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="payment_term" name="payment_term" value="{{ $sales_order_data[0]['sales_order_payment_term'] }}" >
									<label for="payment_term">Payment Term</label>
							</div>
						</div>
						
					</div>
					
					<div class="row mb-2">
					
						<div class="col-sm-6">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="delivered_to" name="delivered_to" list="sales_order_delivered_to_list" value="{{ $sales_order_data[0]['sales_order_delivered_to'] }}" placeholder="Delivered To">
										<datalist id="sales_order_delivered_to_list">
											@foreach ($sales_order_delivered_to as $sales_order_delivered_to_cols)
											<option value="{{$sales_order_delivered_to_cols->sales_order_delivered_to}}">
											@endforeach
										</datalist>
									<label for="delivered_to" class="form-label">Delivered To</label>
							</div>
						</div>
						
						<div class="col-sm-6">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="delivered_to_address" name="delivered_to_address" list="delivered_to_address_list" value="{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}" placeholder="Delivered To Address">
									<label for="delivered_to_address" class="form-label">Delivered To Address</label>
							</div>
						</div>
						
					</div>
					
					<div class="row mb-2">
					
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="delivery_method" name="delivery_method" value="{{ $sales_order_data[0]['sales_order_delivery_method'] }}" placeholder="Delivery Method">
									<label for="delivery_method">Delivery Method</label>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="text" class="form-control" id="hauler" name="hauler" value="{{ $sales_order_data[0]['sales_order_hauler'] }}" placeholder="Hauler">
									<label for="hauler">Hauler</label>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-floating mb-1">
									<input type="date" class="form-control" id="required_date" name="required_date" value="{{ $sales_order_data[0]['sales_order_required_date'] }}" placeholder="Required Date">
									<label for="required_date">Required Date</label>
							</div>
						</div>
						
					</div>
					
					<div class="row mb-2">

						<div class="col-sm-6">
							<div class="form-floating mb-1">
									<textarea class="form-control" id="instructions" name="instructions" style="height: 50px;" placeholder="Instructions">{{ $sales_order_data[0]['sales_order_instructions'] }}</textarea>
									<label for="instructions" class="form-label">Instructions</label>
							</div>
						</div>
						
						<div class="col-sm-6">
							<div class="form-floating mb-1">
									<textarea class="form-control" id="note" name="note" style="height: 50px;" placeholder="Notes">{{ $sales_order_data[0]['sales_order_note'] }}</textarea>
									<label for="note" class="form-label">Notes</label>
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
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-sales-order"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-sales-order"> Reset</button>					  
					</div>
					</form>
                </div>
             </div>
	</div>
