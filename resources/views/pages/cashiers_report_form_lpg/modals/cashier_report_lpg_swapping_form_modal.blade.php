<div class="modal fade" id="CashierReportLpgSwappingModal" tabindex="-1" aria-labelledby="CashierReportLpgSwappingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 py-3">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-1" id="CashierReportLpgSwappingModalLabel">Add Received</h5>
                    <small class="text-white-50">Record received and replaced items as separate entries.</small>
                </div>
                <button type="button" class="btn btn-danger btn-sm rounded-circle" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body p-4">
                <form id="CashierReportLpgSwappingForm" novalidate>
                    @csrf
                    <input type="hidden" name="cashiers_report_lpg_swapping_id" id="cashiers_report_lpg_swapping_id" value="0">
                    <input type="hidden" name="cashiers_report_id" value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">
                    <div class="alert alert-danger d-none lpg-container-form-error" role="alert"></div>
                    <div class="border rounded-3 bg-light p-3 mb-3 d-none" id="lpg-swapping-return-info">
                        <div class="small mb-2" id="lpg-swapping-return-message" role="status"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-swapping-returns"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh Returns</button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="lpg_swapping_entry_mode">Entry Type</label>
                        <select class="form-select form-select-sm" id="lpg_swapping_entry_mode">
                            <option value="RECEIVED">Received from Customer</option>
                            <option value="REPLACED">Replaced / Issued to Customer</option>
                            <option value="BOTH" hidden>Received and Replaced</option>
                        </select>
                        <div class="form-text d-none" id="lpg-swapping-combined-note">This entry contains both received and replaced items. Both are shown when editing.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="lpg_swapping_client_idx">Customer <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" name="client_idx" id="lpg_swapping_client_idx" required>
                            <option value="">Select customer</option>
                            @foreach($client_data as $client)
                                <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="client_idxError"></div>
                    </div>
                    @foreach(['received' => 'Cylinder / Regulator Received', 'replaced' => 'Cylinder / Regulator Replaced / Issued'] as $swapSide => $swapSideLabel)
                        <div class="border rounded-3 p-3 mb-3 lpg-swap-side" data-swap-side="{{ $swapSide }}">
                            <h6 class="fw-semibold mb-3">{{ $swapSideLabel }}</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label" for="{{ $swapSide }}_product_idx">Item <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm lpg-product-picker" name="{{ $swapSide }}_product_idx" id="{{ $swapSide }}_product_idx" required>
                                        <option value="">Select item</option>
                                        @foreach($product_data as $product)
                                            <option value="{{ $product->product_id }}" data-product-code="{{ $product->product_code ?? '' }}" data-price="{{ $product->product_price ?? 0 }}">{{ trim(($product->product_name ?? '') . ' ' . ($product->product_unit_measurement ?? '')) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="{{ $swapSide }}_product_idxError"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="{{ $swapSide }}_quantity">Quantity <span class="text-danger">*</span></label>
                                    <input class="form-control form-control-sm" type="number" min="0.01" step="0.01" name="{{ $swapSide }}_quantity" id="{{ $swapSide }}_quantity" required>
                                    <div class="invalid-feedback" id="{{ $swapSide }}_quantityError"></div>
                                </div>
                                @include('pages.cashiers_report_form_lpg.partials.container_stock_state_fields', ['stockStateFields' => [$swapSide => $swapSide === 'received' ? 'Received Item Contents' : 'Issued / Replaced Item Contents']])
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <label class="form-label fw-semibold" for="lpg_swapping_remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="lpg_swapping_remarks" rows="2" placeholder="Optional"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="CashierReportLpgSwappingForm" class="btn btn-success" id="save-lpg-swapping"><i class="bi bi-save-fill me-2"></i>Save Entry</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CashierReportLpgSwappingDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold">Delete Swapping Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body"><p class="mb-0" id="lpg_swapping_delete_message">Delete this swapping record?</p><div class="alert alert-danger mt-3 mb-0 d-none" id="lpg_swapping_delete_error" role="alert"></div><input type="hidden" id="lpg_swapping_delete_id"></div>
            <div class="modal-footer border-0 bg-light"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" id="confirm-delete-lpg-swapping">Delete</button></div>
        </div>
    </div>
</div>
