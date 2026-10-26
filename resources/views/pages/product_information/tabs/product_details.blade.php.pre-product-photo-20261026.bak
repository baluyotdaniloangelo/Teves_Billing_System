<section class="rounded-4 border bg-white p-3">
    <div class="d-flex align-items-center mb-3">
        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success me-2" style="width:38px;height:38px;">
            <i class="bi bi-box-seam" aria-hidden="true"></i>
        </span>
        <div>
            <h6 class="fw-bold mb-0" id="productInformationHeading">Select a product to manage its details.</h6>
            <small class="text-muted">Update product information or manage its pricing and equipment.</small>
        </div>
    </div>

<ul class="nav product-information-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($tab === 'product-info') active @endif" id="product-info-tab" data-bs-toggle="tab" data-bs-target="#product-info" type="button" role="tab" aria-controls="product-info" aria-selected="{{ $tab === 'product-info' ? 'true' : 'false' }}">
            <i class="bi bi-card-text" aria-hidden="true"></i><span>Product Information</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($tab === 'branchprice') active @endif" id="branchprice-tab" data-bs-toggle="tab" data-bs-target="#branchprice" type="button" role="tab" aria-controls="branchprice" aria-selected="{{ $tab === 'branchprice' ? 'true' : 'false' }}" title="Product Prices per branch">
            <i class="bi bi-building" aria-hidden="true"></i><span>Price per Branch</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($tab === 'seller') active @endif" id="seller-tab" data-bs-toggle="tab" data-bs-target="#seller" type="button" role="tab" aria-controls="seller" aria-selected="{{ $tab === 'seller' ? 'true' : 'false' }}" title="Product Prices per Seller">
            <i class="bi bi-truck" aria-hidden="true"></i><span>Seller Price</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($tab === 'selling_price') active @endif" id="selling-price-tab" data-bs-toggle="tab" data-bs-target="#selling" type="button" role="tab" aria-controls="selling" aria-selected="{{ $tab === 'selling_price' ? 'true' : 'false' }}" title="Product Prices per Client">
            <i class="bi bi-tags" aria-hidden="true"></i><span>Selling Price</span>
        </button>
    </li>
    <li class="nav-item" id="tank-tab-item" role="presentation" hidden>
        <button class="nav-link @if($tab === 'tank') active @endif" id="tank-tab" data-bs-toggle="tab" data-bs-target="#tank" type="button" role="tab" aria-controls="tank" aria-selected="{{ $tab === 'tank' ? 'true' : 'false' }}" title="Tank Information">
            <i class="bi bi-database" aria-hidden="true"></i><span>Tank</span>
        </button>
    </li>
    <li class="nav-item" id="pump-tab-item" role="presentation" hidden>
        <button class="nav-link @if($tab === 'pump') active @endif" id="pump-tab" data-bs-toggle="tab" data-bs-target="#pump" type="button" role="tab" aria-controls="pump" aria-selected="{{ $tab === 'pump' ? 'true' : 'false' }}" title="Pump Information">
            <i class="bi bi-fuel-pump" aria-hidden="true"></i><span>Pump</span>
        </button>
    </li>
</ul>

<div class="tab-content pt-2" id="myTabContent">
    <div class="tab-pane fade @if($tab === 'product-info') show active @endif" id="product-info" role="tabpanel" aria-labelledby="product-info-tab" tabindex="0">
        <form class="needs-validation" id="ProductformEdit" novalidate>
            <div class="product-update-success-notice d-none mb-3" id="productUpdateSuccessNotice" role="status" aria-live="polite">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success me-2" aria-hidden="true"></i>
                    <span id="productUpdateSuccessMessage">Product information updated successfully.</span>
                </div>
                <button type="button" class="btn-close" id="dismissProductUpdateSuccessNotice" aria-label="Dismiss success message"></button>
            </div>

            <div class="row g-2 mb-2 justify-content-center">
                <div class="col-12 col-md-4">
                    <label for="update_category_idx" class="form-label small fw-semibold mb-1">
                        <i class="bi bi-tag me-1 text-success" aria-hidden="true"></i>Product Category
                    </label>
                    <select class="form-select form-select-sm rounded-3" required name="update_category_idx" id="update_category_idx">
                        @foreach ($product_category as $product_category_cols)
                            <option value="{{ $product_category_cols->category_id }}">{{ $product_category_cols->category_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="update_category_idxError">Please select a product category.</div>
                </div>
            </div>
            <div class="row g-2 mb-2 justify-content-center">
                <div class="col-12 col-md-4">
                    <label for="update_product_main_idx" class="form-label small fw-semibold mb-1">
                        <i class="bi bi-boxes me-1 text-success" aria-hidden="true"></i>Main Product (Variant Group)
                    </label>
                    <select class="form-select form-select-sm rounded-3" name="product_main_idx" id="update_product_main_idx">
                        <option value="">No main product (unassigned)</option>
                    </select>
                    <div class="form-text">Choose a main product in the same category, or leave this unassigned.</div>
                    <div class="invalid-feedback" id="update_product_main_idxError">Choose a main product in the selected category.</div>
                </div>
            </div>
            <div class="row g-2 mb-2 justify-content-center">
                <div class="col-12 col-md-4">
                    <label for="update_product_name" class="form-label small fw-semibold mb-1">
                        <i class="bi bi-box me-1 text-success" aria-hidden="true"></i>Product Name
                    </label>
                    <input type="text" class="form-control form-control-sm rounded-3" name="update_product_name" id="update_product_name" required autocomplete="off">
                    <div class="invalid-feedback" id="update_product_nameError">Product name is required.</div>
                </div>
            </div>

            <div class="row g-2 mb-2 justify-content-center">
                <div class="col-12 col-md-4">
                    <label for="update_product_price" class="form-label small fw-semibold mb-1">
                        <i class="bi bi-currency-exchange me-1 text-success" aria-hidden="true"></i>Default Price
                    </label>
                    <input type="number" class="form-control form-control-sm rounded-3" name="update_product_price" id="update_product_price" required step="0.01" min="0">
                    <div class="invalid-feedback" id="update_product_priceError">Please enter a valid price.</div>
                </div>
            </div>
            <div class="row g-2 mb-2 justify-content-center">
                <div class="col-12 col-md-4">
                    <label for="update_product_unit_measurement" class="form-label small fw-semibold mb-1">
                        <i class="bi bi-rulers me-1 text-success" aria-hidden="true"></i>Unit of Measurement
                    </label>
                    <select class="form-select form-select-sm rounded-3" name="update_product_unit_measurement" id="update_product_unit_measurement" required>
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
                    <div class="invalid-feedback" id="update_product_unit_measurementError">Please select a unit.</div>
                </div>
            </div>

            @include('pages.product_information.partials.capacity_fields', ['capacityPrefix' => 'update_', 'capacityColumnClass' => 'col-12 col-md-4'])

            <div class="row justify-content-center mt-2">
                <div class="col-12 col-md-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="update-product">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade @if($tab === 'branchprice') show active @endif" id="branchprice" role="tabpanel" aria-labelledby="branchprice-tab" tabindex="0">
        <table class="table dataTable display nowrap cell-border" id="product_price_branch" width="100%" cellspacing="0">
            <thead>
                <tr class="report">
                    <th class="all">Item #</th>
                    <th class="all">Branch</th>
                    <th class="none">Buying Price:</th>
                    <th class="none">Profit Margin:</th>
                    <th class="none">Type:</th>
                    <th class="none">In Peso:</th>
                    <th class="all">Selling Price</th>
                    <th class="all">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="tab-pane fade @if($tab === 'seller') show active @endif" id="seller" role="tabpanel" aria-labelledby="seller-tab" tabindex="0">
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success rounded-3 shadow-sm px-3 d-inline-flex align-items-center" data-product-modal-target="#ProductPricePerSellerModal" id="IDProductPricePerSellerModal">
                <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Add Seller Price
            </button>
        </div>
        <table class="table dataTable display nowrap cell-border" id="ProductPricePerSellerListTable" width="100%" cellspacing="0">
            <thead>
                <tr class="report">
                    <th class="all">Item #</th>
                    <th class="all">Branch</th>
                    <th class="all">Supplier</th>
                    <th class="all">Seller Price</th>
                    <th class="all">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="tab-pane fade @if($tab === 'selling_price') show active @endif" id="selling" role="tabpanel" aria-labelledby="selling-price-tab" tabindex="0">
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success rounded-3 shadow-sm px-3 d-inline-flex align-items-center" data-product-modal-target="#ProductPricePerClientModal" id="IDProductPricePerClientModal">
                <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Add Selling Price
            </button>
        </div>
        <table class="table dataTable display nowrap cell-border" id="ProductPricePerClientListTable" width="100%" cellspacing="0">
            <thead>
                <tr class="report">
                    <th class="all">Item #</th>
                    <th class="all">Branch</th>
                    <th class="all">Client</th>
                    <th class="all">Selling Price</th>
                    <th class="all">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="tab-pane fade @if($tab === 'tank') show active @endif" id="tank" role="tabpanel" aria-labelledby="tank-tab" tabindex="0">
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success rounded-3 shadow-sm px-3 d-inline-flex align-items-center" data-product-modal-target="#AddProductTankModal" id="AddProductTankBTN">
                <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Add Tank
            </button>
        </div>
        <table class="table dataTable display nowrap cell-border" id="ProductTankListTable" width="100%">
            <thead>
                <tr class="report">
                    <th>Item #</th>
                    <th>Branch</th>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="tab-pane fade @if($tab === 'pump') show active @endif" id="pump" role="tabpanel" aria-labelledby="pump-tab" tabindex="0">
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success rounded-3 shadow-sm px-3 d-inline-flex align-items-center" data-product-modal-target="#AddProductPumpModal" id="AddProductPumpBTN">
                <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Add Pump
            </button>
        </div>
        <table class="table dataTable display nowrap cell-border" id="ProductPumpListTable" width="100%">
            <thead>
                <tr class="report">
                    <th>Item #</th>
                    <th>Branch</th>
                    <th>Name</th>
                    <th>Initial Reading</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</section>
