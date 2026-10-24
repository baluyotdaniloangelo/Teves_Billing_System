<style>
div.dataTables_wrapper {
    overflow: visible !important;
}

.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important;
}

.product-information-theme .product-list-table thead th {
    color: var(--product-info-cream) !important;
    background-color: var(--product-info-teal) !important;
    border-bottom: 2px solid var(--product-info-sage) !important;
    font-weight: 600;
}

.product-information-theme .product-list-table thead th .bi {
    color: var(--product-info-cream) !important;
}

.product-information-theme .product-list-actions .btn {
    width: 2.1rem;
    height: 2.1rem;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--product-info-border) !important;
    border-radius: .65rem;
    background-color: var(--product-info-cream-soft);
    transition: background-color .15s ease, border-color .15s ease, color .15s ease, transform .15s ease;
}

.product-information-theme .product-list-actions .select-product .bi {
    color: var(--product-info-teal) !important;
}

.product-information-theme .product-list-actions .delete-product .bi {
    color: var(--product-info-burgundy) !important;
}

.product-information-theme .product-list-actions .select-product:hover {
    background-color: var(--product-info-teal);
    border-color: var(--product-info-teal) !important;
    transform: translateY(-1px);
}

.product-information-theme .product-list-actions .select-product:hover .bi,
.product-information-theme .product-list-actions .delete-product:hover .bi {
    color: #fff !important;
}

.product-information-theme .product-list-actions .delete-product:hover {
    background-color: var(--product-info-burgundy);
    border-color: var(--product-info-burgundy) !important;
    transform: translateY(-1px);
}

.product-information-theme .product-qr-thumbnail {
    width: 88px;
    height: 88px;
    padding: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--product-info-border);
    border-radius: .75rem;
    background: #fff;
    transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
}

.product-information-theme .product-qr-thumbnail:hover {
    border-color: var(--product-info-sage);
    box-shadow: 0 .2rem .55rem rgba(23, 62, 58, .16);
    transform: translateY(-1px);
}

.product-information-theme .product-qr-inline {
    display: block;
    overflow: hidden;
    width: 72px !important;
    height: 72px !important;
}

.product-information-theme .product-qr-inline canvas,
.product-information-theme .product-qr-inline img {
    width: 72px !important;
    height: 72px !important;
}

.product-information-theme .product-catalog-tabs {
    padding: .35rem;
    border: 1px solid var(--product-info-border);
    border-radius: .9rem;
    background: rgba(92, 131, 116, .08);
}

.product-information-theme .product-catalog-tabs .nav-link {
    color: var(--product-info-teal);
    border-radius: .7rem;
    font-weight: 600;
    padding: .7rem 1.1rem;
}

.product-information-theme .product-catalog-tabs .nav-link:hover:not(.active) {
    color: var(--product-info-burgundy);
    background: var(--product-info-cream-soft);
}

.product-information-theme .product-catalog-tabs .nav-link.active {
    color: var(--product-info-cream);
    background: var(--product-info-teal);
    box-shadow: 0 .2rem .6rem rgba(27, 66, 66, .18);
}

#getProductList_wrapper > .d-flex {
    width: 100%;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    gap: .75rem;
}

#getProductList_wrapper .dataTables_info,
#getProductList_wrapper .dataTables_paginate {
    margin: 0;
}

@media (max-width: 768px) {
    #getProductList_wrapper > .d-flex {
        flex-direction: column;
        align-items: center !important;
    }

    #getProductList_wrapper .dataTables_info {
        text-align: center;
    }
}
</style>

<div class="card border-0 shadow-sm rounded-4 p-2 mb-3">
    <ul class="nav nav-pills product-catalog-tabs" id="productCatalogTabs" role="tablist" aria-label="Product catalog views">
        <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link active w-100" id="main-products-tab" data-bs-toggle="tab" data-bs-target="#mainProductsPane" type="button" role="tab" aria-controls="mainProductsPane" aria-selected="true">
                <i class="bi bi-box-seam me-2" aria-hidden="true"></i>Main
            </button>
        </li>
        <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="product-variants-tab" data-bs-toggle="tab" data-bs-target="#productVariantsPane" type="button" role="tab" aria-controls="productVariantsPane" aria-selected="false">
                <i class="bi bi-boxes me-2" aria-hidden="true"></i>Product Variants
            </button>
        </li>
        @if(($data->user_type ?? null) === 'SUAdmin')
        <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="product-categories-tab" data-bs-toggle="tab" data-bs-target="#productCategoriesPane" type="button" role="tab" aria-controls="productCategoriesPane" aria-selected="false">
                <i class="bi bi-tags me-2" aria-hidden="true"></i>Product Categories
            </button>
        </li>
        @endif
    </ul>
</div>

<div class="tab-content" id="productCatalogTabContent">
<div class="tab-pane fade show active" id="mainProductsPane" role="tabpanel" aria-labelledby="main-products-tab" tabindex="0">
<section class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width:55px;height:55px;">
                    <i class="bi bi-box-seam text-success fs-4" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Products</h4>
                    <small class="text-muted">Manage product information, pricing, and equipment.</small>
                </div>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-outline-primary rounded-3 shadow-sm px-3 d-inline-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#MainProductModal">
                <i class="bi bi-folder-plus me-2" aria-hidden="true"></i>
                Add Main Product
            </button>
            </div>
        </div>
    </div>

    <div class="card-body p-3 p-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="product_list_search" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-search text-primary me-1" aria-hidden="true"></i>
                            Search Main Products
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted" aria-hidden="true"></i>
                            </span>
                            <input type="search" id="product_list_search" class="form-control"
                                   placeholder="Search main product or category..." autocomplete="off">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="product_list_category" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-tag text-success me-1" aria-hidden="true"></i>
                            Category
                        </label>
                        <select id="product_list_category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach ($product_category as $categoryOption)
                                <option value="{{ $categoryOption->category_name }}">{{ $categoryOption->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="product_list_length" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-list-ol text-secondary me-1" aria-hidden="true"></i>
                            Rows to Display
                        </label>
                        <select id="product_list_length" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 d-flex justify-content-md-end">
                        <button type="button" id="resetProductListFilters" class="btn btn-light btn-sm rounded-3 px-3">
                            <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle dataTable display nowrap w-100 product-list-table" id="getProductList" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center"><i class="bi bi-hash text-muted me-1" aria-hidden="true"></i>#</th>
                        <th><i class="bi bi-box-seam text-muted me-1" aria-hidden="true"></i>Main Product</th>
                        <th><i class="bi bi-tag text-muted me-1" aria-hidden="true"></i>Category</th>
                        <th class="text-center"><i class="bi bi-boxes text-muted me-1" aria-hidden="true"></i>Variants</th>
                        <th class="text-center text-nowrap"><i class="bi bi-three-dots text-muted me-1" aria-hidden="true"></i>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
</div>

<div class="tab-pane fade" id="productVariantsPane" role="tabpanel" aria-labelledby="product-variants-tab" tabindex="0">
<section class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
    <div class="card-header bg-white border-0 py-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width:55px;height:55px;">
                    <i class="bi bi-link-45deg text-success fs-4" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Product Variants</h4>
                    <small class="text-muted">Browse all product variants and see which main product each one belongs to.</small>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-outline-primary rounded-3 shadow-sm px-3 d-inline-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#CreateProductModal">
                    <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                    Add Product Variant
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-3 p-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="unassigned_variant_search" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-search text-primary me-1" aria-hidden="true"></i>
                            Search Product Variants
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted" aria-hidden="true"></i>
                            </span>
                            <input type="search" id="unassigned_variant_search" class="form-control"
                                   placeholder="Search variant, code, main product, or category..." autocomplete="off">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="unassigned_variant_category" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-tag text-success me-1" aria-hidden="true"></i>
                            Category
                        </label>
                        <select id="unassigned_variant_category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach ($product_category as $categoryOption)
                                <option value="{{ $categoryOption->category_name }}">{{ $categoryOption->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="unassigned_variant_length" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-list-ol text-secondary me-1" aria-hidden="true"></i>
                            Rows to Display
                        </label>
                        <select id="unassigned_variant_length" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 d-flex justify-content-md-end">
                        <button type="button" id="resetUnassignedVariantFilters" class="btn btn-light btn-sm rounded-3 px-3">
                            <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle dataTable display nowrap w-100 product-list-table" id="getUnassignedProductList" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center"><i class="bi bi-hash me-1" aria-hidden="true"></i>#</th>
                        <th class="text-center"><i class="bi bi-upc-scan me-1" aria-hidden="true"></i>Product Code</th>
                        <th><i class="bi bi-box me-1" aria-hidden="true"></i>Product Variant</th>
                        <th><i class="bi bi-boxes me-1" aria-hidden="true"></i>Main Product</th>
                        <th><i class="bi bi-tag me-1" aria-hidden="true"></i>Category</th>
                        <th class="text-end"><i class="bi bi-currency-exchange me-1" aria-hidden="true"></i>Default Price</th>
                        <th class="text-center"><i class="bi bi-rulers me-1" aria-hidden="true"></i>Unit</th>
                        <th class="text-center"><i class="bi bi-rulers me-1" aria-hidden="true"></i>Capacity</th>
                        <th class="text-center"><i class="bi bi-qr-code me-1" aria-hidden="true"></i>QR Code</th>
                        <th class="text-center text-nowrap"><i class="bi bi-three-dots me-1" aria-hidden="true"></i>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
</div>
@if(($data->user_type ?? null) === 'SUAdmin')
<div class="tab-pane fade" id="productCategoriesPane" role="tabpanel" aria-labelledby="product-categories-tab" tabindex="0">
    @include('pages.product_category.tabs.product_category_management')
</div>
@endif
</div>
