<!-- PRODUCT TAB -->
<div class="tab-pane fade show active"
     id="lpg_accounts_recievable"
     role="tabpanel"
     aria-labelledby="lpg_accounts_recievable">

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
			
            <button type="button"
					class="btn btn-success rounded-3 shadow-sm lpg-report-action-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#AddProductModal" 
					id="AddPurchaseOrderProductBTN">

                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>
			-->
        </div>

    </div>

</div>

        <!-- BODY -->

<!-- =========================================================
FUEL CASHIER REPORT TABS
========================================================= -->

<div class="card-body">

			<div align="right">
				<button type="button"
					class="btn btn-success rounded-3 shadow-sm lpg-report-action-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#CashierReportLpgArModal">

                <i class="bi bi-plus-circle me-1"></i>
                Add
				</button>
			</div>
			<br>

				<table  class="table table-hover align-middle dataTable display nowrap cell-border" id="LpgArTable">
					<thead>
							<tr class='report'>
								<th><i class="bi bi-hash" aria-hidden="true"></i>Item</th>
								<!--<th>Account Name</th>-->
								<th><i class="bi bi-person" aria-hidden="true"></i>Account Name</th>
								<th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>DR Number</th>
								<th><i class="bi bi-chat-left-text" aria-hidden="true"></i>Remarks</th>
								<th><i class="bi bi-cash-stack" aria-hidden="true"></i>Amount</th>
								<th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
							</tr>
					</thead>
					<tbody>
							
					</tbody>
				</table>
</div>


    </div> <!-- card -->

</div> <!-- tab-pane -->
