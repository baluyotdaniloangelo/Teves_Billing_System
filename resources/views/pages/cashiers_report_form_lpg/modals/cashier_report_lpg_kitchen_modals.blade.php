<div class="modal fade" id="LpgKitchenSourcesModal" tabindex="-1" aria-labelledby="LpgKitchenSourcesTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <div><h5 class="modal-title" id="LpgKitchenSourcesTitle">Kitchen Source Records</h5><small id="lpg-kitchen-source-selection">This report</small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="lpg-kitchen-source-error" role="alert"></div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead><tr><th>Item</th><th>Supplier / Account</th><th>DR / Type</th><th>Qty</th><th>SRP</th><th>Gross Amount</th><th>Remarks</th><th>Action</th></tr></thead>
                        <tbody id="lpg-kitchen-source-rows"></tbody>
                    </table>
                </div>
                <div class="text-end fw-semibold">Total Quantity: <span id="lpg-kitchen-source-total">—</span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="lpg-kitchen-source-add"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add Entry</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="LpgKitchenMovementModal" tabindex="-1" aria-labelledby="LpgKitchenMovementTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <div><h5 class="modal-title" id="LpgKitchenMovementTitle">Kitchen Stock Movement</h5><small>Kitchen Products &amp; Accessories</small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="LpgKitchenMovementForm" novalidate>
                @csrf
                <input type="hidden" name="cashiers_report_id" value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">
                <input type="hidden" name="cashiers_report_lpg_kitchen_movement_id" id="lpg_kitchen_movement_id" value="0">
                <input type="hidden" name="movement_type" id="lpg_kitchen_movement_type">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="lpg-kitchen-movement-error" role="alert"></div>
                    <div class="mb-3">
                        <label class="form-label" for="lpg_kitchen_product_idx">Product / Item</label>
                        <select class="form-select form-select-sm lpg-product-picker" id="lpg_kitchen_product_idx" name="product_idx" required>
                            <option value="">Select kitchen product</option>
                            @foreach($product_data->whereIn('category_idx', \App\Services\LpgKitchenProducts::categoryIds())->unique('product_id') as $product)
                                <option value="{{ $product->product_id }}" data-product-code="{{ $product->product_code ?? '' }}">{{ trim(($product->product_name ?? '') . ' ' . ($product->product_unit_measurement ?? '')) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Select a kitchen product.</div>
                    </div>
                    <div class="mb-3" id="lpg-kitchen-client-field">
                        <label class="form-label" for="lpg_kitchen_client_idx" id="lpg-kitchen-client-label">Client / Account</label>
                        <select class="form-select form-select-sm" id="lpg_kitchen_client_idx" name="client_idx" required>
                            <option value="">Select client / account</option>
                            @foreach($client_data as $client)<option value="{{ $client->client_id }}">{{ $client->client_name }}</option>@endforeach
                        </select>
                        <div class="invalid-feedback" id="lpg-kitchen-client-error">Select a client / account.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="lpg_kitchen_reference_no">DR / Reference No.</label>
                        <input type="text" class="form-control form-control-sm" id="lpg_kitchen_reference_no" name="reference_no" maxlength="100" placeholder="Optional">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="lpg_kitchen_quantity">Quantity</label>
                        <input type="number" class="form-control form-control-sm" id="lpg_kitchen_quantity" name="quantity" min="0.01" max="9999999999.99" step="0.01" required>
                        <div class="invalid-feedback">Enter a quantity greater than zero.</div>
                    </div>
                    <div>
                        <label class="form-label" for="lpg_kitchen_remarks">Remarks</label>
                        <textarea class="form-control" id="lpg_kitchen_remarks" name="remarks" rows="2" maxlength="5000"></textarea>
                    </div>
                    <p class="small text-muted mt-3 mb-0" id="lpg-kitchen-movement-effect"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="save-lpg-kitchen-movement">Save Movement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="LpgKitchenDeleteModal" tabindex="-1" aria-labelledby="LpgKitchenDeleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 rounded-4">
        <div class="modal-header"><h5 class="modal-title" id="LpgKitchenDeleteTitle">Delete Kitchen Movement</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body"><p>Delete <strong id="lpg-kitchen-delete-item"></strong> from this report?</p><div class="alert alert-danger d-none" id="lpg-kitchen-delete-error" role="alert"></div></div>
        <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" id="delete-lpg-kitchen-movement">Delete</button></div>
    </div></div>
</div>
