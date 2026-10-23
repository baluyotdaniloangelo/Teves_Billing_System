<div class="tab-pane fade" id="lpg-kitchen" role="tabpanel" aria-labelledby="lpg-kitchen-tab">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h6 class="fw-bold mb-1">Kitchen Products &amp; Accessories</h6>
            <small class="text-muted">Stock movements from the Kitchen Tools &amp; Equipment product category. Use Add / View to access source records.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-kitchen"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    <p class="small text-muted mb-2">Ending = Beginning + Delivery + Damaged + B.O. − Swap issued − On-loan − Consignment − Product Expenses − Total Sold. Existing Return records remain included as additions.</p>
    <p class="small text-muted mb-3">Walk-in = Cash Sales. Total Sold = Sales Order + Cash Sales; deducted once. Swap uses the replacement item issued. Sales amounts use SRP before discounts.</p>
    <p class="small text-muted mb-2">Actual Ending Inventory = Beginning + Add − Less, calculated automatically. Variance = Actual − Theoretical.</p>
    <div class="alert alert-danger d-none" id="lpg-kitchen-error" role="alert"></div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="LpgKitchenTable" style="min-width:2150px;">
            <thead class="text-center">
                <tr>
                    <th rowspan="3" style="min-width:190px;">Item</th>
                    <th rowspan="2">Beginning Inventory</th>
                    <th colspan="3">Add (+)</th>
                    <th colspan="5">Less (-)</th>
                    <th rowspan="3" title="Automatically calculated: Sales Order quantity plus Cash Sales quantity">Total Sold</th>
                    <th rowspan="3">Unit Price</th>
                    <th rowspan="3">Total Sales<br>in Peso</th>
                    <th rowspan="3">Theoretical Ending<br>Inventory</th>
                    <th rowspan="3">Actual Ending<br>Inventory</th>
                    <th rowspan="3" title="Actual Ending Inventory minus Theoretical Ending Inventory">Variance</th>
                </tr>
                <tr>
                    @foreach(['DELIVERY' => 'Delivery', 'DAMAGED' => 'Damaged', 'BO_REPLACEMENT' => 'B.O. Replacement', 'SWAP' => 'Swap', 'ON_LOAN' => 'On-loan', 'CONSIGNMENT' => 'Consignment', 'RAFFLE' => 'Raffle / Product Expenses', 'WALK_IN' => 'Walk-in'] as $sourceMode => $sourceLabel)
                        <th>{{ $sourceLabel }}</th>
                    @endforeach
                </tr>
                <tr class="lpg-summary-actions" data-dt-order="disable">
                    @foreach(['BEGINNING', 'DELIVERY', 'DAMAGED', 'BO_REPLACEMENT', 'SWAP', 'ON_LOAN', 'CONSIGNMENT', 'RAFFLE', 'WALK_IN'] as $sourceMode)
                        <th>
                            @include('pages.cashiers_report_form_lpg.partials.kitchen_source_buttons', ['sourceMode' => $sourceMode])
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody><tr><td colspan="16" class="text-center text-muted py-4">Loading kitchen stock...</td></tr></tbody>
        </table>
    </div>
</div>
