<div class="col-12 d-none" id="ar-so-empty-receipt-fields">
    <div class="border rounded-3 p-3">
        <h6 class="fw-semibold">Empty Container Received</h6>
        <p class="small text-muted">Record the empty brand actually received. If this receipt is already in Swapping, select that record so ending stock counts it once.</p>
        <div class="mb-3">
            <label for="ar_so_empty_receipt_mode" class="form-label">Receipt Source</label>
            <select class="form-select form-select-sm" id="ar_so_empty_receipt_mode">
                <option value="DIRECT">Received with this sale</option>
                <option value="SWAPPING">Already recorded in Swapping</option>
            </select>
        </div>
        <div class="mb-3 d-none" id="ar-so-empty-swap-field">
            <label for="ar_so_empty_swapping_idx" class="form-label">Swapping Record for This Customer</label>
            <select class="form-select form-select-sm" name="empty_swapping_idx" id="ar_so_empty_swapping_idx"><option value="">Select swapping record</option></select>
            <div class="invalid-feedback" id="empty_swapping_idxError">Select the swapping record for the same empty receipt.</div>
            <div class="small text-muted mt-1" id="ar-so-empty-swap-note"></div>
        </div>
        <div id="ar-so-empty-direct-fields" class="row g-3">
            <div class="col-md-8">
                <label for="ar_so_empty_received_product_idx" class="form-label">Received Empty Product / Brand</label>
                <select class="form-select form-select-sm lpg-product-picker" name="empty_received_product_idx" id="ar_so_empty_received_product_idx">
                    <option value="">Select received empty product</option>
                    @foreach($product_data->whereIn('category_idx', \App\Services\LpgEmptyInventorySummary::categoryIds())->whereNotIn('product_id', \App\Services\LpgRegulatorProducts::ids())->unique('product_id') as $product)
                        <option value="{{ $product->product_id }}" data-product-code="{{ $product->product_code ?? '' }}">{{ trim(($product->product_name ?? '') . ' ' . ($product->product_unit_measurement ?? '')) }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="empty_received_product_idxError">Select the empty product actually received.</div>
            </div>
            <div class="col-md-4">
                <label for="ar_so_empty_received_quantity" class="form-label">Empty Qty (pcs)</label>
                <input type="number" class="form-control form-control-sm" name="empty_received_quantity" id="ar_so_empty_received_quantity" min="0" max="2147483647" step="1">
                <div class="invalid-feedback" id="empty_received_quantityError">Enter a whole number, zero or more.</div>
            </div>
            <div class="col-12 small text-muted">Enter zero if no empty container was received.</div>
        </div>
    </div>
</div>
