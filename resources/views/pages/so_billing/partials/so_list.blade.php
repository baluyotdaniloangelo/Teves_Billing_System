<section class="card border-0 shadow-sm rounded-4 overflow-hidden so-list-card">
    <div class="card-header bg-white border-0 py-3 px-4 so-list-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 w-100">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 so-list-icon" aria-hidden="true">
                    <i class="bi bi-receipt-cutoff text-success fs-4"></i>
                </div>
            <div>
                    <h4 class="fw-bold mb-0" id="so-list-title">{{ $title }}</h4>
                    <small class="text-muted">Review and manage sales orders.</small>
                </div>
            </div>
            @if (in_array($data->user_type ?? '', ['SUAdmin', 'Admin', 'Accounting_Staff', 'Encoder'], true))
                <button type="button" class="btn btn-outline-primary rounded-3 shadow-sm px-3 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#AddSOModal">
                    <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                    New Sales Order
                </button>
            @endif
        </div>
    </div>
    <div class="card-body p-3 p-lg-4 so-list-body">
        <div id="so-list-feedback" class="so-feedback" role="status" aria-live="polite" hidden></div>
        <div class="card border-0 shadow-sm rounded-4 mb-3 so-filter-card">
            <div class="card-body p-3">
                <div class="row g-3 align-items-end">
                    <div class="col-xxl-4 col-lg-6">
                        <label for="so_list_search" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-search text-primary me-1" aria-hidden="true"></i>Search Sales Orders
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted" aria-hidden="true"></i></span>
                            <input type="search" id="so_list_search" class="form-control" placeholder="Search sales orders..." autocomplete="off">
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-6">
                        <label for="so_branch_filter" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-geo-alt text-secondary me-1" aria-hidden="true"></i>Branch
                        </label>
                        <select id="so_branch_filter" class="form-select form-select-sm">
                            <option value="">All branches</option>
                            @foreach ($teves_branch as $branch)
                                <option value="{{ $branch->branch_id }}">{{ $branch->branch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-3 col-lg-3 col-md-6">
                        <label for="so_client_filter" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-person text-secondary me-1" aria-hidden="true"></i>Account / Client
                        </label>
                        <select id="so_client_filter" class="form-select form-select-sm">
                            <option value="">All accounts / clients</option>
                            @foreach ($client_data as $client)
                                <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-3">
                        <label for="so_list_length" class="form-label small fw-semibold mb-1">
                            <i class="bi bi-list-ol text-secondary me-1" aria-hidden="true"></i>Rows to Display
                        </label>
                        <select id="so_list_length" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-xxl-1 col-lg-4 col-md-3 d-flex justify-content-md-end">
                        <button type="button" id="resetSOListFilters" class="btn btn-light btn-sm rounded-3 px-2 text-nowrap" title="Reset all filters" aria-label="Reset all filters">
                            <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle dataTable display nowrap w-100 so-list-table" id="getSOBillingTransactionList" width="100%">
                <caption class="visually-hidden">Sales orders, including date, time, order number, branch, account, driver, and description</caption>
                <thead>
                    <tr>
                        <th class="all dtr-control text-center" scope="col"><i class="bi bi-hash" aria-hidden="true"></i>#</th>
                        <th class="all" scope="col"><i class="bi bi-calendar3" aria-hidden="true"></i>Date</th>
                        <th class="all text-center" scope="col"><i class="bi bi-clock" aria-hidden="true"></i>Time</th>
                        <th class="all" scope="col"><i class="bi bi-receipt" aria-hidden="true"></i>S.O. No.</th>
                        <th class="all" scope="col"><i class="bi bi-geo-alt" aria-hidden="true"></i>Branch</th>
                        <th class="all" scope="col"><i class="bi bi-person" aria-hidden="true"></i>Account Name</th>
                        <th class="none" scope="col">Driver's name</th>
                        <th class="none" scope="col">Description</th>
                        <th class="all text-center text-nowrap" scope="col"><i class="bi bi-three-dots" aria-hidden="true"></i>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>

@include('pages.so_billing.modals.manage_products_modal')
@include('pages.so_billing.modals.edit_product_modal')
@include('pages.so_billing.modals.add_product_modal')
@include('pages.so_billing.modals.delete_product_modal')
