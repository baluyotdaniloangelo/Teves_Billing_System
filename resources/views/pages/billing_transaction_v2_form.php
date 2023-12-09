	<!--Part 1-->
	<div class="row">
		<div class="col-lg-4">
						<form class="g-3 needs-validation" id="BillingformNew">
							  
								<div class="row mb-2">
								  <label for="order_date" class="col-sm-3 col-form-label">Date</label>
								  <div class="col-sm-9">
									<input type="date" class="form-control" name="order_date" id="order_date" value="<?=date('Y-m-d');?>" required>
									<span class="valid-feedback" id="order_dateError" title="Required"></span>
								  </div>
								</div>
								
								<div class="row mb-2">
								  <label for="order_time" class="col-sm-3 col-form-label">Time</label>
								  <div class="col-sm-9">
									<input type="time" class="form-control " name="order_time" id="order_time" value="<?=date('H:i');?>" required>
									<span class="valid-feedback" id="order_timeError"></span>
								  </div>
								</div>	
								
								<div class="row mb-2">
								  <label for="order_po_number" class="col-sm-3 col-form-label">SO Number</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control " name="order_po_number" id="order_po_number" value="" required>
									<span class="valid-feedback" id="order_po_numberError"></span>
								  </div>
								</div>
								
								<div class="row mb-2">
								  <label for="client_idx" class="col-sm-3 col-form-label">Client</label>
								  <div class="col-sm-9">
									<input class="form-control" list="client_name" name="client_name" id="client_idx" required autocomplete="off" value="">
										<datalist id="client_name">
											@foreach ($client_data as $client_data_cols)
												<option label="{{$client_data_cols->client_name}}" data-id="{{$client_data_cols->client_id}}" value="{{$client_data_cols->client_name}}">
											@endforeach
										</datalist>
									<span class="valid-feedback" id="client_idxError"></span>
								  </div>
								</div>
								
								<div class="row mb-2">
								  <label for="plate_no" class="col-sm-3 col-form-label">Plate Number</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control " name="plate_no" id="plate_no" value="" required list="plate_no_list">
									<datalist id="plate_no_list">
										@foreach ($plate_no as $plate_no_cols)
											<option value="{{$plate_no_cols->plate_no}}">
										@endforeach
									  </datalist>
									<span class="valid-feedback" id="plate_noError"></span>
								  </div>
								</div>	
								
								<div class="row mb-2">
								
								  <label for="drivers_name" class="col-sm-3 col-form-label">Drivers Name</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control " name="drivers_name" id="drivers_name" value="" required list="drivers_list">
									<datalist id="drivers_list">
										@foreach ($drivers_name as $drivers_name_cols)
											<option value="{{$drivers_name_cols->drivers_name}}">
										@endforeach
									  </datalist>
									<span class="valid-feedback" id="drivers_nameError"></span>
								  </div>
								  
								</div>
								
							<div class="row mb-3">
							  <label class="col-sm-3 col-form-label"></label>
							  <div class="col-sm-9" align='right'>
							  <div id="loading_data" style="display:none;">
										<div class="spinner-border text-success" role="status">
											<span class="visually-hidden">Loading...</span>
										</div>
										</div>
								<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-billing-transaction"> Submit</button>
								<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-billing-save"> Reset</button>
							  </div>
							</div>
							
			</form>
	</div>
	
	<div class="col-lg-8">		
		<div align="right">
			<button type="button" class="btn btn-success new_item bi bi-cart-plus-fill" onclick="AddProductRow();" title="Add a Product(1-5 items)"></button>
		</div>
		<br>
		<table class="table" id="table_product_data">
		
			<thead>
				<tr class="report">
					<th style="text-align:center !important;" width="40%">Product</th>
					<th style="text-align:center !important;">Quantity</th>
					<th style="text-align:center !important;">Price</th>
					<th style="text-align:center !important;">Action</th>
				</tr>
			</thead>
										
			<tbody id="table_product_body_data">
					<tr style="display: none;"><td>HIDDEN</td></tr>
			</tbody>
									
		</table>
	
	</div>
	
	</div>