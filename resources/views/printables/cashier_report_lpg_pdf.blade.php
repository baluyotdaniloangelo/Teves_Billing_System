<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>LPG Cashier Report {{ $report->cashiers_report_no ?? $report->cashiers_report_id }}</title>
    <style>
        @page { size: 8.5in 13in; margin: 8mm 6mm; }
        body { margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 6.5pt; color: #111; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead { display: table-header-group; }
        th, td { border: 0.5pt solid #7a7a7a; padding: 3pt 2pt; vertical-align: middle; }
        th { background: #438f26; color: #fff; text-align: center; font-size: 5pt; line-height: 1.2; font-weight: bold; }
        th.title { font-size: 7pt; }
        th.small { font-size: 4.6pt; }
        td { background: #fff; line-height: 1.2; overflow-wrap: break-word; }
        tr { page-break-inside: avoid; }
        td.number { text-align: right; white-space: nowrap; font-size: 6pt; }
        td.capacity { text-align: center; white-space: nowrap; font-size: 6pt; }
        td.group { position: relative; text-align: center; font-size: 5.8pt; font-weight: bold; word-wrap: break-word; background: #f0f5ec; }
        .group-label { display: block; position: relative; left: -27pt; width: 74pt; transform: rotate(-90deg); transform-origin: 50% 50%; }
        td.empty { text-align: center; padding: 12pt; }
        .note { margin-top: 5pt; font-size: 6pt; color: #444; line-height: 1.4; }
        .preview { font-weight: bold; }
        .kitchen-report { margin-top: 9pt; }
        .kitchen-report th { background: #8bd36f; color: #111; font-size: 4.35pt; }
        .kitchen-report th.title { font-size: 6pt; }
        .kitchen-report td { font-size: 5.15pt; padding: 2.4pt 1.5pt; }
        .kitchen-report td.group { font-size: 4.8pt; }
        .kitchen-report td.number { font-size: 4.9pt; }
        .kitchen-report th, .kitchen-report td { border-color: #222; }
        .detail-report { margin-top: 8pt; page-break-inside: avoid; }
        .detail-report th { background: #8bd36f; color: #111; font-size: 5.2pt; }
        .detail-report th.title { font-size: 6.2pt; }
        .detail-report td { font-size: 5.6pt; padding: 2.5pt 2pt; }
        .detail-report td.number { font-size: 5.4pt; }
        .sales-summary { margin-top: 10pt; page-break-inside: avoid; }
        .sales-summary > table { border: 0.6pt solid #222; }
        .sales-summary th { background: #8bd36f; color: #111; font-size: 5.5pt; }
        .sales-summary th.title { font-size: 6.5pt; }
        .sales-summary td { font-size: 5.4pt; padding: 2.3pt 2pt; }
        .sales-summary td.number { font-size: 5.3pt; }
        .sales-summary .subhead { background: #dcebd6; font-weight: bold; text-align: center; }
        .sales-summary .grand { font-weight: bold; }
        .sales-summary .variance { background: #a9c9cb; font-weight: bold; }
        .empty-report, .swapping-report { margin-top: 10pt; }
        .empty-report th, .swapping-report th { background: #8bd36f; color: #111; font-size: 4.7pt; }
        .empty-report th.title, .swapping-report th.title { font-size: 6pt; }
        .empty-report td, .swapping-report td { font-size: 5pt; padding: 2.1pt 1.4pt; }
        .empty-report td.number, .swapping-report td.number { font-size: 4.8pt; }
        .empty-report .capacity-row { background: #fff2cc; font-weight: bold; }
        .empty-report .total-row { background: #e6b9b8; font-weight: bold; }
        .swapping-report .blank-row td { height: 10pt; }
    </style>
</head>
<body>
@php
    $quantity = static function ($value) {
        return $value === null ? '-' : rtrim(rtrim(number_format((float) $value, 2, '.', ','), '0'), '.');
    };
    $money = static function ($value) { return $value === null ? '-' : number_format((float) $value, 2); };
    $capacity = static function ($row) use ($quantity) {
        if (($row['product_capacity'] ?? null) !== null && ($row['product_capacity'] ?? '') !== '') {
            return $quantity($row['product_capacity']) . strtoupper($row['product_capacity_unit'] ?? '');
        }
        return $row['product_unit_measurement'] ?? '';
    };
    $containerItem = static function ($name, $capacity, $capacityUnit, $unit, $stockState) use ($quantity) {
        $label = trim((string) ($name ?? ''));
        $capacityLabel = ($capacity !== null && $capacity !== '') ? $quantity($capacity) . strtoupper($capacityUnit ?? '') : '';
        if ($capacityLabel !== '' && stripos($label, $capacityLabel) === false) $label = trim($label . ' ' . $capacityLabel);
        elseif ($capacityLabel === '' && !empty($unit) && stripos($label, (string) $unit) === false) $label = trim($label . ' ' . $unit);
        if (!empty($stockState)) $label .= ' (' . ucfirst(strtolower($stockState)) . ')';
        return $label;
    };
    $widths = [4, 13.5, 4, 6, 5, 4.5, 5, 4, 3, 5, 6, 3, 5, 6, 6, 6, 5, 4.5];
    $widthTotal = array_sum($widths);
    $columnWidth = static function ($index, $span = 1) use ($widths, $widthTotal) {
        return array_sum(array_slice($widths, $index, $span)) / $widthTotal * 100;
    };
    $groups = $sellOutRows->groupBy('print_group_key');
    $kitchenSections = [
        'SET' => $kitchenRows->filter(function ($row) { return !empty($row['is_set']); })->groupBy('main_product_name'),
        'PRODUCT SOLD INDIVIDUALLY' => $kitchenRows->filter(function ($row) { return empty($row['is_set']); })->groupBy('main_product_name'),
    ];
@endphp
<table>
    <colgroup>
        @foreach ($widths as $width)
            <col style="width: {{ $width / $widthTotal * 100 }}%">
        @endforeach
    </colgroup>
    <thead>
        <tr>
            <th style="width: {{ $columnWidth(0, 3) }}%" colspan="3" rowspan="3" class="title">LPG SELL-OUT</th>
            <th style="width: {{ $columnWidth(3) }}%" rowspan="3">BEGINNING<br>INVENTORY</th>
            <th style="width: {{ $columnWidth(4, 3) }}%" colspan="3">ADD</th>
            <th style="width: {{ $columnWidth(7, 7) }}%" colspan="7">LESS</th>
            <th style="width: {{ $columnWidth(14) }}%" rowspan="3">TOTAL<br>SALES IN<br>PESO</th>
            <th style="width: {{ $columnWidth(15) }}%" rowspan="3" class="small">THEORETICAL<br>ENDING<br>INVENTORY</th>
            <th style="width: {{ $columnWidth(16) }}%" rowspan="3">ACTUAL<br>END. INV.</th>
            <th style="width: {{ $columnWidth(17) }}%" rowspan="3" class="small">VARIANCE</th>
        </tr>
        <tr>
            <th style="width: {{ $columnWidth(4) }}%" rowspan="2">DELIVERY</th>
            <th style="width: {{ $columnWidth(5) }}%" rowspan="2">RETURN</th>
            <th style="width: {{ $columnWidth(6) }}%" rowspan="2">B.O.<br>REPLACE-<br>MENT</th>
            <th style="width: {{ $columnWidth(7) }}%" rowspan="2">ON-<br>LOAN</th>
            <th style="width: {{ $columnWidth(8, 3) }}%" colspan="3">TOTAL REFILL/SWAP</th>
            <th style="width: {{ $columnWidth(11, 3) }}%" colspan="3">TOTAL CYLINDER SOLD - W/<br>CONTENT</th>
        </tr>
        <tr>
            <th style="width: {{ $columnWidth(8) }}%">QTY.</th><th style="width: {{ $columnWidth(9) }}%">UNIT<br>PRICE</th><th style="width: {{ $columnWidth(10) }}%">AMOUNT</th>
            <th style="width: {{ $columnWidth(11) }}%">QTY.</th><th style="width: {{ $columnWidth(12) }}%">UNIT<br>PRICE</th><th style="width: {{ $columnWidth(13) }}%">AMOUNT</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($groups as $groupRows)
        @foreach ($groupRows->chunk(12) as $chunk)
            @foreach ($chunk as $row)
                <tr>
                    @if ($loop->first)
                        <td style="width: {{ $columnWidth(0) }}%" class="group" rowspan="{{ $chunk->count() }}"><span class="{{ $chunk->count() >= 5 ? 'group-label' : '' }}">{{ $row['print_group_name'] ?? '' }}</span></td>
                    @endif
                    <td style="width: {{ $columnWidth(1) }}%">{{ $row['product_name'] }}</td>
                    <td style="width: {{ $columnWidth(2) }}%" class="capacity">{{ $capacity($row) }}</td>
                    <td class="number">{{ $row['beginning_count'] ? $quantity($row['beginning_quantity']) : '-' }}</td>
                    <td class="number">{{ $quantity($row['delivery_quantity']) }}</td>
                    <td class="number">{{ $quantity($row['return_quantity']) }}</td>
                    <td class="number">{{ $quantity($row['bo_replacement_quantity']) }}</td>
                    <td class="number">{{ $quantity($row['on_loan_quantity']) }}</td>
                    <td class="number">{{ $quantity($row['refill_swap_quantity']) }}</td>
                    <td class="number">{{ $row['refill_swap_mixed_prices'] ? 'Mixed' : $money($row['refill_swap_unit_price']) }}</td>
                    <td class="number">{{ $money($row['refill_swap_amount']) }}</td>
                    <td class="number">{{ $quantity($row['cylinder_with_content_quantity']) }}</td>
                    <td class="number">{{ $row['cylinder_with_content_mixed_prices'] ? 'Mixed' : $money($row['cylinder_with_content_unit_price']) }}</td>
                    <td class="number">{{ $money($row['cylinder_with_content_amount']) }}</td>
                    <td class="number">{{ $money($row['total_sales']) }}</td>
                    <td class="number">{{ $quantity($row['theoretical_ending_quantity']) }}</td>
                    <td class="number">{{ $quantity($row['actual_quantity']) }}</td>
                    <td class="number">{{ $quantity($row['variance_quantity']) }}</td>
                </tr>
            @endforeach
        @endforeach
    @empty
        <tr><td colspan="18" class="empty">No LPG sell-out records for this report.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="kitchen-report">
    @foreach ($kitchenSections as $sectionTitle => $kitchenGroups)
    <table style="margin-top: 5pt;">
        @if ($sectionTitle === 'PRODUCT SOLD INDIVIDUALLY')
        <colgroup><col style="width: 5%"><col style="width: 27%"><col style="width: 8%"><col style="width: 8%"><col style="width: 7%"><col style="width: 7%"><col style="width: 8%"><col style="width: 10%"><col style="width: 10%"><col style="width: 10%"></colgroup>
        @else
        <colgroup><col style="width: 5%"><col style="width: 31%"><col style="width: 9%"><col style="width: 8%"><col style="width: 8%"><col style="width: 9%"><col style="width: 10%"><col style="width: 10%"><col style="width: 10%"></colgroup>
        @endif
        <thead>
            <tr><th colspan="{{ $sectionTitle === 'PRODUCT SOLD INDIVIDUALLY' ? 10 : 9 }}" class="title">{{ $sectionTitle }}</th></tr>
            <tr>
                <th colspan="2" class="title">KITCHEN PRODUCTS &amp; ACCESSORIES</th>
                <th>BEGINNING<br>INVENTORY</th><th>DELIVERY</th>
                @if ($sectionTitle === 'PRODUCT SOLD INDIVIDUALLY')
                    <th>SOLD FROM<br>SET</th>
                @endif
                <th>TOTAL SOLD</th><th>UNIT PRICE</th>
                <th>TOTAL SALES<br>IN PESOS</th><th>THEORETICAL ENDING<br>INVENTORY</th><th>ACTUAL ENDING<br>INVENTORY</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($kitchenGroups as $groupRows)
            @foreach ($groupRows->chunk(12) as $chunk)
                @foreach ($chunk as $row)
                    <tr>
                        @if ($loop->first)
                            <td class="group" rowspan="{{ $chunk->count() }}"><span class="{{ $chunk->count() >= 5 ? 'group-label' : '' }}">{{ $row['main_product_name'] ?? 'Kitchen Products & Accessories' }}</span></td>
                        @endif
                        <td>
                            {{ $row['product_name'] }}
                            @if (!empty($row['product_capacity']))
                                {{ $quantity($row['product_capacity']) }}{{ strtoupper($row['product_capacity_unit'] ?? '') }}
                            @endif
                            @foreach ($row['components'] ?? [] as $component)
                                <div>
                                    {{ $quantity($component['quantity']) }} x {{ $component['product_name'] }}
                                    @if (!empty($component['product_capacity']))
                                        {{ $quantity($component['product_capacity']) }}{{ strtoupper($component['product_capacity_unit'] ?? '') }}
                                    @endif
                                    @if (empty($component['is_active'])) (inactive) @endif
                                </div>
                            @endforeach
                        </td>
                        <td class="number">{{ !empty($row['beginning_count']) ? $quantity($row['beginning_quantity']) : '-' }}</td>
                        <td class="number">{{ !empty($row['delivery_count']) ? $quantity($row['delivery_quantity']) : '' }}</td>
                        @if ($sectionTitle === 'PRODUCT SOLD INDIVIDUALLY')
                            <td class="number">{{ ($row['sold_per_set_quantity'] ?? 0) > 0 ? rtrim(rtrim(number_format((float) $row['sold_per_set_quantity'], 5, '.', ','), '0'), '.') : '' }}</td>
                        @endif
                        <td class="number">{{ !empty($row['report_only']) ? '' : $quantity($row['total_sold_quantity'] ?? 0) }}</td>
                        <td class="number">{{ !empty($row['report_only']) ? '' : (!empty($row['mixed_prices']) ? 'Mixed' : $money($row['unit_price'] ?? null)) }}</td>
                        <td class="number">{{ !empty($row['report_only']) ? '' : $money($row['total_sales'] ?? 0) }}</td>
                        <td class="number">{{ $quantity($row['template_ending_quantity'] ?? null) }}</td>
                        <td class="number">{{ $quantity($row['template_actual_quantity'] ?? null) }}</td>
                    </tr>
                @endforeach
            @endforeach
        @empty
            <tr><td colspan="{{ $sectionTitle === 'PRODUCT SOLD INDIVIDUALLY' ? 10 : 9 }}" class="empty">{{ $sectionTitle === 'SET' ? 'No kitchen sets are configured.' : 'No individual kitchen products are configured.' }}</td></tr>
        @endforelse
        </tbody>
    </table>
    @endforeach
    @if ($kitchenUnknownSetSales > 0)
        <p class="small">Some older sales have no saved component list. Sold from Set quantities do not include those sales.</p>
    @endif
</div>
<div class="detail-report">
    <table>
        <thead>
            <tr><th colspan="5" class="title">ACCOUNTS RECEIVABLES COLLECTION</th></tr>
            <tr>
                <th style="width: 23%">Account Name</th>
                <th style="width: 17%">Account Number</th>
                <th style="width: 19%">DR Number's</th>
                <th style="width: 25%">Remarks</th>
                <th style="width: 16%">Amount Received</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($arCollectionRows as $row)
            <tr>
                <td>{{ $row->client_name ?? '' }}</td>
                <td>{{ $row->client_account_number ?? '' }}</td>
                <td>{{ $row->dr_number ?? '' }}</td>
                <td>{{ $row->remarks ?? '' }}</td>
                <td class="number">{{ $money($row->amount_received) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty">No accounts receivable collections recorded.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="detail-report">
    <table>
        <thead>
            <tr><th colspan="7" class="title">MISCELLANEOUS</th></tr>
            <tr><th colspan="7">Sales Order - Account Receivable / Returned</th></tr>
            <tr>
                <th style="width: 20%">Account Name</th>
                <th style="width: 14%">Account Number</th>
                <th style="width: 10%">DR Nos.</th>
                <th style="width: 28%">Item</th>
                <th style="width: 8%">QTY.</th>
                <th style="width: 10%">SRP</th>
                <th style="width: 10%">Amount</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($miscellaneousRows as $row)
            <tr>
                <td>{{ $row->client_name ?? '' }}</td>
                <td>{{ $row->client_account_number ?? '' }}</td>
                <td>{{ $row->dr_number ?? '' }}</td>
                <td>{{ $row->product_name ?? '' }} {{ $row->product_capacity !== null ? $quantity($row->product_capacity) . strtoupper($row->product_capacity_unit ?? '') : ($row->product_unit_measurement ?? '') }}</td>
                <td class="number">{{ $quantity($row->order_quantity) }}</td>
                <td class="number">{{ $money($row->unit_price) }}</td>
                <td class="number">{{ $money($row->order_total_amount) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">No sales order or return records.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="detail-report">
    <table>
        <thead>
            <tr><th colspan="7" class="title">EXPENSES</th></tr>
            <tr><th colspan="7">Cash Outs / Repair &amp; Maintenance / Raffle Prizes</th></tr>
            <tr>
                <th style="width: 19%">Account Name</th>
                <th style="width: 11%">Type</th>
                <th style="width: 20%">Purpose</th>
                <th style="width: 26%">Item/s</th>
                <th style="width: 8%">QTY.</th>
                <th style="width: 8%">SRP</th>
                <th style="width: 8%">Amount</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($expenseRows as $row)
            <tr>
                <td>{{ $row->client_name ?? '' }}</td>
                <td>{{ $row->expense_type ?? '' }}</td>
                <td>{{ $row->purpose ?? '' }}</td>
                <td>{{ $row->product_name ?? $row->item_description ?? '' }}{{ $row->product_name && $row->product_unit_measurement ? ' ' . $row->product_unit_measurement : '' }}</td>
                <td class="number">{{ $quantity($row->order_quantity) }}</td>
                <td class="number">{{ $money($row->unit_price) }}</td>
                <td class="number">{{ $money($row->amount) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">No expense records.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="detail-report">
    <table>
        <thead>
            <tr><th colspan="10" class="title">DISCOUNTS</th></tr>
            <tr>
                <th style="width: 13%">Account Name</th>
                <th style="width: 10%">Account Number</th>
                <th style="width: 8%">DR Nos.</th>
                <th style="width: 8%">Transaction Type</th>
                <th style="width: 20%">Item/s</th>
                <th style="width: 7%">QTY</th>
                <th style="width: 9%">SRP</th>
                <th style="width: 9%">Discounted Price</th>
                <th style="width: 8%">Discount</th>
                <th style="width: 8%">Total Discount</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($discountRows as $row)
            <tr>
                <td>{{ $row->client_name ?? '' }}</td>
                <td>{{ $row->client_account_number ?? '' }}</td>
                <td>{{ $row->dr_number ?? '' }}</td>
                <td>{{ $row->item_description === 'Sales Order' ? 'A.R.' : ($row->item_description === 'Cash Sales' ? 'Cash' : 'Return') }}</td>
                <td>{{ $row->product_name ?? '' }} {{ $row->product_capacity !== null ? $quantity($row->product_capacity) . strtoupper($row->product_capacity_unit ?? '') : ($row->product_unit_measurement ?? '') }}</td>
                <td class="number">{{ $quantity($row->order_quantity) }}</td>
                <td class="number">{{ $money($row->unit_price) }}</td>
                <td class="number">{{ $money($row->discounted_unit_price) }}</td>
                <td class="number">{{ $money($row->discount_per_unit) }}</td>
                <td class="number">{{ $money($row->total_discount) }}</td>
            </tr>
        @empty
            <tr><td colspan="10" class="empty">No discounted transactions.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="sales-summary">
    <table>
        <thead><tr><th colspan="2" class="title">SALES SUMMARY</th></tr></thead>
        <tbody>
            <tr>
                <td style="width: 57%; padding: 0; vertical-align: top;">
                    <table>
                        <tbody>
                            <tr><td colspan="2" class="grand">TOTAL SALES</td></tr>
                            <tr><td>SELL-OUT</td><td class="number">{{ $money($lpgSalesTotal) }}</td></tr>
                            <tr><td>KITCHEN PRODUCTS &amp; ACCESSORIES</td><td class="number">{{ $money($kitchenSalesTotal) }}</td></tr>
                            <tr><td class="grand">TOTAL</td><td class="number grand">{{ $money($totalSales) }}</td></tr>
                            <tr><td colspan="2" class="grand">MISCELLANEOUS</td></tr>
                            <tr><td>SALES ORDER - ACCOUNTS RECEIVABLE / RETURNED</td><td class="number">{{ $money($miscellaneousTotal) }}</td></tr>
                            <tr><td>EXPENSES</td><td class="number">{{ $money($expensesTotal) }}</td></tr>
                            <tr><td>DISCOUNTS</td><td class="number">{{ $money($discountsTotal) }}</td></tr>
                            <tr><td class="grand">TOTAL</td><td class="number grand">{{ $money($miscellaneousTotal + $expensesTotal + $discountsTotal) }}</td></tr>
                            <tr><td class="grand">THEORETICAL SALES</td><td class="number grand">{{ $money($theoreticalSales) }}</td></tr>
                            <tr><td colspan="2" class="grand">PAYMENTS</td></tr>
                            <tr><td>CASH</td><td class="number">{{ $money($actualCash) }}</td></tr>
                            <tr><td>NON-CASH PAYMENT</td><td></td></tr>
                            <tr><td style="padding-left: 12pt">GCASH / PAYMAYA</td><td class="number">{{ $money($nonCashTotals['gcash']) }}</td></tr>
                            <tr><td style="padding-left: 12pt">LIMITLESS APP</td><td class="number">{{ $money($nonCashTotals['limitless']) }}</td></tr>
                            <tr><td style="padding-left: 12pt">BANK TRANSFER / CREDIT-DEBIT</td><td class="number">{{ $money($nonCashTotals['bank_transfer']) }}</td></tr>
                            <tr><td style="padding-left: 12pt">TOTAL CHECK PAYMENT</td><td class="number">{{ $money($nonCashTotals['check']) }}</td></tr>
                            <tr><td class="grand">TOTAL CASH &amp; NON-CASH SALES AFTER AR</td><td class="number grand">{{ $money($totalCashAndNonCash) }}</td></tr>
                            <tr><td>Less: A.R. COLLECTIONS</td><td class="number">{{ $money($arCollectionsTotal) }}</td></tr>
                            <tr><td class="grand">TOTAL CASH ON HAND BEFORE AR COLLECTIONS</td><td class="number grand">{{ $money($cashBeforeArCollections) }}</td></tr>
                            <tr><td class="variance">VARIANCE</td><td class="number variance">{{ $money($salesVariance) }}</td></tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 43%; padding: 0; vertical-align: top;">
                    <table>
                        <thead>
                            <tr><th colspan="3" class="subhead">CASH PAYMENTS RECEIVED</th></tr>
                            <tr><th>DENO</th><th>QUANTITY</th><th>AMOUNT</th></tr>
                        </thead>
                        <tbody>
                        @foreach ($cashDenominations as $denomination)
                            @php
                                $denominationQuantity = (int) ($cashRecord->{$denomination['field']} ?? 0);
                                $denominationAmount = $denomination['value'] * $denominationQuantity;
                            @endphp
                            <tr>
                                <td class="number">{{ $denomination['label'] }}</td>
                                <td class="number">{{ $denominationQuantity ?: '' }}</td>
                                <td class="number">{{ $money($denominationAmount) }}</td>
                            </tr>
                        @endforeach
                            <tr><td colspan="2">CASH ON HAND</td><td class="number">{{ $money($cashOnHand) }}</td></tr>
                            <tr><td colspan="2">CASH DROP</td><td class="number">{{ $money($cashDrop) }}</td></tr>
                            <tr><td colspan="2" class="grand">TOTAL ACTUAL CASH</td><td class="number grand">{{ $money($actualCash) }}</td></tr>
                        </tbody>
                    </table>
                    <table style="margin-top: 4pt">
                        <thead>
                            <tr><th colspan="5" class="subhead">NON-CASH PAYMENT/S</th></tr>
                            <tr><th>Mode of Payment</th><th>Bank / Recipient</th><th>Ref. No.</th><th>Check Date</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                        @forelse ($nonCashRows as $payment)
                            <tr>
                                <td>{{ strtoupper(str_replace('_', ' ', $payment->mode_of_payment ?? '')) }}</td>
                                <td>{{ $payment->payer_name ?? '' }}{{ !empty($payment->payer_number) ? ' (' . $payment->payer_number . ')' : '' }}</td>
                                <td>{{ $payment->reference_no ?? '' }}</td>
                                <td>{{ $payment->check_expiry_date ?? '' }}</td>
                                <td class="number">{{ $money($payment->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty">No non-cash payments.</td></tr>
                        @endforelse
                            <tr><td colspan="4" class="grand">TOTAL NON-CASH</td><td class="number grand">{{ $money($nonCashTotal) }}</td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="empty-report">
    <table>
        @php
            $emptyWidths = [20, 6, 5, 5, 5, 5, 6, 6, 6, 6, 6, 7, 17];
            $emptyWidthTotal = array_sum($emptyWidths);
            $emptyColWidth = static function ($index, $span = 1) use ($emptyWidths, $emptyWidthTotal) {
                return array_sum(array_slice($emptyWidths, $index, $span)) / $emptyWidthTotal * 100;
            };
            $emptyQuantityFields = [
                'beginning_quantity', 'refill_sell_out_quantity', 'return_quantity', 'swap_in_quantity',
                'from_other_branch_quantity', 'sold_quantity', 'swap_out_quantity', 'borrow_quantity',
                'pull_out_branch_quantity', 'pull_out_truck_quantity',
            ];
        @endphp
        <colgroup>
            @foreach ($emptyWidths as $emptyWidth)
                <col style="width: {{ $emptyWidth / $emptyWidthTotal * 100 }}%">
            @endforeach
        </colgroup>
        <thead>
            <tr><th colspan="13" class="title">EMPTY CYLINDERS / CANISTERS</th></tr>
            <tr>
                <th rowspan="3" style="width: {{ $emptyColWidth(0) }}%">Item</th>
                <th rowspan="3" style="width: {{ $emptyColWidth(1) }}%">Beg.<br>Inv.</th>
                <th colspan="4" style="width: {{ $emptyColWidth(2, 4) }}%">Add (+)</th>
                <th colspan="5" style="width: {{ $emptyColWidth(6, 5) }}%">Less (-)</th>
                <th rowspan="3" style="width: {{ $emptyColWidth(11) }}%">Ending Inventory</th>
                <th rowspan="3" style="width: {{ $emptyColWidth(12) }}%">Remarks</th>
            </tr>
            <tr>
                <th colspan="4" style="width: {{ $emptyColWidth(2, 4) }}%">Empty From:</th>
                <th rowspan="2" style="width: {{ $emptyColWidth(6) }}%">Sold<br>Cylinder</th>
                <th rowspan="2" style="width: {{ $emptyColWidth(7) }}%">Swap<br>Out</th>
                <th rowspan="2" style="width: {{ $emptyColWidth(8) }}%">Borrow</th>
                <th colspan="2" style="width: {{ $emptyColWidth(9, 2) }}%">Pull Out</th>
            </tr>
            <tr>
                <th style="width: {{ $emptyColWidth(2) }}%">Refill /<br>Sell-out</th>
                <th style="width: {{ $emptyColWidth(3) }}%">Return</th>
                <th style="width: {{ $emptyColWidth(4) }}%">Swap In</th>
                <th style="width: {{ $emptyColWidth(5) }}%">From Other<br>Branch</th>
                <th style="width: {{ $emptyColWidth(9) }}%">From Other<br>Branch</th>
                <th style="width: {{ $emptyColWidth(10) }}%">Delivery<br>Truck</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($emptyInventoryGroups as $capacityGroup => $groupRows)
            <tr class="capacity-row"><td colspan="13">{{ $capacityGroup }}</td></tr>
            @foreach ($groupRows as $row)
                <tr>
                    <td>{{ $row->product_name }}</td>
                    @foreach ($emptyQuantityFields as $field)
                        <td class="number">{{ (float) $row->{$field} > 0 ? $quantity($row->{$field}) : '' }}</td>
                    @endforeach
                    <td class="number">{{ $quantity($row->ending_quantity) }}</td>
                    <td>{{ $row->remarks ?? '' }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Total</td>
                @foreach ($emptyQuantityFields as $field)
                    <td class="number">{{ $quantity($groupRows->sum($field)) }}</td>
                @endforeach
                <td class="number">{{ $quantity($groupRows->sum('ending_quantity')) }}</td>
                <td></td>
            </tr>
        @empty
            <tr><td colspan="13" class="empty">No LPG empty-cylinder products are configured.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="swapping-report">
    <table>
        <colgroup>
            <col style="width: 25%"><col style="width: 7%"><col style="width: 25%">
            <col style="width: 7%"><col style="width: 25%"><col style="width: 11%">
        </colgroup>
        <thead>
            <tr><th colspan="6" class="title">SWAPPING</th></tr>
            <tr>
                <th rowspan="2">Customers Name</th>
                <th colspan="2">Cylinder / Regulator Received</th>
                <th colspan="2">Cylinder / Regulator Replaced</th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr><th>Qty</th><th>Brand</th><th>Qty</th><th>Brand</th></tr>
        </thead>
        <tbody>
        @forelse ($swappingRows as $row)
            <tr>
                <td>{{ $row->client_name ?? '' }}</td>
                <td class="number">{{ (float) $row->received_quantity > 0 ? $quantity($row->received_quantity) : '' }}</td>
                <td>{{ $containerItem($row->received_product_name, $row->received_product_capacity, $row->received_product_capacity_unit, $row->received_product_unit_measurement, $row->received_stock_state) }}</td>
                <td class="number">{{ (float) $row->replaced_quantity > 0 ? $quantity($row->replaced_quantity) : '' }}</td>
                <td>{{ $containerItem($row->replaced_product_name, $row->replaced_product_capacity, $row->replaced_product_capacity_unit, $row->replaced_product_unit_measurement, $row->replaced_stock_state) }}</td>
                <td>{{ $row->remarks ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">No swapping records.</td></tr>
        @endforelse
        @for ($blank = 0; $blank < 4; $blank++)
            <tr class="blank-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
        @endfor
        </tbody>
    </table>

    <table style="margin-top: 4pt">
        <colgroup>
            <col style="width: 19%"><col style="width: 8%"><col style="width: 7%"><col style="width: 19%">
            <col style="width: 7%"><col style="width: 8%"><col style="width: 19%"><col style="width: 7%"><col style="width: 6%">
        </colgroup>
        <thead>
            <tr><th colspan="9" class="title">ON-LOAN</th></tr>
            <tr>
                <th rowspan="3">Customers Name</th>
                <th rowspan="3">DR No.</th>
                <th colspan="3">Borrow</th>
                <th colspan="3">Return</th>
                <th rowspan="3">Remarks</th>
            </tr>
            <tr>
                <th colspan="2">Cylinder / New Regulator</th><th rowspan="2">Alum. Qty</th>
                <th colspan="2">Cylinder / Old Regulator</th><th rowspan="2">Alum. Qty</th>
            </tr>
            <tr><th>Qty</th><th>Item</th><th>Qty</th><th>Item</th></tr>
        </thead>
        <tbody>
        @forelse ($onLoanRows as $row)
            <tr>
                <td>{{ $row->client_name ?? '' }}</td>
                <td>{{ $row->dr_number ?? '' }}</td>
                <td class="number">{{ (float) $row->borrow_quantity > 0 ? $quantity($row->borrow_quantity) : '' }}</td>
                <td>{{ $containerItem($row->borrow_product_name, $row->borrow_product_capacity, $row->borrow_product_capacity_unit, $row->borrow_product_unit_measurement, $row->borrow_stock_state) }}</td>
                <td class="number">{{ (float) $row->borrow_alum_quantity > 0 ? $quantity($row->borrow_alum_quantity) : '' }}</td>
                <td class="number">{{ (float) $row->return_quantity > 0 ? $quantity($row->return_quantity) : '' }}</td>
                <td>{{ $containerItem($row->return_product_name, $row->return_product_capacity, $row->return_product_capacity_unit, $row->return_product_unit_measurement, $row->return_stock_state) }}</td>
                <td class="number">{{ (float) $row->return_alum_quantity > 0 ? $quantity($row->return_alum_quantity) : '' }}</td>
                <td>{{ $row->remarks ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="9" class="empty">No on-loan records.</td></tr>
        @endforelse
        @for ($blank = 0; $blank < 4; $blank++)
            <tr class="blank-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        @endfor
        </tbody>
    </table>
</div>
<div class="note">Report: {{ $report->cashiers_report_no ?? $report->cashiers_report_id }} &nbsp; | &nbsp; Date: {{ $report->report_date }} &nbsp; | &nbsp; Branch: {{ $report->branch_name ?? '' }}</div>
@if ($sellOutRows->contains(function ($row) { return !$row['beginning_count']; }))
    <div class="note">- = Beginning inventory has not been entered; ending inventory and variance are not yet available.</div>
@endif
@if ($sellOutRows->sum('unclassified_quantity') || $sellOutRows->sum('expense_quantity'))
    <div class="note">Ending inventory also includes existing unclassified sales and product expenses. Total Sales includes unclassified sales.</div>
@endif
@if ($sellOutRows->contains(function ($row) { return $row['refill_swap_mixed_prices'] || $row['cylinder_with_content_mixed_prices']; }))
    <div class="note">Mixed = more than one unit price. Amounts use the recorded SRP before discounts.</div>
@endif
<div class="note">Kitchen ending inventory = beginning + delivery + damaged + B.O. replacement + return - swap - on-loan - consignment - raffle / product expenses - total sold. Walk-in sales are included in Total Sold.</div>
@if ($kitchenRows->contains(function ($row) { return empty($row['beginning_count']); }))
    <div class="note">Kitchen theoretical and actual ending inventory are blank until beginning inventory is recorded for that item.</div>
@endif
@if (!empty($layoutPreview))
    <div class="note preview">LAYOUT PREVIEW - Sample data from the reference image. Not a live cashier report.</div>
@endif
</body>
</html>
