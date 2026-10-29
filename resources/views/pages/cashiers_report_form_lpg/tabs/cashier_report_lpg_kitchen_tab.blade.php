<div class="tab-pane fade" id="lpg-kitchen" role="tabpanel" aria-labelledby="lpg-kitchen-tab">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h6 class="fw-bold mb-1">Kitchen Products &amp; Accessories</h6>
            <small class="text-muted">Stock movements from the Kitchen Tools &amp; Equipment product category. Use Add / View to access source records.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-kitchen"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    <p class="small text-muted mb-2">Ending Inventory = Beginning Inventory + Delivery − Total Sold. Set products are identified by their saved components in Product Details.</p>
    <p class="small text-muted mb-3">Total Sold includes Sales Orders and Cash Sales for that product. Sold from Set is a quantity only and does not deduct stock again.</p>
    <div class="alert alert-danger d-none" id="lpg-kitchen-error" role="alert"></div>
    <div class="alert alert-warning d-none" id="lpg-kitchen-set-snapshot-warning" role="status">Some older sales have no saved component list. Sold from Set quantities do not include those sales.</div>
    <ul class="nav nav-pills gap-2 mb-3" id="lpgKitchenSubTabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="lpg-kitchen-sets-tab" data-bs-toggle="tab" data-bs-target="#lpg-kitchen-sets" type="button" role="tab" aria-controls="lpg-kitchen-sets" aria-selected="true">Kitchen Set</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="lpg-kitchen-individual-tab" data-bs-toggle="tab" data-bs-target="#lpg-kitchen-individual" type="button" role="tab" aria-controls="lpg-kitchen-individual" aria-selected="false">Sold Separately</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="lpg-kitchen-regulators-tab" data-bs-toggle="tab" data-bs-target="#lpg-kitchen-regulators" type="button" role="tab" aria-controls="lpg-kitchen-regulators" aria-selected="false">New Regulators</button></li>
    </ul>
    <div class="tab-content">
    @foreach ([['table' => 'LpgKitchenSetsTable', 'title' => 'KITCHEN SET', 'pane' => 'lpg-kitchen-sets', 'tab' => 'lpg-kitchen-sets-tab', 'individual' => false], ['table' => 'LpgKitchenIndividualTable', 'title' => 'PRODUCTS SOLD INDIVIDUALLY', 'pane' => 'lpg-kitchen-individual', 'tab' => 'lpg-kitchen-individual-tab', 'individual' => true]] as $section)
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $section['pane'] }}" role="tabpanel" aria-labelledby="{{ $section['tab'] }}" tabindex="0">
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered align-middle mb-0" id="{{ $section['table'] }}" style="min-width: 1050px;">
            <thead class="text-center">
                <tr><th colspan="{{ $section['individual'] ? 9 : 8 }}">{{ $section['title'] }}</th></tr>
                <tr>
                    <th>Kitchen Products &amp; Accessories</th><th>Beginning Inventory</th><th>Delivery</th>@if ($section['individual'])<th title="Components included in sold sets; no additional sales amount or stock deduction">Sold from Set</th>@endif<th>Total Sold</th><th>Unit Price</th><th>Total Sales in Pesos</th><th>Theoretical Ending Inventory</th><th>Actual Ending Inventory</th>
                </tr>
                <tr class="lpg-summary-actions">
                    <th></th>
                    @foreach (['BEGINNING', 'DELIVERY'] as $sourceMode)
                        <th>@include('pages.cashiers_report_form_lpg.partials.kitchen_source_buttons', ['sourceMode' => $sourceMode])</th>
                    @endforeach
                    <th colspan="{{ $section['individual'] ? 6 : 5 }}"></th>
                </tr>
            </thead>
            <tbody><tr><td colspan="{{ $section['individual'] ? 9 : 8 }}" class="text-center text-muted py-3">Loading products...</td></tr></tbody>
        </table>
    </div>
    @if ($section['individual'])
    <details class="mt-3" hidden>
    <summary class="fw-semibold mb-2">Other stock movements and previous summary</summary>
    <p class="small text-muted">The previous summary below includes other movements. The new template above uses Beginning + Delivery − Total Sold.</p>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="LpgKitchenTable" style="min-width:2150px;">
            <thead class="text-center">
                <tr>
                    <th rowspan="3" style="min-width:190px;">Item</th>
                    <th rowspan="2">Beginning Inventory</th>
                    <th colspan="3">Add (+)</th>
                    <th colspan="4">Less (-)</th>
                    <th rowspan="3" title="Automatically calculated: Sales Order quantity plus Cash Sales quantity">Total Sold</th>
                    <th rowspan="3">Unit Price</th>
                    <th rowspan="3">Total Sales<br>in Peso</th>
                    <th rowspan="3">Theoretical Ending<br>Inventory</th>
                    <th rowspan="3">Actual Ending<br>Inventory</th>
                    <th rowspan="3" title="Actual Ending Inventory minus Theoretical Ending Inventory">Variance</th>
                </tr>
                <tr>
                    @foreach(['DELIVERY' => 'Delivery', 'DAMAGED' => 'Damaged', 'BO_REPLACEMENT' => 'B.O. Replacement', 'SWAP' => 'Swap', 'ON_LOAN' => 'On-loan', 'RAFFLE' => 'Raffle / Product Expenses', 'WALK_IN' => 'Walk-in'] as $sourceMode => $sourceLabel)
                        <th>{{ $sourceLabel }}</th>
                    @endforeach
                </tr>
                <tr class="lpg-summary-actions" data-dt-order="disable">
                    @foreach(['BEGINNING', 'DELIVERY', 'DAMAGED', 'BO_REPLACEMENT', 'SWAP', 'ON_LOAN', 'RAFFLE', 'WALK_IN'] as $sourceMode)
                        <th>
                            @include('pages.cashiers_report_form_lpg.partials.kitchen_source_buttons', ['sourceMode' => $sourceMode])
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody><tr><td colspan="15" class="text-center text-muted py-4">Loading kitchen stock...</td></tr></tbody>
        </table>
    </div>
    </details>
    @endif
    </div>
    @endforeach
    <div class="tab-pane fade" id="lpg-kitchen-regulators" role="tabpanel" aria-labelledby="lpg-kitchen-regulators-tab" tabindex="0">
        @include('pages.cashiers_report_form_lpg.partials.regulator_inventory')
    </div>
    </div>
</div>
