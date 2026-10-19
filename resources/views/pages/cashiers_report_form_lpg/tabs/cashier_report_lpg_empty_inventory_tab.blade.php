<div class="tab-pane fade" id="lpg-empty-inventory" role="tabpanel" aria-labelledby="lpg-empty-inventory-tab">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h6 class="fw-bold mb-1">Empty Cylinders / Canisters</h6>
            <small class="text-muted">Empty-stock totals per product for this report. Quantities are in pieces.</small>
        </div>
        <button type="button" class="btn btn-success shadow-sm lpg-report-action-btn" id="add-lpg-empty-inventory">
            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add Empty Stock
        </button>
    </div>
    <div class="alert alert-danger d-none" id="lpg_empty_table_error" role="alert"></div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle cell-border" id="LpgEmptyInventoryTable" style="width:100%;min-width:1400px;">
            @include('pages.cashiers_report_form_lpg.partials.empty_inventory_head', ['withActions' => true])
            <tbody></tbody>
        </table>
    </div>
</div>
<style>
    #LpgEmptyInventoryTable thead th { white-space: normal; text-align: center; vertical-align: middle; }
    #LpgEmptyInventoryTable td:first-child { min-width: 180px; }
    #LpgEmptyInventoryTable .lpg-empty-group-row td { --bs-table-bg: #fff3cd; --bs-table-color: #114447; font-weight: 700; }
    #LpgEmptyInventoryTable .lpg-empty-total-row td { --bs-table-bg: #ECECEC; font-weight: 700; }
</style>
