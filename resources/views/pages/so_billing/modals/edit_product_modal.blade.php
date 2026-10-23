<div class="modal fade so-modal-theme" id="EditSOProductModal" tabindex="-1" aria-labelledby="edit-so-product-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content so-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="so-modal-icon me-3"><i class="bi bi-pencil-square fs-4" aria-hidden="true"></i></div>
                    <div>
                        <p class="so-modal-eyebrow">Sales order product</p>
                        <h2 class="modal-title" id="edit-so-product-title">Edit product</h2>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <form id="EditSOProductForm" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div id="edit-so-product-feedback" class="so-feedback mb-3" role="alert" hidden></div>
                    <input type="hidden" id="edit_so_id" name="so_id">
                    <input type="hidden" id="edit_billing_id" name="billing_id">
                    <input type="hidden" id="edit_branch_idx" name="branch_idx">
                    <input type="hidden" id="edit_client_idx" name="client_idx">
                    <div class="so-edit-context mb-3"><i class="bi bi-receipt me-2" aria-hidden="true"></i>Editing a product for <strong id="edit_so_context_number">—</strong></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="edit_so_product_idx" class="form-label">Product <span class="text-danger" aria-hidden="true">*</span></label>
                            <select id="edit_so_product_idx" name="product_idx" class="form-select" required>
                                @foreach ($product_data as $product)
                                    <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Choose a product.</div>
                        </div>
                        <div class="col-12">
                            <label for="edit_so_product_price" class="form-label">Custom price (optional)</label>
                            <input type="number" id="edit_so_product_price" class="form-control" min="0" step="0.01" inputmode="decimal" placeholder="Use standard account price">
                            <div class="form-text">Leave blank to use the standard branch and account price.</div>
                        </div>
                        <div class="col-12">
                            <label for="edit_so_product_quantity" class="form-label">Quantity <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="number" id="edit_so_product_quantity" name="order_quantity" class="form-control" min="0.01" step="0.01" required inputmode="decimal">
                            <div class="invalid-feedback">Enter a quantity greater than zero.</div>
                        </div>
                        <div class="col-12" id="edit-so-cashiers-sync-wrap" hidden>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_so_update_cashiers_report">
                                <label class="form-check-label" for="edit_so_update_cashiers_report">Update this item in Cashiers Report too</label>
                            </div>
                            <div class="form-text">When selected, the report product, price, quantity, and amount will match this SO product.</div>
                        </div>
                        <div class="col-12"><div class="so-edit-amount">Estimated amount: <strong id="edit_so_product_amount">—</strong></div></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" id="cancel-so-product-edit"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Back to products</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="save-so-product-edit">
                        <span class="spinner-border spinner-border-sm me-1" id="so-product-edit-spinner" role="status" aria-hidden="true" hidden></span>
                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Save product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
