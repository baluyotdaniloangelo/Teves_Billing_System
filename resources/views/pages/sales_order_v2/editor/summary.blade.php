<header class="so-form-heading">
    <div>
        @unless(request()->boolean('embedded') || request()->boolean('modal'))
            <a class="so-form-back" href="{{ route('salesorder') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> All sales orders</a>
        @endunless
        <p class="so-form-eyebrow">ORDER MANAGEMENT / SALES ORDER</p>
        <h1>Sales Order</h1>
        <p>Review the order, deliveries, payments, and receivable balance in one place.</p>
    </div>
    <span class="so-form-number" id="so-editor-control_no">{{ $sales_order_data[0]['sales_order_control_number'] }}</span>
</header>

<section class="so-form-stats" aria-label="Sales Order summary" data-summary-url="{{ route('sales_order_form_summary') }}">
    <article class="so-form-stat">
        <div class="so-form-stat-heading"><span class="so-form-stat-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span><span>Order total</span></div>
        <strong id="so-summary-total">₱{{ number_format($formSummary['order_total'], 2) }}</strong>
        <p>Total due for {{ number_format($formSummary['item_count']) }} {{ $formSummary['item_count'] === 1 ? 'item' : 'items' }}</p>
    </article>
    <article class="so-form-stat">
        <div class="so-form-stat-heading"><span class="so-form-stat-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span><span>Payments received</span></div>
        <strong id="so-summary-paid">₱{{ number_format($formSummary['payments_received'], 2) }}</strong>
        <p><span id="so-summary-payment-percent">{{ number_format($formSummary['payment_percentage'], 1) }}%</span> of the order total · excludes uncleared PDCs</p>
    </article>
    <article class="so-form-stat">
        <div class="so-form-stat-heading"><span class="so-form-stat-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span><span>Balance due</span></div>
        <strong id="so-summary-balance">₱{{ number_format($formSummary['balance_due'], 2) }}</strong>
        <p>Remaining receivable balance</p>
    </article>
    <article class="so-form-stat so-form-stat-delivery">
        <div class="so-form-stat-heading"><span class="so-form-stat-icon"><i class="bi bi-truck" aria-hidden="true"></i></span><span>Delivery progress</span></div>
        <strong id="so-summary-delivery-percent">{{ $formSummary['delivery_progress'] }}%</strong>
        <div class="so-form-progress" role="progressbar" aria-label="Delivery progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $formSummary['delivery_progress'] }}"><span style="width: {{ $formSummary['delivery_progress'] }}%"></span></div>
        <p id="so-summary-delivery-value">₱{{ number_format($formSummary['delivered_value'], 2) }} of ₱{{ number_format($formSummary['delivery_basis'], 2) }} in ordered items</p>
    </article>
</section>

<section class="so-form-overview" aria-label="Order overview">
    <div><i class="bi bi-person" aria-hidden="true"></i><span>Client</span><strong>{{ $sales_order_data[0]['client_name'] }}</strong></div>
    <div><i class="bi bi-shop" aria-hidden="true"></i><span>Branch</span><strong>{{ $sales_order_data[0]['branch_name'] }}</strong></div>
    <div><i class="bi bi-calendar3" aria-hidden="true"></i><span>Order date</span><strong>{{ $sales_order_data[0]['sales_order_date'] }}</strong></div>
    <div><i class="bi bi-tag" aria-hidden="true"></i><span>Status</span><strong class="so-form-status" id="so-summary-status">{{ $formSummary['status'] }}</strong></div>
</section>
