<div class="modal fade so-modal-theme" id="AddSOProductModal" tabindex="-1" aria-labelledby="add-so-product-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content so-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="so-modal-icon me-3"><i class="bi bi-box-seam fs-4" aria-hidden="true"></i></div>
                    <div>
                        <p class="so-modal-eyebrow">Sales order product</p>
                        <h2 class="modal-title" id="add-so-product-title">Add product</h2>
                        <small class="text-white-50">For <strong id="add_so_context_number">—</strong></small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <form id="AddSOProductForm" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div id="add-so-product-feedback" class="so-feedback mb-3" role="alert" hidden></div>
                    <input type="hidden" id="add_so_product_so_id" name="so_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="add_so_product_idx" class="form-label">Product <span class="text-danger" aria-hidden="true">*</span></label>
                            <select id="add_so_product_idx" name="product_idx" class="form-select" required>
                                <option value="">Choose a product</option>
                                @foreach ($product_data as $product)
                                    <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Choose a product.</div>
                        </div>
                        <div class="col-12">
                            <label for="add_so_product_price" class="form-label">Custom price (optional)</label>
                            <input type="number" id="add_so_product_price" name="product_manual_price" class="form-control" min="0" step="0.01" inputmode="decimal" placeholder="Use standard account price">
                            <div class="form-text">Leave blank to use the standard branch and account price.</div>
                        </div>
                        <div class="col-12">
                            <label for="add_so_product_quantity" class="form-label">Quantity <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="number" id="add_so_product_quantity" name="order_quantity" class="form-control" min="0.01" step="0.01" required inputmode="decimal">
                            <div class="invalid-feedback">Enter a quantity greater than zero.</div>
                        </div>
                        <div class="col-12"><div class="so-edit-amount">Estimated amount: <strong id="add_so_product_amount">—</strong></div></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" id="finish-so-product-add"><i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Finish and view order</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="save-so-product-add">
                        <span class="spinner-border spinner-border-sm me-1" id="so-product-add-spinner" role="status" aria-hidden="true" hidden></span>
                        <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Save product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
