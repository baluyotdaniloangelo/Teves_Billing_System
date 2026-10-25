<style>
.product-information-theme .product-category-management-table {
    margin-bottom: 0 !important;
    border-collapse: separate;
    border-spacing: 0;
    border: 0 !important;
}

.product-information-theme .product-category-table-wrap,
#getProductCategoryDetailsList_wrapper {
    border: 0 !important;
    box-shadow: none !important;
}

.product-information-theme .product-category-controls {
    margin-bottom: 1rem;
}

.product-information-theme .product-category-controls .form-control,
.product-information-theme .product-category-controls .form-select,
.product-information-theme .product-category-controls .input-group-text {
    min-height: 2.5rem;
    color: var(--product-info-text);
    border-color: var(--product-info-border);
    background-color: #fff;
}

.product-information-theme .product-category-controls .input-group-text {
    color: var(--product-info-teal);
    border-radius: .65rem 0 0 .65rem;
}

.product-information-theme .product-category-controls .form-control {
    border-radius: 0 .65rem .65rem 0;
}

.product-information-theme .product-category-controls .form-select {
    border-radius: .65rem;
}

.product-information-theme .product-category-management-table thead th {
    color: var(--product-info-cream) !important;
    background-color: var(--product-info-teal) !important;
    border-bottom: 2px solid var(--product-info-sage) !important;
    font-weight: 600;
    padding: .85rem 1rem;
    white-space: nowrap;
}

.product-information-theme .product-category-management-table thead th:first-child {
    border-top-left-radius: .65rem;
}

.product-information-theme .product-category-management-table thead th:last-child {
    border-top-right-radius: .65rem;
}

.product-information-theme .product-category-management-table tbody td {
    padding: .8rem 1rem;
    border-bottom-color: rgba(86, 131, 107, .2);
}

.product-information-theme .product-category-management-table tbody tr:last-child td {
    border-bottom: 0;
}

.product-information-theme .product-category-management-table tbody tr:hover > * {
    background-color: var(--product-info-cream-soft) !important;
}

.product-information-theme .product-category-management-table .dropdown > .btn {
    width: 2.25rem;
    height: 2.25rem;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--product-info-teal);
    border-color: var(--product-info-border) !important;
    background-color: var(--product-info-cream-soft);
    transition: background-color .15s ease, color .15s ease, transform .15s ease;
}

.product-information-theme .product-category-management-table .dropdown > .btn:hover {
    color: var(--product-info-cream);
    background-color: var(--product-info-teal);
    border-color: var(--product-info-teal) !important;
    transform: translateY(-1px);
}

#getProductCategoryDetailsList_wrapper .dataTables_info {
    color: #718078;
    padding-top: .85rem;
}

#getProductCategoryDetailsList_wrapper .page-item.active .page-link {
    color: var(--product-info-cream);
    background-color: var(--product-info-teal);
    border-color: var(--product-info-teal);
}

#getProductCategoryDetailsList_wrapper .page-link {
    color: var(--product-info-teal);
    border-radius: .5rem;
    margin-left: .2rem;
}

</style>
<!-- PRODUCT CATEGORY MANAGEMENT -->
		<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

			<!-- HEADER -->
			<div class="card-header bg-white border-0 py-3 px-4">

				<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

					<!-- TITLE -->
					<div class="d-flex align-items-center">

						<!-- ICON -->
						<div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
							 style="width:55px;height:55px;">

							<i class="bi bi-tags text-success fs-4"></i>

						</div>

						<!-- TEXT -->
						<div>

							<h4 class="fw-bold mb-0">
								Product Categories
							</h4>
							
							<small class="text-muted">
								Product Category List
							</small>

						</div>

					</div>

				   <!-- ACTIONS -->
					<div class="d-flex align-items-center gap-2 flex-wrap"
						 id="product_category_option">

						<!-- CREATE BUTTON -->
						<button type="button" id="createProductCategoryDetails" class="btn btn-success rounded-3 shadow-sm px-3">

							<i class="bi bi-plus-circle me-2"></i>
							New Category

						</button>

					</div>

				</div>

			</div>

			<!-- BODY -->
			<div class="card-body p-4">

				<div class="product-category-controls">
					<div class="row g-3 align-items-end">
						<div class="col-lg-8 col-md-7">
							<label for="product_category_search" class="form-label small fw-semibold mb-1">
								<i class="bi bi-search me-1" aria-hidden="true"></i>Search Categories
							</label>
							<div class="input-group">
								<span class="input-group-text">
									<i class="bi bi-search" aria-hidden="true"></i>
								</span>
								<input type="search" id="product_category_search" class="form-control"
									placeholder="Search category name or code..." autocomplete="off">
							</div>
						</div>
						<div class="col-lg-4 col-md-5">
							<label for="product_category_length" class="form-label small fw-semibold mb-1">
								<i class="bi bi-list-ol me-1" aria-hidden="true"></i>Rows to Display
							</label>
							<select id="product_category_length" class="form-select">
								<option value="10">10 rows</option>
								<option value="25">25 rows</option>
								<option value="50">50 rows</option>
								<option value="100">100 rows</option>
								<option value="-1">All rows</option>
							</select>
						</div>
					</div>
				</div>

				<!-- TABLE -->
				<div class="table-responsive product-category-table-wrap">

					<table class="table table-hover align-middle nowrap w-100 product-category-management-table"
						   id="getProductCategoryDetailsList">

						<thead class="table-light">

							<tr>

								<th class="all text-center">#</th>
								<th class="all">Category Name</th>
								<th class="all text-center">Category Code</th>
								<th class="all text-center">Actions</th>

							</tr>

						</thead>

						<tbody></tbody>

					</table>

				</div>

			</div>

		</div>
