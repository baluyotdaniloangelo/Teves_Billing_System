@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}" rel="stylesheet">
<link href="{{ asset('style/fuel-sales-volume-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-sales-volume-dashboard.css')) }}" rel="stylesheet">
@endpush

@section('content')
@php $liters = fn ($value) => number_format((float) $value, 2) . ' L'; @endphp
<main id="main" class="main fuel-page fuel-dashboard fsv-dashboard">
    <div class="fuel-title-row fsv-title-row">
        <div>
            <p class="fuel-eyebrow">FUEL SALES ANALYTICS</p>
            <h1><span>Fuel Sales Volume</span></h1>
            <p class="fuel-subtitle">Compare delivered Sales Order volume per customer with retail sales at gasoline stations.</p>
        </div>
        <div class="fsv-title-badge"><i class="bi bi-fuel-pump" aria-hidden="true"></i><span>Sales Order · Retail · Liters</span></div>
    </div>

    @if($errors->any())
    <div class="fuel-alert" role="alert"><strong>Please check your filters.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('fuel_sales_volume_dashboard') }}" method="get" class="fuel-filterbar sr-filterbar fsv-filterbar">
        <label class="fsv-filter-field"><span class="fuel-field-label">FROM</span><input type="date" name="start_date" value="{{ $start_date }}" required></label>
        <label class="fsv-filter-field"><span class="fuel-field-label">TO</span><input type="date" name="end_date" value="{{ $end_date }}" min="{{ $start_date }}" required></label>
        <label class="fsv-filter-field fsv-filter-wide"><span class="fuel-field-label">BRANCH</span><select name="company_header"><option value="All">All accessible branches</option>@foreach($branches as $branch)<option value="{{ $branch->branch_id }}" {{ (string) $branch_id === (string) $branch->branch_id ? 'selected' : '' }}>{{ $branch->branch_code }} · {{ $branch->branch_name }}</option>@endforeach</select></label>
        <label class="fsv-filter-field fsv-filter-wide"><span class="fuel-field-label">FUEL PRODUCT</span><select name="product_id"><option value="All">All Fuel products</option>@foreach($fuel_products as $product)<option value="{{ $product->product_id }}" {{ (string) $product_id === (string) $product->product_id ? 'selected' : '' }}>{{ $product->product_name }}</option>@endforeach</select></label>
        <button type="submit" class="fuel-button fuel-button-primary fuel-button-small"><i class="bi bi-funnel" aria-hidden="true"></i> Apply filters</button>
        <a href="{{ route('fuel_sales_volume_dashboard') }}" class="fuel-reset">Reset <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i></a>
    </form>
    <div class="fuel-period-note"><i class="bi bi-calendar3" aria-hidden="true"></i><span>{{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</span><span class="fsv-period-note">Sales Orders use actual delivery date and quantity; Retail uses FUEL cashier report date and pump quantity.</span></div>

    <section class="fuel-stats fsv-stats" aria-label="Fuel sales volume overview">
        <article class="fuel-stat fsv-stat-total"><div class="fuel-stat-label">Total volume sold <i class="bi bi-droplet-half" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ number_format($order_liters + $retail_liters, 2) }} L</div><div class="fuel-stat-foot">Delivered Sales Orders + station Retail</div></article>
        <article class="fuel-stat fsv-stat-orders"><div class="fuel-stat-label">Sales Order deliveries <i class="bi bi-truck" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ number_format($order_liters, 2) }} L</div><div class="fuel-stat-foot">{{ number_format($order_count) }} delivered orders · {{ number_format($active_customers) }} customers</div></article>
        <article class="fuel-stat fsv-stat-retail"><div class="fuel-stat-label">Retail at stations <i class="bi bi-fuel-pump" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ number_format($retail_liters, 2) }} L</div><div class="fuel-stat-foot">{{ number_format($retail_entry_count) }} pump sales entries</div></article>
        <article class="fuel-stat fsv-stat-customers"><div class="fuel-stat-label">Active customers <i class="bi bi-people" aria-hidden="true"></i></div><div class="fuel-stat-value">{{ number_format($active_customers) }}</div><div class="fuel-stat-foot">With Fuel deliveries in this period</div></article>
    </section>

    <section class="fsv-analysis-grid" aria-label="Fuel volume trend and product mix">
        <article class="fuel-panel fsv-panel fsv-trend-panel">
            <div class="fuel-panel-head"><div><h2>Sales volume trend</h2><p>Daily delivered liters by channel · selected period</p></div><span class="fsv-panel-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span></div>
            <div id="fuel-sales-volume-chart" class="fsv-chart" role="img" aria-label="Daily delivered Sales Order liters compared with Retail liters"><p class="fsv-chart-fallback">Volume trend appears here when chart scripts are available.</p></div>
            <div class="fsv-chart-legend"><span><i class="fsv-legend-orders"></i>Sales Order deliveries</span><span><i class="fsv-legend-retail"></i>Retail</span></div>
        </article>

        <article class="fuel-panel fsv-panel fsv-mix-panel">
            <div class="fuel-panel-head"><div><h2>Volume by fuel product</h2><p>Sales Order and Retail shown together</p></div><span class="fsv-panel-icon"><i class="bi bi-bar-chart" aria-hidden="true"></i></span></div>
            @php $mixMax = max(1, (float) $product_mix->max('total_liters')); @endphp
            <div class="fsv-mix-list">
                @forelse($product_mix->take(7) as $item)
                @php $share = min(100, ($item['total_liters'] / $mixMax) * 100); @endphp
                <div class="fsv-mix-row"><div class="fsv-mix-title"><strong>{{ $item['product_name'] }}</strong><span>{{ $liters($item['total_liters']) }}</span></div><div class="fsv-mix-track"><i style="width: {{ $share }}%"></i></div><small>SO {{ $liters($item['sales_order_liters']) }} <span>·</span> Retail {{ $liters($item['retail_liters']) }}</small></div>
                @empty
                <div class="fsv-empty"><i class="bi bi-inbox" aria-hidden="true"></i><span>No fuel volume recorded for this selection.</span></div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="fsv-detail-grid" aria-label="Fuel sales detail">
        <article class="fuel-panel fsv-panel fsv-customers-panel">
            <div class="fuel-panel-head"><div><h2>Sales Order volume by customer</h2><p>Liters are based on recorded delivery details, not full order quantities.</p></div><span class="fsv-count-badge">Top {{ number_format($customer_sales->count()) }} of {{ number_format($active_customers) }}</span></div>
            <div class="fsv-table-scroll"><table class="fsv-table">
                <thead><tr><th>Customer</th><th>Top product</th><th class="text-end">Delivered volume</th><th class="text-end">Orders</th><th class="text-end">Last delivery</th></tr></thead>
                <tbody>
                    @forelse($customer_sales as $customer)
                    <tr><td><strong>{{ $customer->client_name }}</strong></td><td>{{ $customer->top_product }}</td><td class="text-end fsv-volume-cell">{{ $liters($customer->delivered_liters) }}</td><td class="text-end">{{ number_format($customer->order_count) }}</td><td class="text-end">{{ $customer->last_delivery ? \Carbon\Carbon::parse($customer->last_delivery)->format('M d, Y') : '—' }}</td></tr>
                    @empty
                    <tr><td colspan="5" class="fsv-table-empty">No Sales Order deliveries for the selected period.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </article>

        <article class="fuel-panel fsv-panel fsv-retail-panel">
            <div class="fuel-panel-head"><div><h2>Retail by station</h2><p>Gasoline station sales · not assigned to named retail buyers</p></div><span class="fsv-panel-icon"><i class="bi bi-shop" aria-hidden="true"></i></span></div>
            <div class="fsv-retail-list">
                @forelse($retail_by_branch as $branch)
                <div class="fsv-retail-row"><div><strong>{{ $branch->branch_code }}</strong><small>{{ $branch->branch_name ?: 'Station' }} · {{ number_format($branch->report_count) }} cashier reports</small></div><b>{{ $liters($branch->sold_liters) }}</b></div>
                @empty
                <div class="fsv-empty"><i class="bi bi-inbox" aria-hidden="true"></i><span>No station Retail sales for this selection.</span></div>
                @endforelse
            </div>
            <div class="fsv-retail-note"><i class="bi bi-info-circle" aria-hidden="true"></i><span>Retail is grouped by branch and fuel product because cashier pump sales do not capture a named customer.</span></div>
        </article>
    </section>

    <div class="fuel-dashboard-footer"><span>Volume units follow the product's configured fuel measurement.</span><span>Source: Sales Order delivery details and FUEL cashier pump sales</span></div>
    <script type="application/json" id="fuel-sales-volume-data">@json($trend)</script>
</main>
@endsection

@push('page_scripts')
<script src="{{ asset('js/fuel-sales-volume-dashboard.js') }}?v={{ filemtime(public_path('js/fuel-sales-volume-dashboard.js')) }}" defer></script>
<script src="{{ asset('js/dashboard-ajax.js') }}?v={{ filemtime(public_path('js/dashboard-ajax.js')) }}" defer></script>
@endpush
