@php
    $showRegulatorInventoryDetails = (bool) config('lpg_inventory.regulator_inventory_details_enabled', false);
@endphp
<div class="section-title">{{ $showRegulatorInventoryDetails ? 'SWAPPED / OLD REGULATORS — INVENTORY' : 'REGULATOR SWAPPING TOTALS' }}</div>
@if($showRegulatorInventoryDetails)
    <p class="note">Ending = Beginning + Swap Received + Other Received - Swap Issued - Other Issued. Balances are per product; old and new regulator balances are separate only when they use separate products. Other movements include deliveries, B.O., returns, loans, sales and product expenses.</p>
@endif
<table>
    <thead><tr><th>Item</th>@if($showRegulatorInventoryDetails)<th>Beginning Inventory</th>@endif<th>Swap Received</th><th>Swap Issued</th>@if($showRegulatorInventoryDetails)<th>Other Received</th><th>Other Issued</th><th>Ending Inventory</th>@endif</tr></thead>
    <tbody>
        @forelse($regulatorInventoryRows as $row)
            <tr>
                <td>{{ $row['product_name'] }}</td>
                @if($showRegulatorInventoryDetails)<td class="num">{{ $row['beginning_count'] ? $qty($row['beginning_quantity']) : 'Not set' }}</td>@endif
                <td class="num">{{ $qty($row['swap_in_quantity']) }}</td>
                <td class="num">{{ $qty($row['swap_out_quantity']) }}</td>
                @if($showRegulatorInventoryDetails)
                    <td class="num">{{ $qty($row['other_in_quantity']) }}</td>
                    <td class="num">{{ $qty($row['other_out_quantity']) }}</td>
                    <td class="num">{{ $row['ending_quantity'] === null ? 'Set beginning stock' : $qty($row['ending_quantity']) }}</td>
                @endif
            </tr>
        @empty
            <tr><td colspan="{{ $showRegulatorInventoryDetails ? 7 : 3 }}" class="empty">No regulator products configured.</td></tr>
        @endforelse
    </tbody>
</table>
