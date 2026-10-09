<!-- PRODUCT TAB -->
<div class="tab-pane fade "
     id="cashiers_report_car_care"
     role="tabpanel"
     aria-labelledby="cashiers_report_car_care">

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
                    data-bs-target="#CashierReportLubeCarCareModal" 
					id="AddCashierReportLubeCarCareModalBTN">

                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>

        </div>

    </div>

</div>

        <!-- BODY -->

<!-- =========================================================
FUEL CASHIER REPORT TABS
========================================================= -->

<div class="card-body">


	<table class="table table-hover align-middle" id="lube_and_car_care_products_table">
		<thead>
			<tr class='report'>
				<th style="text-align:center !important;"><i class="bi bi-hash" aria-hidden="true"></i>#</th>
				<th style="text-align:center !important;"><i class="bi bi-box-seam" aria-hidden="true"></i>Product</th>
				<th style="text-align:center !important;"><i class="bi bi-cart3" aria-hidden="true"></i>Quantity</th>
				<th style="text-align:center !important;"><i class="bi bi-tag" aria-hidden="true"></i>Price</th>
				<th style="text-align:center !important;"><i class="bi bi-cash-stack" aria-hidden="true"></i>Amount</th>
				<th style="text-align:center !important;" ><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
			</tr>
		</thead>		
		<tbody id="">
			<tr style="display: none;">
				<td>HIDDEN</td></tr>
			</tbody>	
	</table>


</div>


    </div> <!-- card -->

</div> <!-- tab-pane -->
