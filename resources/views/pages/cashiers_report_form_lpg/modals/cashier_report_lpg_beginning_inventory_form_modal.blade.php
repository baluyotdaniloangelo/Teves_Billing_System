<div class="modal fade" id="CashierReportLpgInventoryModal" tabindex="-1" aria-labelledby="CashierReportLpgInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                        <i class="bi bi-box-seam fs-4" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="CashierReportLpgInventoryModalLabel">Beginning Inventory</h5>
                        <small class="text-white-50" id="CashierReportLpgInventoryModalDescription">Record the actual LPG stock available at the start of the report.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body p-4">
                <form id="CashierReportLpgInventoryForm" novalidate>
                    @csrf
                    <input type="hidden" id="cashiers_report_lpg_inventory_id" name="cashiers_report_lpg_inventory_id" value="0">
                    <input type="hidden" id="lpg_inventory_report_id" name="cashiers_report_id" value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">
                    <input type="hidden" id="lpg_inventory_mode" name="inventory_mode" value="BEGINNING">
                    <input type="hidden" id="lpg_inventory_audit_date" name="audit_date" value="{{ optional($CashiersReportData->first())->report_date ? \Illuminate\Support\Carbon::parse($CashiersReportData->first()->report_date)->format('Y-m-d') : '' }}">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="lpg_inventory_product_idx" class="form-label fw-semibold"><i class="bi bi-box-seam me-2 text-success" aria-hidden="true"></i>Product / Item</label>
                    @php
                        $lpgInventoryKitchenProductIds = \App\Services\LpgKitchenProducts::ids();
                        $lpgInventoryRegulatorProductIds = \App\Services\LpgRegulatorProducts::ids();
                    @endphp
                            <select class="form-select form-select-sm rounded-3 lpg-product-picker" id="lpg_inventory_product_idx" name="product_idx" required>
                                <option value="">Select product</option>
                                @foreach ($product_data->filter(function ($product) use ($lpgInventoryKitchenProductIds, $lpgInventoryRegulatorProductIds) { return in_array((int) $product->category_idx, \App\Services\LpgEmptyInventorySummary::categoryIds(), true) || in_array((int) $product->product_id, $lpgInventoryKitchenProductIds, true) || in_array((int) $product->product_id, $lpgInventoryRegulatorProductIds, true); })->unique('product_id') as $product)
                                    @php
                                        $capacityLabel = ($product->product_capacity ?? null) !== null && $product->product_capacity !== ''
                                            ? rtrim(rtrim(number_format((float) $product->product_capacity, 2, '.', ''), '0'), '.') . strtoupper($product->product_capacity_unit ?? '')
                                            : ($product->product_unit_measurement ?? '');
                                    @endphp
                                    <option value="{{ $product->product_id }}" data-product-code="{{ $product->product_code ?? '' }}" data-price="{{ $product->product_price ?? 0 }}" data-lpg="{{ in_array((int) $product->category_idx, \App\Services\LpgEmptyInventorySummary::categoryIds(), true) ? '1' : '0' }}" data-kitchen="{{ in_array((int) $product->product_id, $lpgInventoryKitchenProductIds, true) ? '1' : '0' }}" data-regulator="{{ in_array((int) $product->product_id, $lpgInventoryRegulatorProductIds, true) ? '1' : '0' }}">{{ trim(($product->product_name ?? '') . ' ' . $capacityLabel) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="product_idxError">Please select a product in this inventory category.</div>
                        </div>

                        <div class="col-12 lpg-supplier-field d-none">
                            <label for="lpg_inventory_supplier_idx" class="form-label fw-semibold"><i class="bi bi-truck me-2 text-success" aria-hidden="true"></i>Supplier</label>
                            <select class="form-select form-select-sm rounded-3" id="lpg_inventory_supplier_idx" name="supplier_idx">
                                <option value="">Select supplier</option>
                                @foreach (($supplier_data ?? []) as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="supplier_idxError">Please select a supplier.</div>
                        </div>

                        <div class="col-12 lpg-client-field d-none">
                            <label for="lpg_inventory_client_idx" class="form-label fw-semibold"><i class="bi bi-person me-2 text-success" aria-hidden="true"></i>Customer / Client</label>
                            <select class="form-select form-select-sm rounded-3" id="lpg_inventory_client_idx" name="client_idx">
                                <option value="">Select customer</option>
                                @foreach (($client_data ?? []) as $client)
                                    <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="client_idxError">Please select a customer.</div>
                        </div>

                        <div class="col-md-12 lpg-beginning-audit-field">
                            <label for="lpg_inventory_quantity" class="form-label fw-semibold"><i class="bi bi-123 me-2 text-success" aria-hidden="true"></i>Filled Cylinders</label>
                            <input type="number" min="0" step="0.01" class="form-control form-control-sm rounded-3" id="lpg_inventory_quantity" name="quantity" placeholder="Enter quantity" required>
                            <div class="invalid-feedback" id="quantityError">Please enter the filled cylinder count.</div>
                        </div>

                        <div class="col-md-6 lpg-beginning-audit-field">
                            <label for="lpg_inventory_bo_returned_customer_quantity" class="form-label fw-semibold">B.O. Returned from Customers</label>
                            <input type="number" min="0" step="0.01" class="form-control form-control-sm rounded-3" id="lpg_inventory_bo_returned_customer_quantity" name="bo_returned_customer_quantity" placeholder="Enter count" required>
                        </div>

                        <div class="col-md-6 lpg-beginning-audit-field">
                            <label for="lpg_inventory_bo_during_delivery_quantity" class="form-label fw-semibold">B.O. During Delivery</label>
                            <input type="number" min="0" step="0.01" class="form-control form-control-sm rounded-3" id="lpg_inventory_bo_during_delivery_quantity" name="bo_during_delivery_quantity" placeholder="Enter count" required>
                        </div>

                        <div class="col-12 lpg-beginning-audit-field">
                            <label for="lpg_inventory_audit_date_display" class="form-label fw-semibold">Starting Audit Date</label>
                            <input type="date" class="form-control form-control-sm rounded-3" id="lpg_inventory_audit_date_display" value="{{ optional($CashiersReportData->first())->report_date ? \Illuminate\Support\Carbon::parse($CashiersReportData->first()->report_date)->format('Y-m-d') : '' }}" disabled>
                            <small class="form-text">This audit becomes the Beginning Inventory from this date forward.</small>
                        </div>

                        <div class="col-md-12 lpg-reference-field d-none">
                            <label for="lpg_inventory_delivery_reference_no" class="form-label fw-semibold"><i class="bi bi-file-earmark-text me-2 text-success" aria-hidden="true"></i>DR / Reference No.</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="lpg_inventory_delivery_reference_no" name="delivery_reference_no" maxlength="100" placeholder="Optional">
                        </div>

                        <div class="col-12">
                            <label for="lpg_inventory_remarks" class="form-label fw-semibold"><i class="bi bi-chat-left-text me-2 text-success" aria-hidden="true"></i>Remarks</label>
                            <textarea class="form-control rounded-3" id="lpg_inventory_remarks" name="remarks" rows="3" placeholder="Optional remarks"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light border rounded-3 px-4" id="clear-lpg-inventory"><i class="bi bi-arrow-counterclockwise me-2" aria-hidden="true"></i>Reset</button>
                <button type="submit" form="CashierReportLpgInventoryForm" class="btn btn-success rounded-3 px-4 shadow-sm" id="save-lpg-inventory"><i class="bi bi-save-fill me-2" aria-hidden="true"></i>Save Inventory</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="CashierReportLpgInventoryRevokeModal" tabindex="-1" aria-labelledby="CashierReportLpgInventoryRevokeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3">
                <h5 class="modal-title fw-bold" id="CashierReportLpgInventoryRevokeModalLabel"><i class="bi bi-arrow-counterclockwise me-2" aria-hidden="true"></i>Request to Revoke Inventory Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <input type="hidden" id="lpg_inventory_revoke_id" value="0">
                <div class="alert alert-light border"><div class="fw-semibold" id="lpg_inventory_revoke_number"></div><div id="lpg_inventory_revoke_item"></div></div>
                <label for="lpg_inventory_revoke_reason" class="form-label">Reason for request <span class="text-danger">*</span></label>
                <textarea id="lpg_inventory_revoke_reason" class="form-control form-select-sm" rows="3" maxlength="2000" placeholder="Explain why this transaction should be voided."></textarea>
                <div class="form-text">The transaction stays active until an approver reviews and approves this request.</div>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light border rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-3" id="submit-lpg-inventory-revoke"><i class="bi bi-send me-2" aria-hidden="true"></i>Request Revoke</button>
            </div>
        </div>
    </div>
</div>
