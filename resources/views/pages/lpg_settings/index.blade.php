@extends('layouts.layout')
@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div><h1>LPG Settings</h1><p>Choose how each LPG product is tracked in the cashier's report.</p></div>
        <a class="btn btn-outline-secondary" href="{{ route('cashierReportLPG') }}"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>LPG Reports</a>
    </div>
    <section class="section">
        <div class="card"><div class="card-body pt-4">
            <h5 class="mb-1">Product Inventory Tracking</h5>
            <p class="text-muted mb-3">Enable either option or both. For an empty-only product, turn off LPG Sell-out and keep Empty Cylinders / Canisters enabled. These settings apply to all LPG report dates, product selections, and printouts.</p>
            @if(session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @if($products->isEmpty())
                <div class="alert alert-light mb-0">No active LPG products are configured.</div>
            @else
            <form action="{{ route('lpg.settings.update') }}" method="POST" id="lpg-product-tracking-form">
                @csrf
                <input type="hidden" id="lpg-tracking-payload" name="products_json" value="">
                <noscript><p class="text-danger">Enable JavaScript to save LPG product tracking settings.</p></noscript>
                <div class="row mb-3"><div class="col-md-6 col-lg-5">
                    <label for="lpg-settings-search" class="form-label">Find a product</label>
                    <input id="lpg-settings-search" type="search" class="form-control" placeholder="Search product name or code" autocomplete="off">
                </div></div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="lpg-product-tracking-table">
                        <thead><tr><th>Product Code</th><th>Product / Capacity</th><th class="text-center">LPG Sell-out</th><th class="text-center">Empty Cylinders / Canisters</th><th>Tracking</th></tr></thead>
                        <tbody>
                        @foreach($products as $product)
                            @php
                                $sellOut = (bool) old('products.'.$product->product_id.'.sell_out_enabled', $product->sell_out_enabled);
                                $emptyInventory = (bool) old('products.'.$product->product_id.'.empty_inventory_enabled', $product->empty_inventory_enabled);
                                $capacity = $product->product_capacity !== null && $product->product_capacity !== ''
                                    ? rtrim(rtrim(number_format((float) $product->product_capacity, 3, '.', ''), '0'), '.').' '.$product->product_capacity_unit
                                    : $product->product_unit_measurement;
                            @endphp
                            <tr data-tracking-product data-product-id="{{ $product->product_id }}" data-search="{{ mb_strtolower($product->product_code.' '.$product->product_name.' '.$capacity) }}">
                                <td class="text-nowrap">{{ $product->product_code ?: '—' }}</td>
                                <td>{{ $product->product_name }}@if($capacity)<small class="d-block text-muted">{{ $capacity }}</small>@endif</td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block m-0"><input class="form-check-input" type="checkbox" role="switch" data-tracking-sell-out aria-label="Track {{ $product->product_name }} in LPG Sell-out" @if($sellOut) checked @endif></div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block m-0"><input class="form-check-input" type="checkbox" role="switch" data-tracking-empty aria-label="Track {{ $product->product_name }} in Empty Cylinders and Canisters" @if($emptyInventory) checked @endif></div>
                                </td>
                                <td data-tracking-label>{{ $sellOut && $emptyInventory ? 'Both' : ($sellOut ? 'Sell-out only' : ($emptyInventory ? 'Empty only' : 'Not tracked')) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <p class="text-muted d-none" id="lpg-settings-no-results">No products match your search.</p>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-top pt-3">
                    <small class="text-muted">New LPG products start with both options enabled.</small>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1" aria-hidden="true"></i>Save LPG Settings</button>
                </div>
            </form>
            @endif
        </div></div>
    </section>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('lpg-product-tracking-form');
    if (!form) return;
    const rows = Array.from(form.querySelectorAll('[data-tracking-product]'));
    form.addEventListener('submit', function () {
        const products = {};
        rows.forEach(function (row) {
            products[row.dataset.productId] = {
                product_idx: Number(row.dataset.productId),
                sell_out_enabled: row.querySelector('[data-tracking-sell-out]').checked,
                empty_inventory_enabled: row.querySelector('[data-tracking-empty]').checked
            };
        });
        document.getElementById('lpg-tracking-payload').value = JSON.stringify(products);
    });
    form.addEventListener('change', function (event) {
        const row = event.target.closest('[data-tracking-product]');
        if (!row) return;
        const sellOut = row.querySelector('[data-tracking-sell-out]').checked;
        const empty = row.querySelector('[data-tracking-empty]').checked;
        row.querySelector('[data-tracking-label]').textContent = sellOut && empty ? 'Both' : (sellOut ? 'Sell-out only' : (empty ? 'Empty only' : 'Not tracked'));
    });
    document.getElementById('lpg-settings-search').addEventListener('input', function () {
        const query = this.value.toLocaleLowerCase().trim();
        let matches = 0;
        rows.forEach(function (row) {
            const show = row.dataset.search.includes(query);
            row.hidden = !show;
            if (show) matches++;
        });
        document.getElementById('lpg-settings-no-results').classList.toggle('d-none', matches !== 0);
    });
});
</script>
@endsection
