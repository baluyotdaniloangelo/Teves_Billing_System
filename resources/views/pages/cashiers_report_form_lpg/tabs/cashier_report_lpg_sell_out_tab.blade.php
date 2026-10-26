<div class="tab-pane fade show active" id="lpg-sell-out" role="tabpanel" aria-labelledby="lpg-sell-out-tab">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h6 class="fw-bold mb-1">LPG Sell-out</h6>
            <small class="text-muted">Beginning Inventory is set by a starting audit. Later shifts carry forward the previous ending balance.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-sell-out"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    <p class="small text-muted mb-2">Filled stock and B.O. stock are tracked separately. B.O. Replacement and B.O. Returned to Supplier reduce the combined B.O. balance; delivery B.O. is reduced first.</p>
    <p class="small text-muted mb-2">Refill / Swap, Cylinder Sold with Content, and On-Loan are deducted from Filled Cylinders. Sales amounts use SRP before discounts.</p>
    <div class="alert alert-danger d-none" role="alert" id="lpg-sell-out-error"></div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="LpgSellOutTable" style="min-width:3100px;">
            <thead class="text-center">
                <tr>
                    <th rowspan="4" style="min-width:230px;">Item / Capacity</th>
                    <th colspan="3">Beginning Inventory</th>
                    <th colspan="4">Add (+)</th>
                    <th colspan="8">Less (-)</th>
                    <th colspan="3">Theoretical Ending Inventory</th>
                    <th colspan="3">Actual Ending Inventory</th>
                    <th rowspan="4">Total Sales<br>in Pesos</th>
                </tr>
                <tr>
                    <th rowspan="2">Filled Cylinders</th>
                    <th rowspan="2">B.O. Returned<br>from Customers</th>
                    <th rowspan="2">B.O. During<br>Delivery</th>
                    <th rowspan="2">Suppliers<br>Delivery</th>
                    <th rowspan="2">B.O.<br>Replacement</th>
                    <th rowspan="2">B.O. From<br>Customer/s</th>
                    <th rowspan="2">B.O. During<br>Delivery</th>
                    <th rowspan="2">B.O. Returned<br>to Supplier</th>
                    <th rowspan="2">On-Loan</th>
                    <th colspan="3">Total Refill / Swap</th>
                    <th colspan="3">Total Cylinder Sold with Content</th>
                    <th rowspan="2">Filled Cylinders</th>
                    <th rowspan="2">B.O. Returned<br>from Customers</th>
                    <th rowspan="2">B.O. During<br>Delivery</th>
                    <th rowspan="2">Filled Cylinders</th>
                    <th rowspan="2">B.O. Returned<br>from Customers</th>
                    <th rowspan="2">B.O. During<br>Delivery</th>
                </tr>
                <tr>
                    <th>Qty</th><th>Unit Price</th><th>Amount</th>
                    <th>Qty</th><th>Unit Price</th><th>Amount</th>
                </tr>
                <tr class="lpg-summary-actions" data-dt-order="disable">
                    <th colspan="3">@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'BEGINNING'])</th>
                    <th>@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'DELIVERY'])</th>
                    <th>@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'BO_REPLACEMENT'])</th>
                    <th>@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'BO_FROM_CUSTOMER'])</th>
                    <th>@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'BO_DURING_DELIVERY'])</th>
                    <th>@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'BO_RETURNED_TO_SUPPLIER'])</th>
                    <th>@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'ON_LOAN'])</th>
                    <th colspan="3">@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'REFILL_SWAP'])</th>
                    <th colspan="3">@include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => 'CYLINDER_WITH_CONTENT'])</th>
                    <th colspan="7" class="bg-body-secondary"></th>
                </tr>
            </thead>
            <tbody><tr><td colspan="23" class="text-center text-muted py-4">Loading sell-out summary...</td></tr></tbody>
        </table>
    </div>
</div>
