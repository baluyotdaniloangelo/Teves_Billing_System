<div class="tab-pane fade" id="lpg-empty-inventory" role="tabpanel" aria-labelledby="lpg-empty-inventory-tab">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h6 class="fw-bold mb-1">Empty Cylinders / Canisters</h6>
            <small class="text-muted">Use Add / View to access source transactions. Quantities are in pieces.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-empty-inventory"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    <p class="small text-muted mb-2">Ending Inventory = Beginning + Return (On-Loan) + Swap In + Advance Pull-Out − Cylinder Sold − Borrow (On-Loan) − Delivery from Advance Pull-Out − Delivery Truck.</p>
    <p class="small text-muted mb-3">Return, Swap In, Cylinder Sold, and Borrow follow their source transactions. Advance Pull-Out, its delivery, and Delivery Truck are entered manually. Refill / Sell-out and the removed branch/swap-out columns are hidden from this template.</p>
    <div class="alert alert-warning small d-none" id="lpg-empty-pending-notice"><span id="lpg-empty-pending-message"></span> <button type="button" class="btn btn-sm btn-light" id="lpg-empty-review-sources">Review Sources</button></div>
    <div class="alert alert-danger d-none" id="lpg_empty_table_error" role="alert"></div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle cell-border" id="LpgEmptyInventoryTable" style="width:100%;min-width:1800px;">
            @include('pages.cashiers_report_form_lpg.partials.empty_inventory_head', ['withActions' => true, 'withColumnActions' => true])
            <tbody></tbody>
        </table>
    </div>
</div>
<style>
    #LpgEmptyInventoryTable thead th { white-space: normal; text-align: center; vertical-align: middle; }
    #LpgEmptyInventoryTable td:first-child { min-width: 180px; }
    #LpgEmptyInventoryTable .lpg-empty-group-row td { --bs-table-bg: var(--lpg-accent-soft); --bs-table-color: var(--lpg-primary); font-weight: 700; }
    #LpgEmptyInventoryTable .lpg-empty-total-row td { --bs-table-bg: var(--lpg-surface); --bs-table-color: var(--lpg-primary); font-weight: 700; }
</style>
