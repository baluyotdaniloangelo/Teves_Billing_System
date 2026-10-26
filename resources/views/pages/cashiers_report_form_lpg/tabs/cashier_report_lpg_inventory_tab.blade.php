<div class="tab-pane fade" id="inventory_tab" role="tabpanel" aria-labelledby="inventory-tab">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <small class="text-muted text-uppercase fw-semibold">LPG Cashier Report</small>
            <h5 class="fw-bold mb-0"><i class="bi bi-boxes text-success me-2" aria-hidden="true"></i>Inventory &amp; Transactions</h5>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="lpgInventorySubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="lpg-beginning-inventory-tab" data-bs-toggle="tab" data-bs-target="#lpg-beginning-inventory" type="button" role="tab" aria-controls="lpg-beginning-inventory" aria-selected="true">
                        <i class="bi bi-box-arrow-in-down me-1" aria-hidden="true"></i>Beginning Inventory
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lpg-delivery-tab" data-bs-toggle="tab" data-bs-target="#lpg-delivery" type="button" role="tab" aria-controls="lpg-delivery" aria-selected="false">
                        <i class="bi bi-truck me-1" aria-hidden="true"></i>Delivery
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lpg-bo-replacement-tab" data-bs-toggle="tab" data-bs-target="#lpg-bo-replacement" type="button" role="tab" aria-controls="lpg-bo-replacement" aria-selected="false">
                        <i class="bi bi-arrow-repeat me-1" aria-hidden="true"></i>B.O. Replacement
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lpg-swapping-tab" data-bs-toggle="tab" data-bs-target="#lpg-swapping" type="button" role="tab" aria-controls="lpg-swapping" aria-selected="false">
                        <i class="bi bi-arrow-left-right me-1" aria-hidden="true"></i>Swapping
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lpg-on-loan-tab" data-bs-toggle="tab" data-bs-target="#lpg-on-loan" type="button" role="tab" aria-controls="lpg-on-loan" aria-selected="false">
                        <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>On-Loan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lpg_ar_so-tab" data-bs-toggle="tab" data-bs-target="#lpg_ar_so" type="button" role="tab" aria-controls="lpg_ar_so" aria-selected="false">
                        <i class="bi bi-receipt-cutoff me-1" aria-hidden="true"></i>Sales Order / AR / Returned / Cash Sales
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                @include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_ar_so_tab')
                <div class="tab-pane fade show active" id="lpg-beginning-inventory" role="tabpanel" aria-labelledby="lpg-beginning-inventory-tab">
                    <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between gap-3" id="lpg-starting-audit-status" data-report-id="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">
                        <span><i class="bi bi-info-circle me-2"></i><strong>Starting Audit:</strong> <span class="lpg-audit-status-label">Checking status…</span></span>
                        <button type="button" class="btn btn-sm btn-primary d-none" id="lpg-submit-starting-audit"><i class="bi bi-send me-1"></i>Submit for Approval</button>
                    </div>
                    <div class="alert alert-warning d-none" id="lpg-starting-audit-return-reason"></div>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-success shadow-sm lpg-report-action-btn lpg-inventory-add" data-inventory-mode="BEGINNING">
                            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Starting Audit
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle dataTable display nowrap cell-border" id="LpgBeginningInventoryTable" style="width:100%;">
                            <thead>
                                <tr class="report">
                                    <th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
                                    <th><i class="bi bi-box-seam" aria-hidden="true"></i>Item</th>
                                    <th><i class="bi bi-123" aria-hidden="true"></i>Filled Cylinders</th>
                                    <th>B.O. Returned from Customers</th>
                                    <th>B.O. During Delivery</th>
                                    <th>Audit Date</th>
                                    <th><i class="bi bi-chat-left-text" aria-hidden="true"></i>Remarks</th>
                                    <th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="lpg-delivery" role="tabpanel" aria-labelledby="lpg-delivery-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-success shadow-sm lpg-report-action-btn lpg-inventory-add" data-inventory-mode="DELIVERY">
                            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add Delivery
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle dataTable display nowrap cell-border" id="LpgDeliveryTable" style="width:100%;">
                            <thead>
                                <tr class="report">
                                    <th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
                                    <th><i class="bi bi-truck" aria-hidden="true"></i>Supplier</th>
                                    <th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>DR / Reference No.</th>
                                    <th><i class="bi bi-box-seam" aria-hidden="true"></i>Item</th>
                                    <th><i class="bi bi-123" aria-hidden="true"></i>Delivered Quantity</th>
                                    <th><i class="bi bi-chat-left-text" aria-hidden="true"></i>Remarks</th>
                                    <th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="lpg-bo-replacement" role="tabpanel" aria-labelledby="lpg-bo-replacement-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-success shadow-sm lpg-report-action-btn lpg-inventory-add" data-inventory-mode="BO_REPLACEMENT">
                            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add B.O. Replacement
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle dataTable display nowrap cell-border" id="LpgBoReplacementTable" style="width:100%;">
                            <thead>
                                <tr class="report">
                                    <th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
                                    <th><i class="bi bi-truck" aria-hidden="true"></i>Supplier</th>
                                    <th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>DR / Reference No.</th>
                                    <th><i class="bi bi-box-seam" aria-hidden="true"></i>Replacement Item</th>
                                    <th><i class="bi bi-123" aria-hidden="true"></i>Replacement Quantity</th>
                                    <th><i class="bi bi-chat-left-text" aria-hidden="true"></i>Remarks</th>
                                    <th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="lpg-swapping" role="tabpanel" aria-labelledby="lpg-swapping-tab">
                    @include('pages.cashiers_report_form_lpg.partials.regulator_inventory')
                    <h6 class="fw-bold mb-2">Swapping Transactions</h6>
                    <p class="small text-muted mb-2">Grouped by customer and item. Received and replaced quantities are totaled separately; use View Entries to manage the individual records.</p>
                    <div class="d-flex justify-content-end flex-wrap gap-2 mb-3">
                        <button type="button" class="btn btn-success shadow-sm lpg-report-action-btn" id="add-lpg-swapping"><i class="bi bi-box-arrow-in-down me-1"></i>Add Received</button>
                        <button type="button" class="btn btn-success shadow-sm lpg-report-action-btn" id="add-lpg-swapping-replaced"><i class="bi bi-box-arrow-up-right me-1"></i>Add Replaced / Issued</button>
                    </div>
                    <div class="table-responsive"><table class="table table-hover align-middle dataTable display nowrap cell-border" id="LpgSwappingTable" style="width:100%;"><thead><tr class="report"><th>#</th><th>Customer</th><th>Received Item</th><th>Received Qty</th><th>Replaced Item</th><th>Replaced Qty</th><th>Remarks</th><th>Action</th></tr></thead><tbody></tbody></table></div>
                </div>
                <div class="tab-pane fade" id="lpg-on-loan" role="tabpanel" aria-labelledby="lpg-on-loan-tab">
                    <div class="d-flex justify-content-end mb-3"><button type="button" class="btn btn-success shadow-sm lpg-report-action-btn" id="add-lpg-on-loan"><i class="bi bi-plus-circle me-1"></i>Add On-Loan</button></div>
                    <div class="table-responsive"><table class="table table-hover align-middle dataTable display nowrap cell-border" id="LpgOnLoanTable" style="width:100%;"><thead><tr class="report"><th>#</th><th>Customer</th><th>DR No.</th><th>Borrow</th><th>Return</th><th>Remarks</th><th>Action</th></tr></thead><tbody></tbody></table></div>
                </div>
            </div>
        </div>
    </div>
</div>


