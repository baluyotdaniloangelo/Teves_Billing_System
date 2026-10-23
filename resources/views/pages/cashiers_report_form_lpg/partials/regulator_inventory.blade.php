@php
    $showRegulatorInventoryDetails = (bool) config('lpg_inventory.regulator_inventory_details_enabled', false);
    $regulatorColumns = $showRegulatorInventoryDetails ? 7 : 3;
    $regulatorModes = $showRegulatorInventoryDetails ? ['BEGINNING', 'SWAP_IN', 'SWAP_OUT', 'OTHER_IN', 'OTHER_OUT'] : ['SWAP_IN', 'SWAP_OUT'];
@endphp
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h6 class="fw-bold mb-1">{{ $showRegulatorInventoryDetails ? 'Swapped / Old Regulators — Inventory' : 'Regulator Swapping Totals' }}</h6>
            <small class="text-muted">Received and issued quantities are automatically totaled per product from this report's swapping records.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-regulators"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    @if($showRegulatorInventoryDetails)
        <p class="small text-muted mb-2">Ending = Beginning + Swap Received + Other Received − Swap Issued − Other Issued. Other Received includes Delivery, B.O. and returns; Other Issued includes sales, loans and product expenses.</p>
    @endif
    <div class="alert alert-danger d-none" id="lpg-regulator-error" role="alert"></div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="LpgRegulatorInventoryTable" style="width:100%;min-width:{{ $showRegulatorInventoryDetails ? '1100px' : '520px' }};">
            <thead class="text-center">
                <tr>
                    <th rowspan="2">Item</th>
                    @if($showRegulatorInventoryDetails)<th>Beginning Inventory</th>@endif
                    <th>Swap Received</th>
                    <th>Swap Issued</th>
                    @if($showRegulatorInventoryDetails)
                        <th>Other Received</th>
                        <th>Other Issued</th>
                        <th rowspan="2">Ending Inventory</th>
                    @endif
                </tr>
                <tr class="lpg-summary-actions" data-dt-order="disable">
                    @foreach($regulatorModes as $regulatorMode)
                        <th>
                            <div class="d-flex flex-nowrap justify-content-center align-items-center gap-1 lpg-summary-source-buttons">
                                @if(in_array($regulatorMode, ['BEGINNING', 'SWAP_IN', 'SWAP_OUT'], true))
                                    <button type="button" class="btn btn-sm btn-light lpg-regulator-add" data-source-mode="{{ $regulatorMode }}"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add</button>
                                @endif
                                <button type="button" class="btn btn-sm btn-light lpg-regulator-view" data-source-mode="{{ $regulatorMode }}"><i class="bi bi-eye me-1" aria-hidden="true"></i>View</button>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody><tr><td colspan="{{ $regulatorColumns }}" class="text-center text-muted py-3">Loading regulator totals...</td></tr></tbody>
        </table>
    </div>
</div>
