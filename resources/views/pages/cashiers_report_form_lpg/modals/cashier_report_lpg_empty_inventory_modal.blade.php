<div class="modal fade" id="CashierReportLpgEmptyInventoryModal" tabindex="-1" aria-labelledby="LpgEmptyInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3">
                <div><h5 class="modal-title fw-bold mb-1" id="LpgEmptyInventoryModalLabel">Add Empty Stock</h5><small>Empty Cylinders / Canisters</small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="CashierReportLpgEmptyInventoryForm" novalidate>
                    @csrf
                    <input type="hidden" name="cashiers_report_lpg_empty_inventory_id" value="0">
                    <input type="hidden" name="cashiers_report_id" value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">
                    <div class="alert alert-danger d-none" id="lpg_empty_form_error" role="alert"></div>
                    <div class="mb-3">
                        <label for="lpg_empty_product_idx" class="form-label fw-semibold">Product / Item</label>
                        <select class="form-select" id="lpg_empty_product_idx" name="product_idx" required>
                            <option value="">Select product</option>
                            @foreach($product_data as $product)
                                @php
                                    $capacityLabel = $product->product_capacity && $product->product_capacity_unit
                                        ? rtrim(rtrim(number_format((float) $product->product_capacity, 3, '.', ''), '0'), '.') . ' ' . $product->product_capacity_unit
                                        : 'Capacity not set';
                                @endphp
                                <option value="{{ $product->product_id }}" data-capacity="{{ $capacityLabel }}">{{ $product->product_name }} — {{ $capacityLabel }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Select a product.</div>
                        <div class="form-text" id="lpg_empty_capacity_label">Grouping follows the capacity set on the product.</div>
                    </div>
                    @php
                        $emptyQuantityGroups = [
                            'Beginning Inventory' => ['beginning_quantity' => 'Beginning Quantity'],
                            'Add (+) — Empty From' => \App\Models\CashiersReportModel_LPG_EmptyInventory::ADDITIONS,
                            'Less (-)' => \App\Models\CashiersReportModel_LPG_EmptyInventory::DEDUCTIONS,
                        ];
                    @endphp
                    @foreach($emptyQuantityGroups as $heading => $fields)
                        <fieldset class="border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-2 fs-6 fw-semibold">{{ $heading }}</legend>
                            @foreach($fields as $field => $label)
                                <div class="mb-2">
                                    <label for="lpg_empty_{{ $field }}" class="form-label small fw-semibold">{{ $label }} (pcs)</label>
                                    <input type="number" class="form-control lpg-empty-quantity" id="lpg_empty_{{ $field }}" name="{{ $field }}" min="0" max="2147483647" step="1" value="0" required>
                                    <div class="invalid-feedback">Enter a whole number of zero or more.</div>
                                </div>
                            @endforeach
                        </fieldset>
                    @endforeach
                    <div class="border rounded-3 bg-light p-3 mb-3 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Computed Ending Inventory</span><output class="fw-bold fs-5" id="lpg_empty_ending_quantity">0</output>
                    </div>
                    <div class="mb-3">
                        <label for="lpg_empty_remarks" class="form-label fw-semibold">Remarks</label>
                        <textarea class="form-control" id="lpg_empty_remarks" name="remarks" rows="2" maxlength="5000" placeholder="Optional"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="CashierReportLpgEmptyInventoryForm" class="btn btn-success" id="save-lpg-empty-inventory"><i class="bi bi-save me-2" aria-hidden="true"></i>Save Empty Stock</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CashierReportLpgEmptyInventoryDeleteModal" tabindex="-1" aria-labelledby="LpgEmptyInventoryDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 rounded-4">
        <div class="modal-header"><h5 class="modal-title" id="LpgEmptyInventoryDeleteLabel">Delete Empty-stock Row</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body"><p>Delete the empty-stock totals for <strong id="lpg_empty_delete_item"></strong> from this report?</p><div class="alert alert-danger d-none" id="lpg_empty_delete_error" role="alert"></div></div>
        <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" id="confirm-delete-lpg-empty-inventory">Delete</button></div>
    </div></div>
</div>
