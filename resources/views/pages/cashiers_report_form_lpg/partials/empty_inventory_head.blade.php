@php
    $showEmptyColumnActions = $withColumnActions ?? false;
    $emptyHeaderRows = $showEmptyColumnActions ? 4 : 3;
@endphp
<thead>
    <tr>
        <th rowspan="{{ $emptyHeaderRows }}">Item</th>
        <th rowspan="3">Beg.<br>Inv.</th>
        <th colspan="3">Add (+)</th>
        <th colspan="4">Less (-)</th>
        <th rowspan="{{ $emptyHeaderRows }}">Ending<br>Inventory</th>
        <th rowspan="{{ $emptyHeaderRows }}">Remarks</th>
    </tr>
    <tr>
        <th rowspan="2">Return<br>(On-Loan)</th>
        <th rowspan="2">Swap In</th>
        <th rowspan="2">Advance<br>Pull-Out</th>
        <th>Sold</th>
        <th rowspan="2">Borrow<br>(On-Loan)</th>
        <th rowspan="2">Delivery from<br>Advance Pull-Out</th>
        <th rowspan="2">Delivery<br>Truck</th>
    </tr>
    <tr>
        <th>Cylinder</th>
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
