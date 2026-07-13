	<!--Modal to Product-->
	<div class="modal fade" id="AddPaymentModal" tabindex="-1">
              <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Payment</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-3 needs-validation" id="AddPayment" enctype="multipart/form-data" action="{{route('save_purchase_order_payment')}}"  method="post" >
						@csrf
						<div class="col-sm-12">
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
							<textarea class="form-control" id="purchase_order_bank" name="purchase_order_bank" style="height: 50px;" placeholder="Bank" title="Please press Enter to create new lines for proper text formatting and improved readability."></textarea>
							<label for="purchase_order_bank" class="form-label">Bank</label>
							<span class="valid-feedback" id="purchase_order_bankError"></span>
							</div>
						</div>
						
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type='date' class='form-control' id='purchase_order_date_of_payment' name='purchase_order_date_of_payment' value='<?=date('Y-m-d');?>'>
								<label for="purchase_order_date_of_payment">Date of Payment</label>
								<span class="valid-feedback" id="purchase_order_date_of_paymentError"></span>
							</div>
						 
						</div>
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
							<textarea class="form-control" id="purchase_order_reference_no" name="purchase_order_reference_no" style="height: 50px;" placeholder="Reference No." title="Please press Enter to create new lines for proper text formatting and improved readability."></textarea>
							<label for="purchase_order_reference_no" class="form-label">Reference No.</label>
							<span class="valid-feedback" id="purchase_order_reference_noError"></span>
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="purchase_order_payment_amount" id="purchase_order_payment_amount" required step=".01" placeholder="Amount">
								<label for="purchase_order_payment_amount">Amount</label>
								<span class="valid-feedback" id="purchase_order_payment_amountError"></span>
							</div>
							 
						</div>
						
						
						<div class="row mb-3">
							<div class="col-sm-12">
							<label for="payment_image_reference" class="form-label">Upload</label>
							<input class="form-control" type="file" id="payment_image_reference" name="payment_image_reference">
							</div>
						
						 <input type="hidden" id="purchase_order_id_payment" name="purchase_order_id_payment" value="{{ $PurchaseOrderID }}">
						 <input type="hidden" id="purchase_order_payment_details_id" name="purchase_order_payment_details_id" value="">		
						</div>
						
						<div class="row mb-3">
							<div class="col-sm-12">
								<div class="img-holder" align="center" id="image_payment_div"></div>
							</div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_update_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-payment" value="0"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-payment"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>			
	
	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PurchaseOrderPaymentDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Payment?<br>
				</div>
				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">
				
				Bank: <span id="delete_purchase_order_bank"></span><br>
				Date Of Payment: <span id="delete_purchase_order_date_of_payment"></span><br>	
				Reference No.: <span id="delete_purchase_order_reference_no"></span><br>
				Amount: <span id="delete_purchase_order_payment_amount"></span><br>
				
				</div>
				</div>
				<div class="col-sm-8">
					<div class="delete_img-holder" align="center"></div>
				</div>
				</div>
				
                <div class="modal-footer footer_modal_bg">
				
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deletePurchaseOrderPaymentConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>	
	
	<!-- Bill Delete Modal-->
    <div class="modal fade" id="PurchaseOrderViewPaymentReferenceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Information</h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

				<div class="row mb-2">
				<div class="col-sm-4">
				<div align="left"style="margin: 10px;">
				
				Bank: <span id="view_purchase_order_bank"></span><br>
				Date Of Payment: <span id="view_purchase_order_date_of_payment"></span><br>	
				Reference No.: <span id="view_purchase_order_reference_no"></span><br>
				Amount: <span id="view_purchase_order_payment_amount"></span><br>
				
				</div>
				</div>
				<div class="col-sm-8">
					<div class="view_img-holder" align="center"></div>
				</div>
				</div>
				
                <div class="modal-footer footer_modal_bg">
				
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Close</button>
                  
                </div>
            </div>
        </div>
    </div>	

 	<!-- View Payment Gallery Modal-->
    <div class="modal fade" id="ViewPaymentGalery" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="height: 900px;">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Information</h5>
 					<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
                </div>
				
				<br>
				
					<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" align="center">
					
					
					<div class="carousel-indicators">
				
					</div>
					<div class="carousel-inner" style="height: 700px;">
					 
					</div>
					
					
					
					<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
					  <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: magenta; border-radius: 15px;"></span>
					  <span class="visually-hidden" >Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
					  <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: magenta; border-radius: 15px;"></span>
					  <span class="visually-hidden">Next</span>
					</button>

					</div>
					
				<br>
				
            </div>
        </div>
    </div>	