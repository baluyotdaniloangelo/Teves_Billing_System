@php
    $showEmptyColumnActions = $withColumnActions ?? false;
    $emptyHeaderRows = $showEmptyColumnActions ? 4 : 3;
@endphp
<thead>
    <tr>
        <th rowspan="{{ $emptyHeaderRows }}">Item</th>
        <th rowspan="3">Beg.<br>Inv.</th>
        <th colspan="4">Add (+)</th>
        <th colspan="5">Less (-)</th>
        <th rowspan="{{ $emptyHeaderRows }}">Ending<br>Inventory</th>
        <th rowspan="{{ $emptyHeaderRows }}">Remarks</th>
    </tr>
    <tr>
        <th colspan="4">Empty From</th>
        <th>Sold</th>
        <th rowspan="2">Swap<br>Out</th>
        <th rowspan="2">Borrow</th>
        <th colspan="2">Pull Out</th>
    </tr>
    <tr>
        <th>Refill /<br>Sell-out</th>
        <th>Return</th>
        <th>Swap In</th>
        <th>From Other<br>Branch</th>
        <th>Cylinder</th>
        <th>Other<br>Branch</th>
        <th>Delivery<br>Truck</th>
    </tr>
    @if($showEmptyColumnActions)
        <tr class="lpg-summary-actions" data-dt-order="disable">
            @foreach(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS as $quantityField => $quantityLabel)
                <th>
                    @include('pages.cashiers_report_form_lpg.partials.empty_inventory_source_buttons', ['quantityField' => $quantityField])
                </th>
            @endforeach
        </tr>
    @endif
</thead>
