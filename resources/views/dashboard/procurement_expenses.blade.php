@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}" rel="stylesheet">
<link href="{{ asset('style/procurement-expenses-dashboard.css') }}?v={{ filemtime(public_path('style/procurement-expenses-dashboard.css')) }}" rel="stylesheet">
@endpush

@section('content')
@php
    $money = fn ($amount) => '₱' . number_format((float) $amount, 2);
    $pipelineLabels = [
        'Pending' => ['label' => 'Pending', 'icon' => 'bi-hourglass-split'],
        'Partial' => ['label' => 'Partially received', 'icon' => 'bi-box-seam'],
        'Received' => ['label' => 'Fully received', 'icon' => 'bi-check2-circle'],
        'Not tracked' => ['label' => 'No item quantities', 'icon' => 'bi-question-circle'],
    ];
    $breakdownMax = max(1, collect($expense_breakdown)->where('excluded', false)->max('amount') ?: 0);
@endphp

<main id="main" class="main fuel-page fuel-dashboard pe-dashboard" data-expense-source="{{ $source }}">
    <div class="fuel-title-row pe-title-row">
        <div>
            <p class="fuel-eyebrow"></p>
            <h1><span>Procurement &amp; Expenses</span></h1>
            <p class="fuel-subtitle">Monitor purchase commitments, deliveries, and expenses recorded in Fuel and LPG cashier reports.</p>
        </div>
        <div class="pe-title-badge"><i class="bi bi-clipboard-data" aria-hidden="true"></i><span>Purchase Orders · Cash Out · LPG Expenses</span></div>
    </div>

    @if($errors->any())
    <div class="fuel-alert" role="alert"><strong>Please check your filters.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('procurement_expenses_dashboard') }}" method="get" class="fuel-filterbar sr-filterbar pe-filterbar">
        <label class="pe-filter-field"><span class="fuel-field-label">FROM</span><input type="date" name="start_date" value="{{ $start_date }}" required></label>
        <label class="pe-filter-field"><span class="fuel-field-label">TO</span><input type="date" name="end_date" value="{{ $end_date }}" min="{{ $start_date }}" required></label>
        <label class="pe-filter-field pe-filter-wide"><span class="fuel-field-label">BRANCH</span><select name="company_header"><option value="All">All accessible branches</option>@foreach($branches as $branch)<option value="{{ $branch->branch_id }}" {{ (string) $branch_id === (string) $branch->branch_id ? 'selected' : '' }}>{{ $branch->branch_code }} · {{ $branch->branch_name }}</option>@endforeach</select></label>
        <label class="pe-filter-field"><span class="fuel-field-label">EXPENSE SOURCE</span><select name="source"><option value="All" {{ $source === 'All' ? 'selected' : '' }}>Fuel + LPG</option><option value="Fuel" {{ $source === 'Fuel' ? 'selected' : '' }}>Fuel only</option><option value="LPG" {{ $source === 'LPG' ? 'selected' : '' }}>LPG only</option></select></label>
        <button type="submit" class="fuel-button fuel-button-primary fuel-button-small"><i class="bi bi-funnel" aria-hidden="true"></i> Apply filters</button>
        <a href="{{ route('procurement_expenses_dashboard') }}" class="fuel-reset">Reset <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i></a>
    </form>
    <div class="fuel-period-note"><i class="bi bi-calendar3" aria-hidden="true"></i><span>{{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</span><span class="pe-period-note">Purchase orders follow PO date; cashier expenses follow report date.</span></div>

    <section class="fuel-stats pe-stats" aria-label="Procurement and expenses overview">
        <article class="fuel-stat pe-stat-orders"><div class="fuel-stat-label">Purchase order value <i class="bi bi-bag-check" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($purchase_order_amount) }}</div><div class="fuel-stat-foot"><i class="fuel-dot fuel-orange"></i>{{ number_format($purchase_order_count) }} POs created in this period</div></article>
        <article class="fuel-stat pe-stat-open"><div class="fuel-stat-label">Open to receive <i class="bi bi-truck" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($open_to_receive_amount) }}</div><div class="fuel-stat-foot">{{ number_format($open_to_receive_count) }} POs with remaining items · full PO value</div></article>
        <article class="fuel-stat pe-stat-fuel"><div class="fuel-stat-label">Fuel cash out <i class="bi bi-fuel-pump" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($fuel_cash_out) }}</div><div class="fuel-stat-foot">{{ number_format($fuel_cash_out_count) }} CASHOUT entries · OTHERS is separate</div></article>
        <article class="fuel-stat pe-stat-lpg"><div class="fuel-stat-label">LPG expenses <i class="bi bi-fire" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($lpg_expenses) }}</div><div class="fuel-stat-foot">{{ number_format($lpg_expense_count) }} entries · OPEX and Non-OPEX</div></article>
    </section>

    <section class="pe-analysis-grid" aria-label="Expense trend and breakdown">
        <article class="fuel-panel pe-panel pe-trend-panel">
            <div class="fuel-panel-head"><div><h2>Expense trend</h2><p>Daily totals from cashier entries; Fuel OTHERS is excluded from expense lines.</p></div><span class="pe-panel-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span></div>
            <div id="procurement-expenses-chart" class="pe-chart" role="img" aria-label="Daily Fuel cash out and LPG expense trend"><p class="pe-chart-fallback">Expense totals are available in the source breakdown.</p></div>
            <div class="pe-chart-legend">@if($source !== 'LPG')<span><i class="pe-legend-fuel"></i>Fuel cash out</span>@endif @if($source !== 'Fuel')<span><i class="pe-legend-opex"></i>LPG OPEX</span><span><i class="pe-legend-non-opex"></i>LPG Non-OPEX</span>@endif</div>
        </article>

        <article class="fuel-panel pe-panel pe-breakdown-panel">
            <div class="fuel-panel-head"><div><h2>Expenses by source</h2><p>Fuel cash out and LPG entries are grouped separately.</p></div><span class="pe-panel-icon"><i class="bi bi-pie-chart" aria-hidden="true"></i></span></div>
            <div class="pe-breakdown-list">
                @forelse($expense_breakdown as $item)
                    @php $share = !$item['excluded'] && $breakdownMax > 0 ? min(100, ((float) $item['amount'] / $breakdownMax) * 100) : 0; @endphp
                    <div class="pe-breakdown-row {{ $item['excluded'] ? 'is-excluded' : '' }}">
                        <div class="pe-breakdown-title"><span><i class="{{ $item['source'] === 'Fuel' ? 'bi bi-fuel-pump' : 'bi bi-fire' }}" aria-hidden="true"></i>{{ $item['label'] }}</span><strong>{{ $money($item['amount']) }}</strong></div>
                        @if(!$item['excluded'])<div class="pe-breakdown-track"><i class="{{ $item['source'] === 'Fuel' ? 'pe-bar-fuel' : ($item['label'] === 'LPG OPEX' ? 'pe-bar-opex' : 'pe-bar-non-opex') }}" style="width: {{ $share }}%"></i></div>@endif
                        <small>{{ number_format($item['entries']) }} entries{{ $item['excluded'] ? ' · shown separately, not counted as expense' : '' }}</small>
                    </div>
                @empty
                    <div class="pe-empty"><i class="bi bi-inbox" aria-hidden="true"></i><span>No expense entries for this selection.</span></div>
                @endforelse
            </div>
            <div class="pe-breakdown-note"><i class="bi bi-info-circle" aria-hidden="true"></i><span>Fuel <strong>OTHERS</strong> entries are classified separately and excluded from expense totals.</span></div>
        </article>
    </section>

    <section class="fuel-panel pe-panel pe-pipeline-panel">
        <div class="fuel-panel-head"><div><h2>Purchase order delivery pipeline</h2><p>Delivery status is calculated from ordered and received quantities.</p></div><span class="pe-pipeline-total">{{ number_format($purchase_order_count) }} purchase orders</span></div>
        <div class="pe-pipeline-grid">
            @foreach($pipelineLabels as $key => $item)
                @php $row = $purchase_order_pipeline->get($key); @endphp
                <article class="pe-pipeline-step pe-pipeline-{{ \Illuminate\Support\Str::slug($key) }}">
                    <span class="pe-pipeline-icon"><i class="bi {{ $item['icon'] }}" aria-hidden="true"></i></span>
                    <div><small>{{ $item['label'] }}</small><strong>{{ number_format((int) ($row->order_count ?? 0)) }} orders</strong><span>{{ $money($row->amount ?? 0) }}</span></div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="fuel-panel pe-panel pe-orders-panel">
        <div class="fuel-panel-head"><div><h2>Recent purchase orders</h2><p>Latest POs within the selected dates and branch.</p></div><a href="{{ route('purchaseorder') }}" class="pe-orders-link">Open Purchase Orders <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
        <div class="pe-table-scroll">
            <table class="pe-table">
                <thead><tr><th>PO date</th><th>Reference</th><th>Supplier</th><th>Branch</th><th>Delivery</th><th class="text-end">Total payable</th></tr></thead>
                <tbody>
                    @forelse($recent_purchase_orders as $order)
                    <tr>
                        <td>{{ $order->purchase_order_date ? \Carbon\Carbon::parse($order->purchase_order_date)->format('M d, Y') : '—' }}</td>
                        <td><strong>{{ $order->purchase_order_control_number ?: 'PO-'.$order->purchase_order_id }}</strong></td>
                        <td>{{ $order->supplier_name ?: 'Supplier not specified' }}</td>
                        <td>{{ $order->branch_code ?: ($order->branch_name ?: '—') }}</td>
                        <td><span class="pe-status pe-status-{{ \Illuminate\Support\Str::slug($order->delivery_bucket) }}">{{ $order->delivery_bucket }}</span></td>
                        <td class="text-end pe-amount">{{ $money($order->purchase_order_total_payable) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="pe-empty-row">No purchase orders for this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pe-table-foot"><span>Purchase commitments are shown separately from cashier expenses.</span><span>{{ number_format($recent_purchase_orders->count()) }} recent records</span></div>
    </section>

    <div class="fuel-dashboard-footer pe-footer"><span><strong>REPORTING NOTE</strong> PO value represents purchase commitments; cashier expense totals represent recorded cash out.</span><span>Updated from selected period</span></div>
    <script type="application/json" id="procurement-expenses-data">@json($trend)</script>
</main>
@endsection

@push('page_scripts')
<script src="{{ asset('js/procurement-expenses-dashboard.js') }}?v={{ filemtime(public_path('js/procurement-expenses-dashboard.js')) }}" defer></script>
<script src="{{ asset('js/dashboard-ajax.js') }}?v={{ filemtime(public_path('js/dashboard-ajax.js')) }}" defer></script>
@endpush
