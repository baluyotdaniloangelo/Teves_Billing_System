@php
    $showUnclassifiedSales = (bool) config('lpg_inventory.show_unclassified_sales', false);
@endphp
<div class="tab-pane fade show active" id="lpg-sell-out" role="tabpanel" aria-labelledby="lpg-sell-out-tab">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h6 class="fw-bold mb-1">LPG Sell-out</h6>
            <small class="text-muted">Quantities come from this report's source records. Use Add or View under each linked column.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-sell-out"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    <p class="small text-muted mb-2">Ending Inventory = Beginning + Delivery + Return + B.O. − On-Loan − all Sales − product Expenses. Automatically updated from source records. Sales amounts use SRP before discounts.</p>
    <p class="small text-muted mb-2">Actual Ending Inventory = Beginning + Add − Less, calculated automatically. Variance = Actual − Theoretical.</p>
    <div class="alert alert-warning small d-none" id="lpg-sell-out-unclassified">Some older sales have no sale classification. Their quantities are included in sales deductions. Edit these records under Inventory &amp; Transactions → Sales Order / AR / Returned / Cash Sales to classify them.</div>
    <div class="alert alert-danger d-none" role="alert" id="lpg-sell-out-error"></div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="LpgSellOutTable" style="min-width:2250px;">
            <thead class="text-center">
                <tr>
                    <th rowspan="4" style="min-width:200px;">Item</th>
                    <th rowspan="3">Beginning Inventory</th>
                    <th colspan="3">Add (+)</th>
                    <th colspan="{{ $showUnclassifiedSales ? 9 : 8 }}">Less (-)</th>
                    <th rowspan="4">Total Sales<br>in Peso</th>
                    <th rowspan="4">Theoretical Ending<br>Inventory</th>
                    <th rowspan="4">Actual Ending<br>Inventory</th>
                    <th rowspan="4" title="Actual Ending Inventory minus Theoretical Ending Inventory">Variance</th>
                </tr>
                <tr>
                    <th rowspan="2">Delivery</th>
                    <th rowspan="2">Return</th>
                    <th rowspan="2">B.O. Replacement</th>
                    <th rowspan="2">On-Loan</th>
                    <th colspan="3">Total Refill / Swap</th>
                    <th colspan="3">Cylinder Sold with Content</th>
                    @if($showUnclassifiedSales)<th rowspan="2">Unclassified Sales<br><small>Qty</small></th>@endif
                    <th rowspan="2">Other Stock-Out<br><small>Product Expenses / Free Items</small></th>
                </tr>
                <tr><th>Qty</th><th>Unit Price</th><th>Amount</th><th>Qty</th><th>Unit Price</th><th>Amount</th></tr>
                <tr class="lpg-summary-actions" data-dt-order="disable">
                    @foreach(['BEGINNING' => 1, 'DELIVERY' => 1, 'RETURN' => 1, 'BO_REPLACEMENT' => 1, 'ON_LOAN' => 1, 'REFILL_SWAP' => 3, 'CYLINDER_WITH_CONTENT' => 3, 'UNCLASSIFIED' => 1, 'EXPENSE' => 1] as $sourceMode => $columnSpan)
                        @if($showUnclassifiedSales || $sourceMode !== 'UNCLASSIFIED')
                            <th colspan="{{ $columnSpan }}">
                                @include('pages.cashiers_report_form_lpg.partials.sell_out_source_buttons', ['sourceMode' => $sourceMode])
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody><tr><td colspan="{{ $showUnclassifiedSales ? 18 : 17 }}" class="text-center text-muted py-4">Loading sell-out summary...</td></tr></tbody>
        </table>
    </div>
</div>
