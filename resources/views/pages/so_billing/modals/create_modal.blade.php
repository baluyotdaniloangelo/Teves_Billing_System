<div class="modal fade so-modal-theme" id="AddSOModal" tabindex="-1" aria-labelledby="add-so-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content so-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="so-modal-icon me-3"><i class="bi bi-receipt-cutoff fs-4" aria-hidden="true"></i></div>
                    <div>
                        <p class="so-modal-eyebrow">Sales order</p>
                        <h2 class="modal-title" id="add-so-title">Create SO information</h2>
                        <small class="text-white-50">Add a sales order to the billing system.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>

            <form id="SOBillingformNew" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <p class="so-form-note">Enter the order details. You can add products after saving.</p>
                    <div id="so-create-feedback" class="so-feedback" role="alert" hidden></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="branch_id" class="form-label">Branch <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="form-select" name="branch_id" id="branch_id" required>
                                @foreach ($teves_branch as $branch)
                                    <option value="{{ $branch->branch_id }}">{{ $branch->branch_code }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="branch_idError"></div>
                        </div>
                        <div class="col-12">
                            <label for="so_client_idx" class="form-label">Account name <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" class="form-control" name="so_client_name" id="so_client_idx" list="so_client_name" autocomplete="off" required>
                            <datalist id="so_client_name">
                                @foreach ($client_data as $client)
                                    <option data-id="{{ $client->client_id }}" value="{{ $client->client_name }}"></option>
                                @endforeach
                            </datalist>
                            <div class="invalid-feedback" id="so_client_idxError"></div>
                        </div>
                        <div class="col-12">
                            <label for="so_number" class="form-label">S.O. number <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" class="form-control" name="so_number" id="so_number" maxlength="100" autocomplete="off" required>
                            <div class="invalid-feedback" id="so_numberError"></div>
                        </div>
                        <div class="col-6">
                            <label for="so_order_date" class="form-label">Date <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="date" class="form-control" name="so_order_date" id="so_order_date" value="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback" id="so_order_dateError"></div>
                        </div>
                        <div class="col-6">
                            <label for="so_order_time" class="form-label">Time <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="time" class="form-control" name="so_order_time" id="so_order_time" value="{{ date('H:i') }}" required>
                            <div class="invalid-feedback" id="so_order_timeError"></div>
                        </div>
                        <div class="col-12">
                            <label for="so_plate_no" class="form-label">Description <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" class="form-control" name="so_plate_no" id="so_plate_no" list="so_plate_no_list" maxlength="255" required>
                            <datalist id="so_plate_no_list">
                                @foreach ($plate_no as $plate)
                                    <option value="{{ $plate->plate_no }}"></option>
                                @endforeach
                            </datalist>
                            <div class="invalid-feedback" id="so_plate_noError"></div>
                        </div>
                        <div class="col-12">
                            <label for="so_drivers_name" class="form-label">Driver's name <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" class="form-control" name="so_drivers_name" id="so_drivers_name" list="so_drivers_list" maxlength="255" required>
                            <datalist id="so_drivers_list">
                                @foreach ($drivers_name as $driver)
                                    <option value="{{ $driver->drivers_name }}"></option>
                                @endforeach
                            </datalist>
                            <div class="invalid-feedback" id="so_drivers_nameError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="so-required-note"><span class="text-danger" aria-hidden="true">*</span> Required</span>
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="save-so-billing-transaction">
                        <span class="spinner-border spinner-border-sm me-1" id="so-save-spinner" role="status" aria-hidden="true" hidden></span>
                        <i class="bi bi-check2-circle" aria-hidden="true"></i> Save and add products
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
