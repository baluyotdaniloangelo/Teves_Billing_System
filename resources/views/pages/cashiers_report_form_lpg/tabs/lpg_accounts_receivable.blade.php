<!-- PRODUCT TAB -->
<div class="tab-pane fade"
     id="lpg_accounts_recievable"
     role="tabpanel"
     aria-labelledby="lpg_accounts_recievable-tab">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">LPG Cashier Report</small>
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person-lines-fill text-success me-2" aria-hidden="true"></i>
                        Accounts Receivable
                    </h5>
                </div>

                <button type="button"
                        class="btn btn-success rounded-3 shadow-sm lpg-report-action-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#CashierReportLpgArModal">
                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                    Add
                </button>
            </div>
        </div>

        <!-- BODY -->

<!-- =========================================================
FUEL CASHIER REPORT TABS
========================================================= -->

<div class="card-body">


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
