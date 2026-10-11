<div class="modal fade product-information-modal-theme" id="AddProductTankModal" tabindex="-1" aria-labelledby="AddProductTankModalTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;"><i class="bi bi-fuel-pump-fill fs-5" aria-hidden="true"></i></div>
                    <div><h5 class="modal-title fw-bold mb-0" id="AddProductTankModalTitle">Add Tank</h5><small class="text-white-50">Register a fuel tank for this branch.</small></div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <form class="needs-validation" id="AddProductTank" novalidate>
                <div class="modal-body product-settings-modal-body">
                    <section class="product-settings-form-card">
                        <div class="mb-3">
                            <label for="branch_idx" class="form-label small fw-semibold mb-1"><i class="bi bi-building me-1 text-success" aria-hidden="true"></i>Branch</label>
                            <select class="form-select form-select-sm rounded-3" required name="branch_idx" id="branch_idx">
                                @foreach ($teves_branch as $teves_branch_cols)
                                    <option value="{{ $teves_branch_cols->branch_id }}">{{ $teves_branch_cols->branch_code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tank_name" class="form-label small fw-semibold mb-1"><i class="bi bi-pencil-square me-1 text-success" aria-hidden="true"></i>Tank Name</label>
                            <input class="form-control form-control-sm rounded-3" name="tank_name" id="tank_name" required autocomplete="off" placeholder="e.g. Tank 1">
                        </div>
                        <div>
                            <label for="tank_capacity" class="form-label small fw-semibold mb-1"><i class="bi bi-speedometer2 me-1 text-success" aria-hidden="true"></i>Capacity</label>
                            <input type="number" class="form-control form-control-sm rounded-3" name="tank_capacity" id="tank_capacity" min="0" step="0.01" inputmode="decimal" placeholder="Enter capacity" required>
                        </div>
                    </section>
                </div>
                <div class="modal-footer product-settings-modal-footer">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                    <button type="reset" class="btn btn-light border btn-sm rounded-3 px-3"><i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>Reset</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="save-tank"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Tank</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="UpdateProductTankModal" tabindex="-1" aria-labelledby="UpdateProductTankModalTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content product-settings-modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;"><i class="bi bi-fuel-pump-fill fs-5" aria-hidden="true"></i></div>
                    <div><h5 class="modal-title fw-bold mb-0" id="UpdateProductTankModalTitle">Update Tank</h5><small class="text-white-50">Update this tank's branch and capacity details.</small></div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
            </div>
            <form class="needs-validation" id="UpdateProductTank" novalidate>
                <div class="modal-body product-settings-modal-body">
                    <section class="product-settings-form-card">
                        <div class="mb-3">
                            <label for="update_branch_idx" class="form-label small fw-semibold mb-1"><i class="bi bi-building me-1 text-success" aria-hidden="true"></i>Branch</label>
                            <select class="form-select form-select-sm rounded-3" required name="update_branch_idx" id="update_branch_idx">
                                @foreach ($teves_branch as $teves_branch_cols)
                                    <option value="{{ $teves_branch_cols->branch_id }}">{{ $teves_branch_cols->branch_code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="update_tank_name" class="form-label small fw-semibold mb-1"><i class="bi bi-pencil-square me-1 text-success" aria-hidden="true"></i>Tank Name</label>
                            <input class="form-control form-control-sm rounded-3" name="update_tank_name" id="update_tank_name" required autocomplete="off" placeholder="e.g. Tank 1">
                        </div>
                        <div>
                            <label for="update_tank_capacity" class="form-label small fw-semibold mb-1"><i class="bi bi-speedometer2 me-1 text-success" aria-hidden="true"></i>Capacity</label>
                            <input type="number" class="form-control form-control-sm rounded-3" name="update_tank_capacity" id="update_tank_capacity" min="0" step="0.01" inputmode="decimal" placeholder="Enter capacity" required>
                        </div>
                    </section>
                </div>
                <div class="modal-footer product-settings-modal-footer">
                    <button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3" id="update-tank" value="0"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="TankInfoDeleteModal" tabindex="-1" aria-labelledby="TankInfoDeleteModal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content product-settings-modal-content">
        <div class="modal-header px-4 py-3"><div class="d-flex align-items-center text-white"><div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;"><i class="bi bi-trash3-fill" aria-hidden="true"></i></div><div><h5 class="modal-title fw-bold mb-0" id="TankInfoDeleteModal_title">Delete Tank?</h5><small class="text-white-50">Please confirm this deletion.</small></div></div><button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:34px;height:34px;"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <div class="modal-body product-settings-modal-body"><div class="alert alert-warning border-0 rounded-3 d-flex align-items-start mb-3"><i class="bi bi-exclamation-triangle-fill me-2 mt-1" aria-hidden="true"></i><div class="small">This tank record will be removed.</div></div><div class="card border-0 bg-light rounded-3"><div class="card-body py-2 px-3"><dl class="row small mb-0"><dt class="col-5">Name</dt><dd class="col-7" id="delete_tank_name"></dd><dt class="col-5">Capacity</dt><dd class="col-7 mb-0" id="delete_tank_capacity"></dd></dl></div></div></div>
        <div class="modal-footer product-settings-modal-footer"><button type="button" class="btn btn-light border btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger btn-sm rounded-3 px-3" id="deleteTankInfoConfirmed" value="0"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete</button></div>
    </div></div>
</div>