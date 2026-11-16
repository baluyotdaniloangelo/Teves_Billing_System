<style>
#CreateProductModal .create-product-modal-content {
    border: 0;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, .2);
}

#CreateProductModal .create-product-modal-header {
    color: #fff;
    border: 0;
    padding: .85rem 1.25rem;
    background: linear-gradient(115deg, var(--product-info-burgundy), var(--product-info-teal) 72%, var(--product-info-sage));
}

#CreateProductModal .product-capacity-field {
    flex: 0 0 100%;
    width: 100%;
    max-width: 100%;
}

#CreateProductModal .product-capacity-field .form-control,
#CreateProductModal .product-capacity-field .form-select {
    width: 100%;
    max-width: 100%;
}

.product-photo-form-preview,
.product-photo-form-placeholder {
    width: 100%;
    height: 15rem;
    border: 1px solid var(--product-info-border);
    border-radius: .75rem;
    object-fit: contain;
    background: #f8f9f7;
}

.product-photo-form-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--product-info-sage);
    background: var(--product-info-cream-soft);
    font-size: 3rem;
}

.product-photo-panel {
    height: 100%;
    padding: 1rem;
    border: 1px solid var(--product-info-border);
    border-radius: 1rem;
    background: #fff;
}

@media (max-width: 575.98px) {
    #CreateProductModal .create-product-modal-header {
        padding: .75rem;
    }
}
</style>

<div class="modal fade product-information-modal-theme" id="CreateProductModal" tabindex="-1" aria-labelledby="CreateProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content create-product-modal-content">
            <div class="modal-header create-product-modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                        <i class="bi bi-box-seam-fill fs-4" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="CreateProductModalLabel">Create Product</h5>
                        <small class="text-white-50">Add a product to your catalog.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>

            <form class="needs-validation" id="ProductformNew" enctype="multipart/form-data" novalidate>
                <div class="modal-body p-3">
                    <div class="row g-3 align-items-stretch">
                        <div class="col-12 col-lg-7">
                            <section class="rounded-4 border bg-white p-3 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success me-2" style="width:38px;height:38px;">
                                        <i class="bi bi-card-text" aria-hidden="true"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-bold mb-0">Product Information</h6>
                                        <small class="text-muted">Enter the product and variant details.</small>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <label for="product_main_idx" class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-boxes me-1 text-success" aria-hidden="true"></i>Main Product
                                        </label>
                                        <select class="form-select form-select-sm rounded-3" name="product_main_idx" id="product_main_idx">
                                            <option value="">Unassigned variant</option>
                                        </select>
                                        <div class="form-text">Choose a main product to group this variant. Leave blank to assign it later.</div>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <label for="category_idx" class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-tag me-1 text-success" aria-hidden="true"></i>Product Category
                                        </label>
                                        <select class="form-select form-select-sm rounded-3" required name="category_idx" id="category_idx">
                                            <option value="">Select a category</option>
                                            @foreach ($product_category as $product_category_cols)
                                                <option value="{{ $product_category_cols->category_id }}">{{ $product_category_cols->category_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="category_idxError">Please select a product category.</div>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <label for="product_name" class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-box me-1 text-success" aria-hidden="true"></i>Product Name
                                        </label>
                                        <input type="text" class="form-control form-control-sm rounded-3" name="product_name" id="product_name" required autocomplete="off">
                                        <div class="invalid-feedback" id="product_nameError">Product name is required.</div>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <label for="product_price" class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-currency-exchange me-1 text-success" aria-hidden="true"></i>Default Price
                                        </label>
                                        <input type="number" class="form-control form-control-sm rounded-3" name="product_price" id="product_price" required min="0" step="0.01">
                                        <div class="invalid-feedback" id="product_priceError">Please enter a valid price.</div>
                                    </div>
                                </div>

                                @include('pages.product_information.partials.capacity_fields', ['capacityPrefix' => '', 'capacityColumnClass' => 'col-12'])

                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="product_unit_measurement" class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-rulers me-1 text-success" aria-hidden="true"></i>Unit of Measurement
                                        </label>
                                        <select class="form-select form-select-sm rounded-3" name="product_unit_measurement" id="product_unit_measurement" required>
                                            <option value="L">Liter | L</option>
                                            <option value="mL">Milliliter | mL</option>
                                            <option value="g">Gram | g</option>
                                            <option value="kg">Kilo | kg</option>
                                            <option value="PC">Pieces | PC</option>
                                            <option value="oz">Ounce | oz</option>
                                            <option value="Pail">Pail</option>
                                            <option value="Drum">Drum</option>
                                            <option value="Case">Case</option>
                                        </select>
                                        <div class="invalid-feedback" id="product_unit_measurementError"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-12 col-lg-5">
                            <section class="product-photo-panel">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success me-2" style="width:38px;height:38px;">
                                        <i class="bi bi-image" aria-hidden="true"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-bold mb-0">Product Photo</h6>
                                        <small class="text-muted">Upload or preview the product image.</small>
                                    </div>
                                </div>
                                <img id="product_photo_preview" class="product-photo-form-preview d-none mb-3" alt="Product photo preview">
                                <span id="product_photo_placeholder" class="product-photo-form-placeholder mb-3" aria-hidden="true"><i class="bi bi-image"></i></span>
                                <label for="product_photo" class="form-label small fw-semibold mb-1">
                                    Upload Product Photo <span class="text-muted fw-normal">(Optional)</span>
                                </label>
                                <input type="file" class="form-control form-control-sm rounded-3" name="product_photo" id="product_photo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                <div class="form-text">JPG, PNG, or WEBP up to 5 MB.</div>
                                <div class="invalid-feedback" id="product_photoError">Choose a JPG, PNG, or WEBP image under 5 MB.</div>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel
                    </button>
                    <button type="reset" class="btn btn-light border btn-sm rounded-3 px-3" id="clear-product">
                        <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="save-product">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
