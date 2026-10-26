@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}" rel="stylesheet">
<link href="{{ asset('style/sales-receivables-dashboard.css') }}?v={{ filemtime(public_path('style/sales-receivables-dashboard.css')) }}" rel="stylesheet">
@endpush

@section('content')
@php
    $money = fn ($amount) => '₱' . number_format((float) $amount, 2);
    $totalPipeline = $pipeline->sum('order_count');
    $pipelineAmounts = $pipeline->keyBy('status');
    $agingTotal = array_sum($aging);
    $formatTimestamp = fn ($value) => $value ? \Carbon\Carbon::parse($value)->diffForHumans() : 'Date unavailable';
@endphp

<main id="main" class="main fuel-page fuel-dashboard sr-dashboard">
    <div class="fuel-title-row sr-title-row">
        <div>
            <p class="fuel-eyebrow"></p>
            <h1><span>Sales &amp; Receivables</span></h1>
            <p class="fuel-subtitle">Track bulk orders, direct billings, customer balances, and collections separately.</p>
        </div>
        <div class="sr-title-badge"><i class="bi bi-diagram-3" aria-hidden="true"></i><span>Orders · Billing · Collections</span></div>
    </div>

    @if($errors->any())
    <div class="fuel-alert" role="alert"><strong>Please check your filters.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('sales_receivables_dashboard') }}" method="get" class="fuel-filterbar sr-filterbar">
        <label class="sr-filter-field"><span class="fuel-field-label">FROM</span><input type="date" name="start_date" value="{{ $start_date }}" required></label>
        <label class="sr-filter-field"><span class="fuel-field-label">TO</span><input type="date" name="end_date" value="{{ $end_date }}" min="{{ $start_date }}" required></label>
        <label class="sr-filter-field sr-filter-wide"><span class="fuel-field-label">BRANCH</span><select name="company_header"><option value="All">All accessible branches</option>@foreach($branches as $branch)<option value="{{ $branch->branch_id }}" {{ (string) $branch_id === (string) $branch->branch_id ? 'selected' : '' }}>{{ $branch->branch_code }} · {{ $branch->branch_name }}</option>@endforeach</select></label>
        <label class="sr-filter-field sr-filter-wide"><span class="fuel-field-label">CUSTOMER</span><select name="client_id"><option value="">All customers</option>@foreach($clients as $client)<option value="{{ $client->client_id }}" {{ (string) $client_id === (string) $client->client_id ? 'selected' : '' }}>{{ $client->client_name }}</option>@endforeach</select></label>
        <button type="submit" class="fuel-button fuel-button-primary fuel-button-small"><i class="bi bi-funnel" aria-hidden="true"></i> Apply filters</button>
        <a href="{{ route('sales_receivables_dashboard') }}" class="fuel-reset">Reset <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i></a>
    </form>
    <div class="fuel-period-note"><i class="bi bi-calendar3" aria-hidden="true"></i><span>{{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</span><span class="sr-current-balance-note">Open AR is a current balance; invoice age is measured from billing date.</span></div>

    <section class="fuel-stats sr-stats" aria-label="Sales and receivables overview">
        <article class="fuel-stat"><div class="fuel-stat-label">Ship To <i class="bi bi-cart-check" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($order_sales) }}</div><div class="fuel-stat-foot"><i class="fuel-dot fuel-orange"></i>{{ number_format($order_count) }} approved orders in range</div></article>
        <article class="fuel-stat"><div class="fuel-stat-label">Direct Billing <i class="bi bi-receipt" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($direct_billing_amount) }}</div><div class="fuel-stat-foot">{{ number_format($direct_billing_count) }} standalone billing records · excludes SO-linked billing</div></article>
        <article class="fuel-stat sr-stat-alert"><div class="fuel-stat-label">Unbilled Balance <i class="bi bi-hourglass-split" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($unbilled_amount) }}</div><div class="fuel-stat-foot">{{ number_format($unbilled_count) }} direct billing records not yet attached to a receivable</div></article>
        <article class="fuel-stat sr-stat-ar"><div class="fuel-stat-label">Open Receivables <i class="bi bi-wallet2" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($open_ar_amount) }}</div><div class="fuel-stat-foot">{{ number_format($open_ar_count) }} unpaid or partially paid invoices · current balance</div></article>
        <article class="fuel-stat sr-stat-collections"><div class="fuel-stat-label">Collections <i class="bi bi-cash-coin" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ $money($collections) }}</div><div class="fuel-stat-foot">{{ number_format($collection_count) }} payment entries in selected period</div></article>
    </section>

    <section class="sr-chart-grid" aria-label="Sales trend and receivable aging">
        <article class="fuel-panel sr-panel">
            <div class="fuel-panel-head"><div><h2>Sales and collection trend</h2><p>Separate measures across the selected period—never added together.</p></div><span class="sr-panel-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span></div>
            <div id="sales-receivables-chart" class="sr-chart" role="img" aria-label="Sales Orders, direct billing, and collection trend"></div>
            <div class="sr-chart-legend"><span><i class="sr-legend-orders"></i>Sales Orders</span><span><i class="sr-legend-billing"></i>Direct Billing</span><span><i class="sr-legend-collections"></i>Collections</span></div>
        </article>

        <article class="fuel-panel sr-panel sr-aging-panel">
            <div class="fuel-panel-head"><div><h2>Receivable aging</h2><p>Age from invoice date; due dates are not stored as a separate field.</p></div><span class="sr-panel-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span></div>
            <div class="sr-aging-total"><strong>{{ $money($open_ar_amount) }}</strong><span>{{ number_format($open_ar_count) }} open invoices</span></div>
            <div class="sr-aging-list">
                @foreach($aging as $bucket => $amount)
                @php $share = $agingTotal > 0 ? min(100, $amount / $agingTotal * 100) : 0; @endphp
                <div class="sr-aging-row"><div><strong>{{ $bucket }}</strong><span>{{ $money($amount) }}</span></div><div class="sr-aging-track"><i class="sr-aging-{{ $loop->index }}" style="width: {{ $share }}%"></i></div></div>
                @endforeach
            </div>
            <div class="sr-top-clients"><h3>Largest open balances</h3>
                @forelse($top_receivable_clients as $name => $balance)
                <div><span>{{ $name }}</span><strong>{{ $money($balance) }}</strong></div>
                @empty
                <p>No open customer balances for this selection.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section class="fuel-panel sr-pipeline-panel">
        <div class="fuel-panel-head"><div><h2>Ship To pipeline</h2><p>Approved orders only; quotations remain separate from order sales.</p></div><span class="sr-pipeline-total">{{ number_format($totalPipeline) }} approved orders</span></div>
        <div class="sr-pipeline-grid">
            <article class="sr-pipeline-step sr-pending"><span class="sr-pipeline-icon"><i class="bi bi-truck" aria-hidden="true"></i></span><div><small>Pending delivery</small><strong>{{ number_format((int) ($pipelineAmounts->get('Pending')->order_count ?? 0)) }} orders</strong><span>{{ $money($pipelineAmounts->get('Pending')->amount ?? 0) }}</span></div></article>
            <article class="sr-pipeline-step sr-partial"><span class="sr-pipeline-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span><div><small>Partially delivered</small><strong>{{ number_format((int) ($pipelineAmounts->get('Partially delivered')->order_count ?? 0)) }} orders</strong><span>{{ $money($pipelineAmounts->get('Partially delivered')->amount ?? 0) }}</span></div></article>
            <article class="sr-pipeline-step sr-delivered"><span class="sr-pipeline-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span><div><small>Delivered</small><strong>{{ number_format((int) ($pipelineAmounts->get('Delivered')->order_count ?? 0)) }} orders</strong><span>{{ $money($pipelineAmounts->get('Delivered')->amount ?? 0) }}</span></div></article>
            <article class="sr-pipeline-step sr-approval"><span class="sr-pipeline-icon"><i class="bi bi-file-earmark-check" aria-hidden="true"></i></span><div><small>Quotations awaiting approval</small><strong>{{ number_format($approval_count) }} quotations</strong><span>{{ $money($approval_amount) }}</span></div></article>
        </div>
    </section>

    <section class="sr-bottom-grid" aria-label="Items needing attention and recent activity">
        <article class="fuel-panel sr-panel sr-attention-panel">
            <div class="fuel-panel-head"><div><h2>Needs attention</h2><p>Open items from delivery, receivables, and standalone billing.</p></div><span class="sr-attention-count">{{ $pending_deliveries->count() + $old_receivables->count() + $unbilled_rows->count() }} items</span></div>
            <div class="sr-attention-list">
                @forelse($pending_deliveries as $item)
                <div class="sr-attention-item"><span class="sr-attention-tag sr-tag-order">Order</span><div><strong>{{ $item->sales_order_control_number ?: 'Sales Order '.$item->sales_order_id }} · {{ $item->client_name ?: 'Customer not specified' }}</strong><small>Pending delivery · required {{ \Carbon\Carbon::parse($item->sales_order_required_date)->format('M d, Y') }}</small></div><b>{{ $money($item->sales_order_total_due) }}</b></div>
                @empty
                @endforelse
                @foreach($old_receivables as $item)
                <div class="sr-attention-item"><span class="sr-attention-tag sr-tag-ar">AR</span><div><strong>{{ $item->control_number ?: 'Receivable #'.$item->receivable_id }} · {{ $item->client_name ?: 'Customer not specified' }}</strong><small>Open balance · invoice age {{ \Carbon\Carbon::parse($item->billing_date)->startOfDay()->diffInDays(now()->startOfDay()) }} days</small></div><b>{{ $money($item->receivable_remaining_balance) }}</b></div>
                @endforeach
                @foreach($unbilled_rows as $item)
                <div class="sr-attention-item"><span class="sr-attention-tag sr-tag-bill">Billing</span><div><strong>{{ $item->client_name ?: 'Customer not specified' }}</strong><small>Not yet attached to a receivable · {{ \Carbon\Carbon::parse($item->order_date)->format('M d, Y') }}</small></div><b>{{ $money($item->order_total_amount) }}</b></div>
                @endforeach
                @if($pending_deliveries->isEmpty() && $old_receivables->isEmpty() && $unbilled_rows->isEmpty())
                <div class="sr-no-items"><i class="bi bi-check-circle" aria-hidden="true"></i><span>No outstanding items need attention for these filters.</span></div>
                @endif
            </div>
        </article>

        <article class="fuel-panel sr-panel sr-activity-panel">
            <div class="fuel-panel-head"><div><h2>Recent activity</h2><p>Recent Sales Orders, direct billings, and payments.</p></div><span class="sr-panel-icon"><i class="bi bi-clock-history" aria-hidden="true"></i></span></div>
            <div class="sr-activity-list">
                @forelse($recent_activity as $activity)
                <div class="sr-activity-item"><span class="sr-activity-icon sr-activity-{{ strtolower(str_replace(' ', '-', $activity->activity_type)) }}"><i class="bi {{ $activity->activity_type === 'Payment' ? 'bi-cash' : ($activity->activity_type === 'Billing' ? 'bi-receipt' : 'bi-cart-check') }}" aria-hidden="true"></i></span><div><strong>{{ $activity->activity_type }} · {{ $activity->reference ?: 'Reference unavailable' }}</strong><small>{{ $activity->client_name ?: 'Customer not specified' }} · {{ $formatTimestamp($activity->occurred_at) }}</small></div><b>{{ $money($activity->amount) }}</b></div>
                @empty
                <div class="sr-no-items">No activity recorded during this period.</div>
                @endforelse
            </div>
        </article>
    </section>

    <div class="fuel-dashboard-footer"><span><strong> </strong> </span><span></span></div>
    <script type="application/json" id="sales-receivables-data">@json($trend)</script>
</main>
@endsection

@push('page_scripts')
<script src="{{ asset('js/sales-receivables-dashboard.js') }}?v={{ filemtime(public_path('js/sales-receivables-dashboard.js')) }}" defer></script>
<script src="{{ asset('js/dashboard-ajax.js') }}?v={{ filemtime(public_path('js/dashboard-ajax.js')) }}" defer></script>
@endpush
