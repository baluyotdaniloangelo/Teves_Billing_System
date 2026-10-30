@php
    $emptyFormModes = [
        'BEGINNING' => ['title' => 'Beginning Inventory', 'suffix' => 'Beginning', 'fields' => ['beginning_quantity' => 'Beginning Quantity']],
        'ADDITIONS' => ['title' => 'Additions (+)', 'suffix' => 'Additions', 'fields' => \App\Models\CashiersReportModel_LPG_EmptyInventory::ADDITIONS],
        'DEDUCTIONS' => ['title' => 'Deductions (-)', 'suffix' => 'Deductions', 'fields' => \App\Models\CashiersReportModel_LPG_EmptyInventory::DEDUCTIONS],
    ];
@endphp
@foreach($emptyFormModes as $mode => $settings)
<div class="modal fade" id="CashierReportLpgEmpty{{ $settings['suffix'] }}Modal" tabindex="-1" aria-labelledby="LpgEmpty{{ $settings['suffix'] }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3">
                <div><h5 class="modal-title fw-bold mb-1" id="LpgEmpty{{ $settings['suffix'] }}Label">{{ $settings['title'] }}</h5><small>Empty Cylinders / Canisters</small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="CashierReportLpgEmpty{{ $settings['suffix'] }}Form" class="lpg-empty-entry-form" data-entry-mode="{{ $mode }}" novalidate>
                    @csrf
                    <input type="hidden" name="entry_mode" value="{{ $mode }}">
                    <input type="hidden" name="entry_field" value="">
                    <input type="hidden" name="cashiers_report_lpg_empty_inventory_id" value="0">
                    <input type="hidden" name="cashiers_report_id" value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">
                    <div class="alert alert-danger d-none lpg-empty-form-error" role="alert"></div>
                    <div class="mb-3">
                        <label for="lpg_empty_{{ $mode }}_product_idx" class="form-label fw-semibold">Product / Item</label>
                        <select class="form-select form-select-sm lpg-empty-product lpg-product-picker" id="lpg_empty_{{ $mode }}_product_idx" name="product_idx" required>
                            <option value="">Select product</option>
                            @foreach($product_data->whereNotIn('product_id', \App\Services\LpgRegulatorProducts::ids()) as $product)
                                @php
                                    $capacityLabel = $product->product_capacity && $product->product_capacity_unit
                                        ? rtrim(rtrim(number_format((float) $product->product_capacity, 3, '.', ''), '0'), '.') . ' ' . $product->product_capacity_unit
                                        : 'Capacity not set';
                                @endphp
                                <option value="{{ $product->product_id }}" data-capacity="{{ $capacityLabel }}" data-product-code="{{ $product->product_code ?? '' }}" data-price="{{ $product->product_price ?? 0 }}">{{ $product->product_name }} — {{ $capacityLabel }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Select a product.</div>
                        <div class="form-text lpg-empty-capacity-label">Grouping follows the capacity set on the product.</div>
                    </div>
                    <p class="small text-muted lpg-empty-entry-note">Enter {{ strtolower($settings['title']) }} totals for this report. Quantities are in pieces.</p>
                    @foreach($settings['fields'] as $field => $label)
                        <div class="mb-3 lpg-empty-quantity-field" data-quantity-field="{{ $field }}">
                            <label for="lpg_empty_{{ $mode }}_{{ $field }}" class="form-label fw-semibold">{{ $label }} (pcs)</label>
                            <input type="number" class="form-control form-control-sm lpg-empty-quantity" id="lpg_empty_{{ $mode }}_{{ $field }}" name="{{ $field }}" min="0" max="2147483647" step="1" value="0" required>
                            <div class="invalid-feedback">Enter a whole number of zero or more.</div>
                        </div>
                    @endforeach
                    <div class="border rounded-3 bg-light p-3 mb-3">
                        <div class="d-flex justify-content-between"><span>Beginning</span><output data-summary-field="beginning">0</output></div>
                        <div class="d-flex justify-content-between"><span>Additions (+)</span><output data-summary-field="additions">0</output></div>
                        <div class="d-flex justify-content-between"><span>Deductions (-)</span><output data-summary-field="deductions">0</output></div>
                        <div class="d-flex justify-content-between border-top mt-2 pt-2 fw-bold"><span>Computed Ending Inventory</span><output data-summary-field="ending">0</output></div>
                    </div>
                    <div class="mb-3">
                        <label for="lpg_empty_{{ $mode }}_remarks" class="form-label fw-semibold">Remarks</label>
                        <textarea class="form-control" id="lpg_empty_{{ $mode }}_remarks" name="remarks" rows="2" maxlength="5000" placeholder="Optional"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="CashierReportLpgEmpty{{ $settings['suffix'] }}Form" class="btn btn-success lpg-empty-save"><i class="bi bi-save me-2" aria-hidden="true"></i>Save {{ $settings['title'] }}</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="modal fade" id="LpgEmptyInventorySourcesModal" tabindex="-1" aria-labelledby="LpgEmptyInventorySourcesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <div><h5 class="modal-title" id="LpgEmptyInventorySourcesLabel">Empty-stock Sources</h5><small>Source transactions and remaining manual totals, in pieces</small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="lpg-empty-source-error" role="alert"></div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead><tr><th>Item</th><th>Source / State</th><th class="text-end">Quantity</th><th>Remarks</th><th>Reference / Account</th><th>Action</th></tr></thead>
                        <tbody id="lpg-empty-source-rows"></tbody>
                    </table>
                </div>
                <div class="text-end fw-semibold">Total: <span id="lpg-empty-source-total">—</span> pcs</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="lpg-empty-source-add"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add / Edit Total</button>
            </div>
        </div>
    </div>
</div>
