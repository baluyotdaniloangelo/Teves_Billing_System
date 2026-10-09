<!-- LPG ACCOUNTS RECEIVABLE / SALES ORDER TAB -->
<div class="tab-pane fade"
     id="lpg_ar_so"
     role="tabpanel"
     aria-labelledby="lpg_ar_so-tab">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">
                        LPG Cashier Report
                    </small>
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-receipt-cutoff text-success me-2"></i>
                        Sales Order - Account Receivable / Returned
                    </h5>
                </div>

                <button type="button"
                        class="btn btn-success shadow-sm lpg-report-action-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#CashierReportLpgArSoModal">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle dataTable display nowrap cell-border"
                       id="LpgArSoTable"
                       style="width:100%;">
                    <thead>
                        <tr class="report">
                            <th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
                            <th><i class="bi bi-tag" aria-hidden="true"></i>Type</th>
                            <th><i class="bi bi-person" aria-hidden="true"></i>Account Name</th>
                            <th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>DR Number</th>
                            <th><i class="bi bi-box-seam" aria-hidden="true"></i>Item</th>
                            <th><i class="bi bi-cart3" aria-hidden="true"></i>QTY.</th>
                            <th><i class="bi bi-tag" aria-hidden="true"></i>SRP</th>
                            <th><i class="bi bi-tag-fill" aria-hidden="true"></i>Discounted Price</th>
                            <th><i class="bi bi-currency-exchange" aria-hidden="true"></i>Less / Unit</th>
                            <th><i class="bi bi-percent" aria-hidden="true"></i>Total Discount</th>
                            <th><i class="bi bi-cash-stack" aria-hidden="true"></i>Net Amount</th>
                            <th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
</div>
