<!-- PRODUCT TAB -->
<div class="tab-pane fade"
     id="payment"
     role="tabpanel"
     aria-labelledby="payment">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
<div class="card-header bg-white border-0 py-3 px-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <!-- LEFT -->
        <div>
			<!--
            <small class="text-muted text-uppercase fw-semibold">
                Purchase Order
            </small>
			
            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			-->
        </div>

        <!-- RIGHT -->
        <div class="d-flex align-items-center gap-2">
			<!--
			<h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			
            <span class="badge rounded-pill bg-success fs-6 px-4 py-2 shadow-sm"
                  id="control_no">
                
            </span>
			-->
				
            <button type="button"
                    class="btn btn-success rounded-3 shadow-sm "
                    data-bs-toggle="modal"
                    data-bs-target="#AddPaymentModal" 
					id="AddPaymentOrderProductBTN" 
					onclick="ResetPaymentForm()">

                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>

			<button type="button" 
					class="btn  rounded-3 shadow-sm bi bi-images" 
					data-bs-toggle="modal" 
					data-bs-target="#ViewPaymentGalery" 
					style="background-color: magenta;"
			></button>
						
        </div>

    </div>

</div>

        <!-- BODY -->

		<div class="card-body p-4">
						<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;">#</th>
							<th style="text-align:center !important;">Action</th>
							<th style="text-align:center !important;">Bank</th>
							<th style="text-align:center !important;">Date of Payment</th>
							<th style="text-align:center !important;">Reference No.</th>
							<th style="text-align:center !important;">Amount</th>	
						</tr>
						</thead>
							<tbody id="update_table_payment_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
		</div>


    </div> <!-- card -->

</div> <!-- tab-pane -->