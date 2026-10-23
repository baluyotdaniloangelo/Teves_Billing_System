<div class="section-title">EMPTY CYLINDERS / CANISTERS</div>
<p class="note" style="margin:4px 0;">Quantities in pieces. Ending = Beginning + Additions - Deductions - Linked receipt overlap. A refill receipt linked to Swapping appears in both columns and counts once in ending stock.</p>
<table class="empty-inventory-print">
    @include('pages.cashiers_report_form_lpg.partials.empty_inventory_head', ['withActions' => false])
    <tbody>
        @forelse($emptyInventoryRows->groupBy('capacity_group') as $capacity => $rows)
            <tr><td colspan="13" style="background:#fff3cd;font-weight:bold;">{{ $capacity }}</td></tr>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->product_name ?: '-' }}</td>
                    @foreach(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS as $field => $label)
                        <td class="num">{{ number_format((int) $row->{$field}) }}</td>
                    @endforeach
                    <td class="num" style="font-weight:bold;">{{ number_format($row->ending_quantity) }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @endforeach
            <tr style="background:#ececec;font-weight:bold;">
                <td>Total</td>
                @foreach(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS as $field => $label)
                    <td class="num">{{ number_format($rows->sum($field)) }}</td>
                @endforeach
                <td class="num">{{ number_format($rows->sum('ending_quantity')) }}</td>
                <td></td>
            </tr>
        @empty
            <tr><td colspan="13" class="empty">No empty-stock inventory recorded for this report.</td></tr>
        @endforelse
    </tbody>
</table>
