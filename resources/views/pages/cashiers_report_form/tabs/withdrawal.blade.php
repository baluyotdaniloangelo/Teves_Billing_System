<!-- PRODUCT TAB -->
<div class="tab-pane fade"
     id="withdrawal"
     role="tabpanel"
     aria-labelledby="withdrawal">

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
                    class="btn btn-success rounded-3 shadow-sm fuel-report-action-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#AddProductDeliveryModal" 
					onclick="ResetDeliveryForm()">

                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>
			
			<button type="button" 
			class="btn btn-dark rounded-3 shadow-sm fuel-report-action-btn" 
			id="PrintPurchaseOrderDeliveyStatus" 
			title="Print Purchase Order Delivered Item / Status">
				<i class="bi bi-printer-fill"></i>Print
			</button>
        </div>

    </div>

</div>

        <!-- BODY -->

		<div class="card-body p-4">
						<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;" class=""><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
							<th style="text-align:center !important;"><i class="bi bi-hash" aria-hidden="true"></i>Item #</th>
							<th style="text-align:center !important;"><i class="bi bi-calendar3" aria-hidden="true"></i>Date</th>
							<th style="text-align:center !important;"><i class="bi bi-box-seam" aria-hidden="true"></i>Product</th>
							<th style="text-align:center !important;"><i class="bi bi-cart3" aria-hidden="true"></i>Quantity</th>
							<th style="text-align:center !important;"><i class="bi bi-tag" aria-hidden="true"></i>Price</th>
							<th style="text-align:center !important;"><i class="bi bi-cash-stack" aria-hidden="true"></i>Amount</th>
						</tr>
						</thead>
							<tbody id="product_list_delivery_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
		</div>


    </div> <!-- card -->

</div> <!-- tab-pane -->
