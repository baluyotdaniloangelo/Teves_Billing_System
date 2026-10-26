@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}" rel="stylesheet">
<link href="{{ asset('style/sales-order.css') }}?v={{ filemtime(public_path('style/sales-order.css')) }}" rel="stylesheet">
@endpush

@section('content')
<main id="main" class="main fuel-page fuel-dashboard so-page">
    <div class="fuel-title-row so-title-row">
        <div>
            <p class="fuel-eyebrow">ORDER MANAGEMENT</p>
            <h1><span>Ship To</span></h1>
            <p class="fuel-subtitle">Manage direct orders, quotations, and completed deliveries.</p>
        </div>
        <div class="so-title-actions">
            <span class="so-title-badge"><i class="bi bi-truck" aria-hidden="true"></i> Delivery &amp; Order Tracking</span>
            <div id="sales_order_option"><button type="button" id="sales-order-create-button" class="fuel-button fuel-button-primary so-create-button" data-bs-toggle="modal" data-bs-target="#CreateSalesOrderModal">
    <i class="bi bi-plus-lg" aria-hidden="true"></i><span id="so-create-button-label">Create Quotation</span>
</button></div>
        </div>
    </div>

    <section class="fuel-stats so-stats" aria-label="Ship To overview">
        <article class="fuel-stat so-stat-orders">
            <div class="fuel-stat-label">Sales Orders <i class="bi bi-cart-check" aria-hidden="true"></i></div>
            <div class="fuel-stat-value">{{ number_format($salesOrderCounts['orders']) }}</div>
            <div class="fuel-stat-foot">Direct orders and approved quotations</div>
        </article>
        <article class="fuel-stat so-stat-quotations">
            <div class="fuel-stat-label">Quotations <i class="bi bi-file-earmark-text" aria-hidden="true"></i></div>
            <div class="fuel-stat-value">{{ number_format($salesOrderCounts['quotations']) }}</div>
            <div class="fuel-stat-foot">Pending and rejected quotations</div>
        </article>
        <article class="fuel-stat so-stat-delivered">
            <div class="fuel-stat-label">Delivered <i class="bi bi-check2-circle" aria-hidden="true"></i></div>
            <div class="fuel-stat-value">{{ number_format($salesOrderCounts['delivered']) }}</div>
            <div class="fuel-stat-foot">Completed deliveries</div>
        </article>
    </section>

    <section class="fuel-panel so-orders-panel" aria-label="Order lists">
        <div class="fuel-panel-head so-panel-heading">
            <div><h2>Order Tracking</h2><p>Approved quotations move into Sales Order. Delivered orders are listed separately.</p></div>
        </div>
        @include('pages.sales_order_v2.tabs.navigation')
        @include('pages.sales_order_v2.tabs.orders')
    </section>

    @include('pages.sales_order_v2.modals.edit_sales_order')
    @include('pages.sales_order_v2.modals.create_sales_order')
    @include('pages.sales_order_v2.modals.preview_sales_order')
    @include('pages.sales_order_v2.modals.delete_sales_order')
    @include('pages.sales_order_v2.modals.approve_quotation')
    @include('pages.sales_order_v2.modals.disapprove_quotation')
    @include('pages.sales_order_v2.modals.create_receivable')
</main>
@endsection

@push('page_scripts')
    @include('pages.sales_order_v2.scripts.index')
    @include('pages.sales_order_v2.scripts.edit_modal')
    @include('layouts.sales_order_form_delivery_script')
    @include('layouts.sales_order_form_script')
@endpush
