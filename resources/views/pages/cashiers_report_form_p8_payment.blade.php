	<!--Part 1-->
	
	<div align="right">
			<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CRPH8_Modal"></button>
	</div>
	<br>
		<table class="table" id="">
			<thead>
					<tr class='report'>
						<!--<th style="text-align:center !important;">#</th>-->
						<th style="text-align:center !important;">Cash Payment</th>
						<th style="text-align:center !important;">Limitless Payment</th>
						<th style="text-align:center !important;">Credit/Debit Payment</th>
						<th style="text-align:center !important;">GCASH Payment</th>
						<th style="text-align:center !important;" colspan="2">ACTION</th>
					</tr>
			</thead>
			<tbody id="table_cash_payment_body_data">
					<tr style="display: none;">
						<td>HIDDEN</td>
					</tr>
			</tbody>
		</table>

		<div class="modal fade" id="CRPH8_Modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Add Cash Payment</h5>
                      <button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
                    </div>
                    
					<div class="modal-body">
						<form class="g-3 needs-validation" id="CRPH8_form">		
						
							
							<div class="row mb-2">
							  <label for="cash_payment_amount" class="col-sm-4 col-form-label">Cash Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="cash_payment_amount" id="cash_payment_amount" step=".01" value="0">
								  <span class="valid-feedback" id="cash_payment_amountError"></span>						  
							  </div>
							</div>	
							
							<div class="row mb-2">
							  <label for="limitless_payment_amount" class="col-sm-4 col-form-label">Limitless Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="limitless_payment_amount" id="limitless_payment_amount" step=".01" value="0">
								  <span class="valid-feedback" id="limitless_payment_amountError"></span>						  
							  </div>
							</div>	
							
							<div class="row mb-2">
							  <label for="credit_debit_payment_amount" class="col-sm-4 col-form-label">Credit/Debit Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="credit_debit_payment_amount" id="credit_debit_payment_amount" step=".01" value="0">
								  <span class="valid-feedback" id="credit_debit_payment_amountError"></span>						  
							  </div>
							</div>

							<div class="row mb-2">
							  <label for="ewallet_payment_amount" class="col-sm-4 col-form-label" title="Manual Price">GCASH Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="ewallet_payment_amount" id="ewallet_payment_amount" step=".01" value="0">
								  <span class="valid-feedback" id="ewallet_payment_amountError"></span>						  
							  </div>
							</div>						
							
						
					</div>
					
                    <div class="modal-footer">
							<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-CRPH8"> Submit</button>
						    <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-CRPH8-save"> Reset</button>
                    </div>
					</form>
                </div>
            </div>
        </div>


		<div class="modal fade" id="Update_CRPH8_Modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Update Cash Payment</h5>
                      <button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
                    </div>
                    
					<div class="modal-body">
						<form class="g-3 needs-validation" id="update_CRPH8_form">		
							
							<div class="row mb-2">
							  <label for="update_cash_payment_amount" class="col-sm-4 col-form-label">Cash Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="update_cash_payment_amount" id="update_cash_payment_amount" step=".01">
								  <span class="valid-feedback" id="update_cash_payment_amountError"></span>						  
							  </div>
							</div>
							
							<div class="row mb-2">
							  <label for="update_limitless_payment_amount" class="col-sm-4 col-form-label">Limitless Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="update_limitless_payment_amount" id="update_limitless_payment_amount" required step=".01" onchange="update_dipstick_inventory_compute()" >
								  <span class="valid-feedback" id="update_limitless_payment_amountError"></span>						  
							  </div>
							</div>	
							
							<div class="row mb-2">
							  <label for="update_credit_debit_payment_amount" class="col-sm-4 col-form-label">Credit/Debit Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="update_credit_debit_payment_amount" id="update_credit_debit_payment_amount" required step=".01" onchange="update_dipstick_inventory_compute()" >
								  <span class="valid-feedback" id="update_credit_debit_payment_amountError"></span>						  
							  </div>
							</div>

							<div class="row mb-2">
							  <label for="update_ewallet_payment_amount" class="col-sm-4 col-form-label" title="Manual Price">GCASH Payment</label>
							  <div class="col-sm-8">
								  <input type="number" class="form-control" placeholder="" name="update_ewallet_payment_amount" id="update_ewallet_payment_amount" required step=".01" onchange="update_dipstick_inventory_compute()" >
								  <span class="valid-feedback" id="update_ewallet_payment_amountError"></span>						  
							  </div>
							</div>						
							
						
					</div>
					
                    <div class="modal-footer">
							<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-CRPH8"> Submit</button>
						    <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-CRPH8-update"> Reset</button>
                    </div>
					</form>
                </div>
            </div>
        </div>			  
	
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
				Cash Payment: <span id="delete_cash_payment_amount"></span><br>
				Limitless Payment: <span id="delete_limitless_payment_amount"></span><br>
				Credit/Debit Payment: <span id="delete_credit_debit_payment_amount"></span><br>
				GCASH Payment: <span id="delete_ewallet_payment_amount"></span><br>	
				</div>	
				
                <div class="modal-footer footer_modal_bg">
                    
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteCRPH8Confirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
					
