@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}" rel="stylesheet">
@endpush

@section('content')
@php
    $total_sales = $result->sum('total_sales');
    $total_cash = $result->sum('total_cash_sales');
    $total_non_cash = $result->sum('non_cash_payment');
    $total_short_over = $result->sum('short_over');
    $total_over = $result->where('short_over', '>', 0)->sum('short_over');
    $total_short = $result->where('short_over', '<', 0)->sum('short_over');
    $station_count = $result->pluck('branch_id')->unique()->count();
    $sales_change = $total_sales - $previous_sales;
    $sales_change_percent = $previous_sales > 0 ? ($sales_change / $previous_sales) * 100 : null;
    $product_sales_total = $product_breakdown->sum('total_sales');
    $shortage_count = (int) ($shortage_alerts->count ?? 0);
    $signed_amount = function ($amount) {
        return ($amount < 0 ? '−' : ($amount > 0 ? '+' : '')) . '₱' . number_format(abs($amount), 2);
    };
@endphp

<main id="main" class="main fuel-page fuel-dashboard">
    <div class="fuel-title-row">
        <div>
            <p class="fuel-eyebrow">STATION PERFORMANCE</p>
            <h1><span>Cashier Dashboard</span></h1>
            <p class="fuel-subtitle">Sales, cash position, and report activity by cashier report type.</p>
        </div>
        <div class="fuel-actions">
            <button type="button" class="fuel-button" id="fuel-print"><i class="bi bi-printer" aria-hidden="true"></i> Print report</button>
            <button type="button" class="fuel-button fuel-button-primary" id="fuel-export"><i class="bi bi-download" aria-hidden="true"></i> Export CSV</button>
        </div>
    </div>

    <nav class="fuel-dashboard-tabs" aria-label="Cashier dashboard type">
        <a class="fuel-dashboard-tab is-active" href="{{ route('dashboard') }}" aria-current="page"><i class="bi bi-fuel-pump" aria-hidden="true"></i> Fuel</a>
        <button class="fuel-dashboard-tab fuel-dashboard-tab-disabled" type="button" disabled aria-describedby="lpg-dashboard-status"><i class="bi bi-fire" aria-hidden="true"></i> LPG <span>Coming next</span></button>
    </nav>
    <span id="lpg-dashboard-status" class="visually-hidden">The LPG cashier dashboard will be added next.</span>

    @if($errors->any())
    <div class="fuel-alert" role="alert"><strong>Please check your filters.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('dashboard') }}" method="get" class="fuel-filterbar" id="fuel-filters">
        <div class="fuel-period-filter">
            <span class="fuel-field-label">REPORTING PERIOD</span>
            <div class="fuel-date-fields">
                <label class="visually-hidden" for="fuel-start-date">Start date</label>
                <input type="date" id="fuel-start-date" name="start_date" value="{{ $start_date }}" required>
                <span aria-hidden="true">→</span>
                <label class="visually-hidden" for="fuel-end-date">End date</label>
                <input type="date" id="fuel-end-date" name="end_date" value="{{ $end_date }}" min="{{ $start_date }}" required>
            </div>
        </div>
        <div class="fuel-filter-divider" aria-hidden="true"></div>
        <label class="fuel-station-filter" for="fuel-station"><span class="fuel-field-label">STATION</span>
            <select name="company_header" id="fuel-station">
                <option value="All">All accessible stations</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->branch_id }}" {{ (string)$company_header === (string)$branch->branch_id ? 'selected' : '' }}>{{ $branch->branch_code }} · {{ $branch->branch_name }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit" class="fuel-button fuel-button-small">Apply filters</button>
        <a href="{{ route('dashboard') }}" class="fuel-reset">Reset <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i></a>
        <span class="fuel-source"><i></i> FUEL cashier reports</span>
    </form>
    <div class="fuel-period-note"><i class="bi bi-calendar3" aria-hidden="true"></i><span>{{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</span><span class="fuel-period-count" id="fuel-record-count">{{ $result->count() }} daily records</span></div>

    <section class="fuel-stats" aria-label="Cashier report totals">
        <article class="fuel-stat"><div class="fuel-stat-label">Total sales <i class="bi bi-arrow-up-right" aria-hidden="true"></i></div><div class="fuel-stat-value" id="fuel-sales">₱{{ number_format($total_sales, 2) }}</div><div class="fuel-stat-foot"><i class="fuel-dot fuel-orange"></i><span id="fuel-station-count">Across {{ $station_count }} stations</span></div></article>
        <article class="fuel-stat"><div class="fuel-stat-label">Cash on hand <span aria-hidden="true">₱</span></div><div class="fuel-stat-value" id="fuel-cash">₱{{ number_format($total_cash, 2) }}</div><div class="fuel-stat-foot">As recorded in the cashier reports</div></article>
        <article class="fuel-stat"><div class="fuel-stat-label">Non-cash payments <i class="bi bi-credit-card" aria-hidden="true"></i></div><div class="fuel-stat-value" id="fuel-noncash">₱{{ number_format($total_non_cash, 2) }}</div><div class="fuel-stat-foot" id="fuel-noncash-share">{{ number_format($total_sales ? $total_non_cash / $total_sales * 100 : 0, 2) }}% of recorded sales</div></article>
        <article class="fuel-stat fuel-stat-variance"><div class="fuel-stat-label">Net short / over <i class="bi bi-arrow-left-right" aria-hidden="true"></i></div><div class="fuel-stat-value {{ $total_short_over < 0 ? 'fuel-negative' : 'fuel-positive' }}" id="fuel-variance">{{ $signed_amount($total_short_over) }}</div><div class="fuel-stat-foot" id="fuel-variance-note">{{ $total_short_over < 0 ? 'Net short' : ($total_short_over > 0 ? 'Net over' : 'Balanced') }} across selected reports</div></article>
    </section>

    <section class="fuel-analysis" aria-label="Sales and cash analysis">
        <article class="fuel-panel fuel-chart-panel">
            <div class="fuel-panel-head"><div><h2>Sales performance</h2><p>Compare collections across your stations</p></div><div class="fuel-segmented" role="group" aria-label="Chart grouping"><button type="button" data-fuel-chart="station" aria-pressed="true">By station</button><button type="button" data-fuel-chart="day" aria-pressed="false">By day</button></div></div>
            <div id="reportsChart" class="fuel-chart" role="img" aria-label="Sales by station"><p class="fuel-chart-fallback">Sales figures are available in the daily summary below.</p></div>
            <div class="fuel-chart-footer"><span><i class="fuel-legend"></i> Total sales</span><span id="fuel-chart-caption">{{ \Carbon\Carbon::parse($start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</span></div>
        </article>
        <article class="fuel-panel fuel-cash-check">
            <div class="fuel-panel-head"><div><h2>Cash check</h2><p>Small differences. Clear visibility.</p></div><i class="bi bi-arrow-left-right" aria-hidden="true"></i></div>
            <div class="fuel-cash-total"><span>NET VARIANCE</span><strong id="fuel-check-total" class="{{ $total_short_over < 0 ? 'fuel-negative' : 'fuel-positive' }}">{{ $signed_amount($total_short_over) }}</strong><small id="fuel-check-status">{{ $total_short_over < 0 ? 'Short' : ($total_short_over > 0 ? 'Over' : 'Balanced') }} recorded cash position</small></div>
            <dl class="fuel-breakdown"><div><dt><i class="fuel-dot fuel-green"></i>Total over</dt><dd id="fuel-over-total">{{ $signed_amount($total_over) }}</dd></div><div><dt><i class="fuel-dot fuel-red"></i>Total short</dt><dd id="fuel-short-total">{{ $signed_amount($total_short) }}</dd></div></dl>
            <button type="button" class="fuel-review" id="fuel-review">Review shortages <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
        </article>
    </section>

    <section class="fuel-insights" aria-label="Sales breakdown and operational insights">
        <article class="fuel-panel fuel-insight-panel">
            <div class="fuel-panel-head"><div><h2>Sales vs. previous period</h2><p>Same number of days, immediately before this date range</p></div><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
            <div class="fuel-comparison">
                <div class="fuel-comparison-current"><span>SELECTED PERIOD</span><strong>₱{{ number_format($total_sales, 2) }}</strong><small>{{ \Carbon\Carbon::parse($start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</small></div>
                <div class="fuel-comparison-track" role="img" aria-label="Selected period sales {{ number_format($total_sales, 2) }} pesos compared with previous period sales {{ number_format($previous_sales, 2) }} pesos">
                    @php $comparisonMax = max($total_sales, $previous_sales, 1); @endphp
                    <span class="fuel-comparison-bar fuel-comparison-bar-current" style="width: {{ min(100, $total_sales / $comparisonMax * 100) }}%"></span>
                    <span class="fuel-comparison-bar fuel-comparison-bar-previous" style="width: {{ min(100, $previous_sales / $comparisonMax * 100) }}%"></span>
                </div>
                <div class="fuel-comparison-previous"><span>PREVIOUS PERIOD</span><strong>₱{{ number_format($previous_sales, 2) }}</strong><small>{{ \Carbon\Carbon::parse($previous_period_start)->format('M d') }} – {{ \Carbon\Carbon::parse($previous_period_end)->format('M d, Y') }}</small></div>
                <div class="fuel-comparison-result {{ $sales_change < 0 ? 'fuel-negative' : 'fuel-positive' }}">
                    <i class="bi {{ $sales_change < 0 ? 'bi-arrow-down-right' : 'bi-arrow-up-right' }}" aria-hidden="true"></i>
                    @if($sales_change_percent === null)
                        <strong>{{ $previous_sales > 0 ? number_format($sales_change_percent, 1).'%' : 'No prior sales' }}</strong>
                    @else
                        <strong>{{ $sales_change > 0 ? '+' : '' }}{{ number_format($sales_change_percent, 1) }}%</strong>
                    @endif
                    <span>{{ $sales_change < 0 ? 'below' : ($sales_change > 0 ? 'above' : 'equal to') }} the previous period · ₱{{ number_format(abs($sales_change), 2) }}</span>
                </div>
                <p class="fuel-target-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Previous-period benchmark. Station sales targets are not configured yet.</p>
            </div>
        </article>

        <article class="fuel-panel fuel-insight-panel">
            <div class="fuel-panel-head"><div><h2>Fuel product mix</h2><p>Sales recorded from pump readings in this period</p></div><i class="bi bi-fuel-pump" aria-hidden="true"></i></div>
            <div class="fuel-breakdown-list">
                @forelse($product_breakdown as $product)
                @php $productShare = $product_sales_total > 0 ? min(100, (float) $product->total_sales / $product_sales_total * 100) : 0; @endphp
                <div class="fuel-product-row"><div class="fuel-product-title"><strong>{{ $product->product_name }}</strong><span>₱{{ number_format($product->total_sales, 2) }}</span></div><div class="fuel-product-track"><i style="width: {{ $productShare }}%"></i></div><small>{{ number_format($productShare, 1) }}% of pump-recorded sales</small></div>
                @empty
                <p class="fuel-insight-empty">No pump product sales were recorded for this selection.</p>
                @endforelse
            </div>
        </article>

        <article class="fuel-panel fuel-insight-panel">
            <div class="fuel-panel-head"><div><h2>Shift comparison</h2><p>Sales and cash variance by cashier shift</p></div><i class="bi bi-clock-history" aria-hidden="true"></i></div>
            <div class="fuel-shift-list">
                @forelse($shift_breakdown as $shift)
                @php $shiftShare = $total_sales > 0 ? min(100, (float) $shift->total_sales / $total_sales * 100) : 0; @endphp
                <div class="fuel-shift-row"><div class="fuel-shift-title"><strong>{{ $shift->shift ?: 'Unspecified shift' }}</strong><span>₱{{ number_format($shift->total_sales, 2) }}</span></div><div class="fuel-product-track"><i style="width: {{ $shiftShare }}%"></i></div><small>{{ $shift->report_count }} {{ (int) $shift->report_count === 1 ? 'report' : 'reports' }} <span class="{{ $shift->short_over < 0 ? 'fuel-negative' : 'fuel-positive' }}">· {{ $signed_amount($shift->short_over) }} short / over</span></small></div>
                @empty
                <p class="fuel-insight-empty">No shift sales were recorded for this selection.</p>
                @endforelse
            </div>
        </article>

        <article class="fuel-panel fuel-insight-panel fuel-alert-panel">
            <div class="fuel-panel-head"><div><h2>Cash alerts</h2><p>Shortages reported in the selected range</p></div><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></div>
            @if($shortage_count > 0)
            <div class="fuel-alert-summary"><span class="fuel-alert-icon"><i class="bi bi-exclamation-lg" aria-hidden="true"></i></span><div><strong>{{ $shortage_count }} {{ $shortage_count === 1 ? 'report needs' : 'reports need' }} review</strong><p>Combined short: <b>₱{{ number_format(abs((float) $shortage_alerts->total_short), 2) }}</b></p><small>Largest single short: ₱{{ number_format(abs((float) $shortage_alerts->largest_short), 2) }}</small></div></div>
            <a href="#fuel-records" class="fuel-alert-link">Review daily summary <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            @else
            <div class="fuel-alert-clear"><i class="bi bi-check-circle" aria-hidden="true"></i><div><strong>No cash shortages reported</strong><p>There are no negative short / over entries in this period.</p></div></div>
            @endif
            <p class="fuel-alert-limits">Stock-level and missing-shift alerts are not shown because those thresholds and schedules are not configured.</p>
        </article>
    </section>

    <section class="fuel-panel fuel-activity-panel" aria-labelledby="fuel-activity-title">
        <div class="fuel-panel-head fuel-activity-head"><div><h2 id="fuel-activity-title">Recent cashier reports</h2><p>Latest reports submitted within the selected period and station filter.</p></div><span class="fuel-activity-count">{{ $recent_activity->count() }} recent</span></div>
        <div class="fuel-activity-list">
            @forelse($recent_activity as $activity)
            <a class="fuel-activity-item" href="{{ route('cashiers_report_form_fuel', $activity->cashiers_report_id) }}">
                <span class="fuel-activity-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
                <span class="fuel-activity-main"><strong>{{ $activity->cashiers_report_no ?: 'Cashier report' }} · {{ $activity->branch_code ?: 'Station' }}</strong><small>{{ $activity->cashiers_name ?: 'Cashier not specified' }} · {{ $activity->shift ?: 'Shift not specified' }} · {{ \Carbon\Carbon::parse($activity->report_date)->format('M d, Y') }}</small></span>
                <span class="fuel-activity-sales">₱{{ number_format($activity->total_sales, 2) }}<small>{{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : 'Date unavailable' }}</small></span>
                <i class="bi bi-chevron-right fuel-activity-chevron" aria-hidden="true"></i>
            </a>
            @empty
            <p class="fuel-insight-empty">No cashier reports were submitted during this selection.</p>
            @endforelse
        </div>
    </section>

    <section class="fuel-panel fuel-records" id="fuel-records" aria-labelledby="fuel-summary-title">
        <div class="fuel-panel-head fuel-table-head"><div><h2 id="fuel-summary-title">Daily summary <span class="fuel-count" id="fuel-table-count">{{ $result->count() }}</span></h2><p>Daily station totals from your FUEL cashier reports.</p></div><div class="fuel-table-tools"><label class="fuel-short-toggle"><input type="checkbox" id="fuel-short-only"> Shortages only</label><label class="fuel-search"><i class="bi bi-search" aria-hidden="true"></i><input type="search" id="fuel-search" placeholder="Search station…" aria-label="Search station"></label></div></div>
        <div class="fuel-table-scroll">
            <table class="fuel-table" id="salesTable">
                <caption class="visually-hidden">Cashier report daily summary. All amounts are in Philippine pesos.</caption>
                <thead><tr><th scope="col" aria-sort="descending"><button type="button" data-fuel-sort="report_date">Report date <span aria-hidden="true">↓</span></button></th><th scope="col" aria-sort="none"><button type="button" data-fuel-sort="branch_code">Station <span aria-hidden="true">↕</span></button></th><th scope="col" class="fuel-numeric" aria-sort="none"><button type="button" data-fuel-sort="total_sales">Total sales <span aria-hidden="true">↕</span></button></th><th scope="col" class="fuel-numeric">Cash on hand</th><th scope="col" class="fuel-numeric">Non-cash</th><th scope="col" class="fuel-numeric" aria-sort="none"><button type="button" data-fuel-sort="short_over">Short / over <span aria-hidden="true">↕</span></button></th></tr></thead>
                <tbody id="fuel-rows">
                    @foreach($result as $index => $row)
                    <tr data-fuel-index="{{ $index }}" data-matches="true"><td>{{ \Carbon\Carbon::parse($row->report_date)->format('M d, Y') }}</td><td><div class="fuel-branch-cell"><span class="fuel-branch-icon"><i class="bi bi-fuel-pump" aria-hidden="true"></i></span><div><strong>{{ $row->branch_code }}</strong><small>{{ $row->branch_name }}</small></div></div></td><td class="fuel-numeric fuel-sales-cell">₱{{ number_format($row->total_sales, 2) }}</td><td class="fuel-numeric">₱{{ number_format($row->total_cash_sales, 2) }}</td><td class="fuel-numeric">₱{{ number_format($row->non_cash_payment, 2) }}</td><td class="fuel-numeric"><span class="fuel-variance-pill {{ $row->short_over < 0 ? 'fuel-negative' : 'fuel-positive' }}">{{ $signed_amount($row->short_over) }}</span><span class="visually-hidden">{{ $row->short_over < 0 ? ' short' : ($row->short_over > 0 ? ' over' : ' balanced') }}</span></td></tr>
                    @endforeach
                </tbody>
                <tfoot id="fuel-totals"><tr><th scope="row" colspan="2" id="fuel-total-label">TOTAL · {{ $result->count() }} REPORTS</th><td class="fuel-numeric" id="fuel-table-sales">₱{{ number_format($total_sales, 2) }}</td><td class="fuel-numeric" id="fuel-table-cash">₱{{ number_format($total_cash, 2) }}</td><td class="fuel-numeric" id="fuel-table-noncash">₱{{ number_format($total_non_cash, 2) }}</td><td class="fuel-numeric" id="fuel-table-variance">{{ $signed_amount($total_short_over) }}</td></tr></tfoot>
            </table>
            <p class="fuel-empty" id="fuel-empty" {{ $result->isNotEmpty() ? 'hidden' : '' }}>No reports match this selection. Try another date range or station.</p>
        </div>
        <div class="fuel-table-footer"><span id="fuel-showing" aria-live="polite">{{ $result->count() }} daily records</span><span class="fuel-table-legend"><i class="fuel-dot fuel-green"></i> Over <i class="fuel-dot fuel-red"></i> Short <span>All amounts in PHP</span></span><div class="fuel-pagination"><label for="fuel-page-size" class="visually-hidden">Records per page</label><select id="fuel-page-size"><option value="10">10 / page</option><option value="25">25 / page</option><option value="50">50 / page</option></select><button type="button" id="fuel-prev" aria-label="Previous page">‹</button><span id="fuel-page-label">1 / 1</span><button type="button" id="fuel-next" aria-label="Next page">›</button></div></div>
    </section>
    <div class="fuel-dashboard-footer"><span><strong>TEVES.</strong> Clarity for every collection.</span><span>Source: FUEL cashier reports · All amounts in PHP</span></div>
    <div id="fuel-status" class="fuel-toast" role="status" aria-live="polite"></div>
    <noscript><p class="fuel-alert">Enable JavaScript for chart controls, search, CSV export, and print. Date and station filters remain available.</p></noscript>
</main>
@endsection

@push('page_scripts')
<script type="application/json" id="fuel-dashboard-data">@json($result)</script>
<script src="{{ asset('js/fuel-dashboard.js') }}?v={{ filemtime(public_path('js/fuel-dashboard.js')) }}" defer></script>
<script src="{{ asset('js/dashboard-ajax.js') }}?v={{ filemtime(public_path('js/dashboard-ajax.js')) }}" defer></script>
@endpush
