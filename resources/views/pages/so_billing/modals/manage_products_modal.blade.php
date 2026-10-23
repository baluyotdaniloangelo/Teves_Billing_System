<div class="modal fade so-modal-theme" id="ManageSOProductsModal" tabindex="-1" aria-labelledby="manage-so-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content so-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="so-modal-icon me-3"><i class="bi bi-box-seam fs-4" aria-hidden="true"></i></div>
                    <div>
                        <p class="so-modal-eyebrow">Sales order products</p>
                        <h2 class="modal-title" id="manage-so-title">Products for <span id="manage_so_number">—</span></h2>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <div id="manage-so-feedback" class="so-feedback mb-3" role="alert" hidden></div>
                <div class="row g-3 align-items-start" id="manage-so-products-view">
                    <section class="col-lg-8" aria-labelledby="manage-so-products-heading">
                        <div class="so-products-panel">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                <h3 class="so-products-title mb-0" id="manage-so-products-heading">Products on this order</h3>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small text-muted text-nowrap">Total: <strong id="manage_so_total">₱0.00</strong></span>
                                    <button type="button" class="btn btn-success btn-sm rounded-3 text-nowrap" id="open-so-add-product" hidden>
                                        <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add Product
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle so-products-table">
                                    <caption class="visually-hidden">Products included on this sales order</caption>
                                    <thead><tr><th scope="col">#</th><th scope="col">Product</th><th scope="col" class="text-end">Price</th><th scope="col" class="text-end">Quantity</th><th scope="col" class="text-end">Amount</th><th scope="col" class="text-center">Action</th></tr></thead>
                                    <tbody id="manage_so_products_body"><tr><td colspan="6" class="text-center text-muted">Select a sales order to load its products.</td></tr></tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <aside class="col-lg-4" aria-labelledby="manage-so-information-heading">
                        <form id="UpdateSOInformationForm" class="so-information-form so-info-panel" novalidate>
                            @csrf
                            <input type="hidden" id="manage_so_id" name="so_id">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                <h3 class="so-products-title mb-0" id="manage-so-information-heading">SO Information</h3>
                                <button type="submit" class="btn btn-success btn-sm rounded-3 px-2 text-nowrap" id="save-so-information">
                                    <span class="spinner-border spinner-border-sm me-1" id="so-information-spinner" role="status" aria-hidden="true" hidden></span>
                                    <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Save
                                </button>
                            </div>
                            <div class="so-info-fields">
                                <div>
                                    <label for="manage_so_branch" class="form-label">Branch <span class="text-danger" aria-hidden="true">*</span></label>
                                    <select id="manage_so_branch" name="branch_id" class="form-select" required>
                                        @foreach ($teves_branch as $branch)
                                            <option value="{{ $branch->branch_id }}">{{ $branch->branch_code }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Choose a branch.</div>
                                </div>
                                <div>
                                    <label for="manage_so_client" class="form-label">Account / Client <span class="text-danger" aria-hidden="true">*</span></label>
                                    <select id="manage_so_client" name="client_idx" class="form-select" required>
                                        @foreach ($client_data as $client)
                                            <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Choose an account or client.</div>
                                </div>
                                <div>
                                    <label for="manage_so_number_input" class="form-label">S.O. number <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="text" id="manage_so_number_input" name="so_number" class="form-control" maxlength="100" required autocomplete="off">
                                    <div class="invalid-feedback">Enter the S.O. number.</div>
                                </div>
                                <div>
                                    <label for="manage_so_date" class="form-label">Date <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="date" id="manage_so_date" name="order_date" class="form-control" required>
                                    <div class="invalid-feedback">Choose a date.</div>
                                </div>
                                <div>
                                    <label for="manage_so_time" class="form-label">Time <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="time" id="manage_so_time" name="order_time" class="form-control" required>
                                    <div class="invalid-feedback">Choose a time.</div>
                                </div>
                                <div>
                                    <label for="manage_so_description" class="form-label">Description <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="text" id="manage_so_description" name="plate_no" class="form-control" maxlength="255" required list="manage_so_description_options" autocomplete="off">
                                    <datalist id="manage_so_description_options">
                                        @foreach ($plate_no as $plate)
                                            <option value="{{ $plate->plate_no }}"></option>
                                        @endforeach
                                    </datalist>
                                    <div class="invalid-feedback">Enter a description.</div>
                                </div>
                                <div>
                                    <label for="manage_so_driver" class="form-label">Driver's name <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="text" id="manage_so_driver" name="drivers_name" class="form-control" maxlength="255" required list="manage_so_driver_options" autocomplete="off">
                                    <datalist id="manage_so_driver_options">
                                        @foreach ($drivers_name as $driver)
                                            <option value="{{ $driver->drivers_name }}"></option>
                                        @endforeach
                                    </datalist>
                                    <div class="invalid-feedback">Enter the driver's name.</div>
                                </div>
                            </div>
                        </form>
                    </aside>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
