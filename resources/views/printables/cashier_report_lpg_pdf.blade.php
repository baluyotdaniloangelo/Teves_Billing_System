<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LPG Cashier Report</title>
    <style>
        @page { margin: 12mm 8mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 8px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: 16px; } h2 { font-size: 10px; } h3 { font-size: 9px; }
        .header { width: 100%; border-bottom: 2px solid #114447; padding-bottom: 7px; margin-bottom: 8px; }
        .header td { vertical-align: top; }
        .muted { color: #5d6668; } .right { text-align: right; } .center { text-align: center; }
        .section-title { background: #8bd36f; border: 1px solid #222; font-size: 9px; font-weight: bold; padding: 4px; text-align: center; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 8px; }
        th, td { border: 1px solid #444; padding: 3px 4px; vertical-align: top; }
        th { background: #e9e9e9; font-weight: bold; text-align: center; }
        .num { text-align: right; white-space: nowrap; }
        .pending { margin: 8px 0; padding: 7px 9px; border: 1px solid #a46900; background: #fff3d7; color: #6b4500; }
        .pending strong { display: block; margin-bottom: 2px; }
        .summary td { border-color: #777; } .summary .label { background: #f2f2f2; font-weight: bold; }
        .note { font-size: 7px; color: #555; }
        .page-break { page-break-before: always; }
        .no-border td { border: 0; padding: 1px 4px; }
        .empty { color: #777; text-align: center; padding: 7px; }
    </style>
</head>
<body>
    @php
        $money = fn ($value) => number_format((float) $value, 2, '.', ',');
        $qty = fn ($value) => number_format((float) $value, 2, '.', ',');
        $item = fn ($row) => trim(($row->product_name ?? '') . ' ' . ($row->product_unit_measurement ?? '')) ?: '-';
        $modeLabel = ['BEGINNING' => 'Beginning Inventory', 'DELIVERY' => 'Delivery', 'BO_REPLACEMENT' => 'B.O. Replacement (+)'];
    @endphp

    <table class="header">
        <tr>
            <td style="width:65%;">
                <h1>TEVES GASOLINE STATION</h1>
                <h2>PHOENIX SUPER LPG</h2>
                <p class="muted">{{ $report->branch_name ?? $report->branch_code ?? '-' }}{{ !empty($report->branch_address) ? ' · ' . $report->branch_address : '' }}</p>
            </td>
            <td class="right" style="width:35%;">
                <h2>LPG CASHIER'S REPORT</h2>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->report_date)->format('m/d/Y') }}</p>
                <p><strong>Shift:</strong> {{ $report->shift ?: '-' }}</p>
                <p><strong>Report No.:</strong> {{ $report->cashiers_report_no ?: $report->cashiers_report_id }}</p>
            </td>
        </tr>
    </table>

    <table class="no-border" style="margin-bottom:8px;">
        <tr>
            <td style="width:33%;"><strong>Cashier:</strong> {{ $report->cashiers_name ?: '-' }}</td>
            <td style="width:33%;"><strong>Employee on Duty:</strong> {{ $report->forecourt_attendant ?: '-' }}</td>
            <td style="width:34%;"><strong>Encoded By:</strong> {{ $report->encoder_name ?: '-' }}</td>
        </tr>
        <tr><td colspan="3"><strong>Remarks:</strong> {{ $report->cashier_report_remarks ?: '-' }}</td></tr>
    </table>

    <div class="pending">
        <strong>PRINT STATUS: PARTIAL LPG REPORT</strong>
        Records entered in the system are printed below. Sections still marked <em>PENDING INPUT MODULE</em> are intentionally not computed as zero.
    </div>

    <div class="section-title">LPG INVENTORY MOVEMENTS RECORDED</div>
    <table>
        <thead><tr><th>Movement</th><th>Item</th><th>Supplier</th><th>DR / Reference</th><th>Quantity</th><th>Remarks</th></tr></thead>
        <tbody>
        @forelse($inventoryRows as $row)
            <tr>
                <td>{{ $modeLabel[$row->inventory_mode] ?? $row->inventory_mode }}</td>
                <td>{{ $item($row) }}</td>
                <td>{{ $row->supplier_name ?: '-' }}</td>
                <td>{{ $row->delivery_reference_no ?: '-' }}</td>
                <td class="num">{{ $qty($row->quantity) }}</td>
                <td>{{ $row->remarks ?: '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">No inventory movements recorded.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="pending">
        <strong>PENDING INPUT MODULE — LPG SELL-OUT / STOCK RECONCILIATION</strong>
        Return, refill or swap sales quantity, cylinder sold with content, actual ending inventory, theoretical ending inventory, and variance are not yet connected for printing.
    </div>

    <div class="section-title">ACCOUNTS RECEIVABLES COLLECTION</div>
    <table>
        <thead><tr><th>Date</th><th>Account Name</th><th>DR Number</th><th>Remarks</th><th>Amount Received</th></tr></thead>
        <tbody>
        @forelse($arCollections as $row)
            <tr><td>{{ $row->ar_date ? \Carbon\Carbon::parse($row->ar_date)->format('m/d/Y') : '-' }}</td><td>{{ $row->client_name ?: '-' }}</td><td>{{ $row->dr_number ?: '-' }}</td><td>{{ $row->remarks ?: '-' }}</td><td class="num">{{ $money($row->amount_received) }}</td></tr>
        @empty
            <tr><td colspan="5" class="empty">No AR collections recorded.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">SALES ORDER / ACCOUNT RECEIVABLE / RETURNED / CASH SALES</div>
    <table>
        <thead><tr><th>Account Name</th><th>DR No.</th><th>Transaction Type</th><th>Item</th><th>Qty</th><th>SRP</th><th>Discount</th><th>Net Amount</th></tr></thead>
        <tbody>
        @forelse($salesRows as $row)
            <tr>
                <td>{{ $row->client_name ?: '-' }}</td><td>{{ $row->dr_number ?: '-' }}</td><td>{{ $row->item_description ?: '-' }}</td>
                <td>{{ trim(($row->product_name ?? '') . ' ' . ($row->product_unit_measurement ?? '')) ?: '-' }}</td>
                <td class="num">{{ $qty($row->order_quantity) }}</td><td class="num">{{ $money($row->unit_price) }}</td>
                <td class="num">{{ $money(($row->order_quantity ?? 0) * ($row->discount_per_unit ?? 0)) }}</td><td class="num">{{ $money($row->order_total_amount) }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="empty">No sales order, AR, return, or cash sales recorded.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">EXPENSES — CASH OUTS / REPAIR &amp; MAINTENANCE / RAFFLE PRIZES</div>
    <table>
        <thead><tr><th>Account Name</th><th>Type</th><th>Purpose</th><th>Item</th><th>Qty</th><th>SRP</th><th>Amount</th><th>Remarks</th></tr></thead>
        <tbody>
        @forelse($expenses as $row)
            <tr><td>{{ $row->client_name ?: '-' }}</td><td>{{ $row->expense_type ?: '-' }}</td><td>{{ $row->purpose ?: '-' }}</td><td>{{ $row->item_description ?: (trim(($row->product_name ?? '') . ' ' . ($row->product_unit_measurement ?? '')) ?: '-') }}</td><td class="num">{{ $qty($row->order_quantity) }}</td><td class="num">{{ $money($row->unit_price) }}</td><td class="num">{{ $money($row->amount) }}</td><td>{{ $row->remarks ?: '-' }}</td></tr>
        @empty
            <tr><td colspan="8" class="empty">No expenses recorded.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">CASH PAYMENTS RECEIVED</div>
    <table>
        <thead><tr><th>Deno</th><th>Quantity</th><th>Amount</th><th>Deno</th><th>Quantity</th><th>Amount</th><th>Cash on Hand</th><th>Cash Drop</th><th>Total Actual Cash</th></tr></thead>
        <tbody>
        @php $denominations = [[1000, 'one_thousand_deno'], [500, 'five_hundred_deno'], [200, 'two_hundred_deno'], [100, 'one_hundred_deno'], [50, 'fifty_deno'], [20, 'twenty_deno'], [10, 'ten_deno'], [5, 'five_deno'], [1, 'one_deno'], [.25, 'twenty_five_cent_deno']]; @endphp
        @foreach(array_chunk($denominations, 2) as $index => $pair)
            <tr>
                @foreach($pair as [$denomination, $field])
                    @php $count = $cash->{$field} ?? 0; @endphp
                    <td class="num">{{ $money($denomination) }}</td><td class="num">{{ $qty($count) }}</td><td class="num">{{ $money($denomination * $count) }}</td>
                @endforeach
                @if(count($pair) === 1)<td colspan="3"></td>@endif
                @if($index === 0)<td class="num" rowspan="5">{{ $money($cashTotal) }}</td><td class="num" rowspan="5">{{ $money($summary['cash_drop']) }}</td><td class="num" rowspan="5">{{ $money($cashTotal + $summary['cash_drop']) }}</td>@endif
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="section-title">NON-CASH PAYMENTS</div>
    <table>
        <thead><tr><th>Mode of Payment</th><th>Payer Name</th><th>Account Number</th><th>Bank / Reference</th><th>Check Expiry Date</th><th>Amount</th></tr></thead>
        <tbody>
        @forelse($nonCashPayments as $row)
            <tr><td>{{ $row->mode_of_payment ?: '-' }}</td><td>{{ $row->payer_name ?: '-' }}</td><td>{{ $row->payer_number ?: '-' }}</td><td>{{ $row->reference_no ?: '-' }}</td><td>{{ $row->check_expiry_date ? \Carbon\Carbon::parse($row->check_expiry_date)->format('m/d/Y') : '-' }}</td><td class="num">{{ $money($row->amount) }}</td></tr>
        @empty
            <tr><td colspan="6" class="empty">No non-cash payments recorded.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">SWAPPING</div>
    <table>
        <thead><tr><th>Customer Name</th><th>Received Item</th><th>Received Qty</th><th>Replaced Item</th><th>Replaced Qty</th><th>Remarks</th></tr></thead>
        <tbody>
        @forelse($swappingRows as $row)
            <tr><td>{{ $row->client_name ?: '-' }}</td><td>{{ $row->received_product ?: '-' }}</td><td class="num">{{ $qty($row->received_quantity) }}</td><td>{{ $row->replaced_product ?: '-' }}</td><td class="num">{{ $qty($row->replaced_quantity) }}</td><td>{{ $row->remarks ?: '-' }}</td></tr>
        @empty
            <tr><td colspan="6" class="empty">No swapping records.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">ON-LOAN</div>
    <table>
        <thead><tr><th>Customer Name</th><th>DR No.</th><th>Borrow</th><th>Return</th><th>Remarks</th></tr></thead>
        <tbody>
        @forelse($onLoanRows as $row)
            <tr><td>{{ $row->client_name ?: '-' }}</td><td>{{ $row->dr_number ?: '-' }}</td><td>{{ $row->borrow_product ?: '-' }} (Qty: {{ $qty($row->borrow_quantity) }}, Alum: {{ $qty($row->borrow_alum_quantity) }})</td><td>{{ $row->return_product ?: '-' }} (Qty: {{ $qty($row->return_quantity) }}, Alum: {{ $qty($row->return_alum_quantity) }})</td><td>{{ $row->remarks ?: '-' }}</td></tr>
        @empty
            <tr><td colspan="5" class="empty">No on-loan records.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">CURRENT PRINTED SUMMARY</div>
    <table class="summary">
        <tr><td class="label">Sales Order / AR / Return / Cash Sales</td><td class="num">{{ $money($summary['sales']) }}</td><td class="label">Discounts</td><td class="num">{{ $money($summary['discounts']) }}</td><td class="label">Expenses</td><td class="num">{{ $money($summary['expenses']) }}</td></tr>
        <tr><td class="label">AR Collections</td><td class="num">{{ $money($summary['ar_collections']) }}</td><td class="label">Cash on Hand</td><td class="num">{{ $money($summary['cash_on_hand']) }}</td><td class="label">Non-Cash Payments</td><td class="num">{{ $money($summary['non_cash']) }}</td></tr>
    </table>

    <div class="pending">
        <strong>PENDING INPUT MODULES — NOT INCLUDED IN SUMMARY OR VARIANCE</strong>
        Kitchen Products &amp; Accessories inventory; Empty Cylinders / Canisters movement ledger; Swapped / Old Regulator inventory; LPG actual ending count; and the final theoretical-sales, inventory-variance, and cash-variance reconciliation.
    </div>
    <p class="note">Generated {{ now()->format('m/d/Y h:i A') }}. Amounts represent saved LPG report records only.</p>
</body>
</html>
