<div class="modal fade"
     id="UpdateCashiersReportModal"
     tabindex="-1"
     aria-labelledby="UpdateCashiersReportModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white border-0 px-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:46px;height:46px;">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="UpdateCashiersReportModalLabel">
                            Edit LPG Cashier's Report
                        </h5>
                        <small class="text-white-50">Update report information</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle"
                        data-bs-dismiss="modal" aria-label="Close" style="width:34px;height:34px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form class="needs-validation" id="LpgCashierReportUpdateForm" novalidate>
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="teves_branch" class="form-label fw-semibold">Branch</label>
                            <select class="form-select rounded-3" name="teves_branch" id="teves_branch" required>
                                @foreach ($teves_branch as $teves_branch_cols)
                                    <option value="{{ $teves_branch_cols->branch_id }}">
                                        {{ $teves_branch_cols->branch_code }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="teves_branchError">Please select a branch.</div>
                        </div>

                        <div class="col-12">
                            <label for="report_date" class="form-label fw-semibold">Report Date</label>
                            <input type="date" class="form-control rounded-3"
                                   name="report_date" id="report_date" required>
                            <div class="invalid-feedback" id="report_dateError">Please select a report date.</div>
                        </div>

                        <div class="col-12">
                            <label for="cashiers_name" class="form-label fw-semibold">Cashier's on Duty</label>
                            <input type="text" class="form-control rounded-3"
                                   name="cashiers_name" id="cashiers_name"
                                   placeholder="Enter cashier's name" autocomplete="off" required>
                            <div class="invalid-feedback" id="cashiers_nameError">Please enter the cashier's name.</div>
                        </div>

                        <div class="col-12">
                            <label for="forecourt_attendant" class="form-label fw-semibold">Employee's On-Duty</label>
                            <input type="text" class="form-control rounded-3"
                                   name="forecourt_attendant" id="forecourt_attendant"
                                   placeholder="Enter employee's name" autocomplete="off" required>
                            <div class="invalid-feedback" id="forecourt_attendantError">Please enter the employee's name.</div>
                        </div>

                        <div class="col-12">
                            <label for="shift" class="form-label fw-semibold">Shift</label>
                            <select class="form-select rounded-3" name="shift" id="shift" required>
                                <option value="1st Shift">1st Shift</option>
                                <option value="2nd Shift">2nd Shift</option>
                                <option value="3rd Shift">3rd Shift</option>
                                <option value="4th Shift">4th Shift</option>
                                <option value="5th Shift">5th Shift</option>
                                <option value="6th Shift">6th Shift</option>
                            </select>
                            <div class="invalid-feedback" id="shiftError">Please select a shift.</div>
                        </div>

                        <div class="col-12">
                            <label for="cashier_report_remarks" class="form-label fw-semibold">Remarks</label>
                            <textarea class="form-control rounded-3"
                                      name="cashier_report_remarks"
                                      id="cashier_report_remarks"
                                      rows="2"
                                      placeholder="Optional remarks"></textarea>
                            <div class="invalid-feedback" id="cashier_report_remarksError">Please check the remarks.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-light border rounded-3" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success rounded-3 shadow-sm" id="update-lpg-cashiers-report">
                        <i class="bi bi-save-fill me-1"></i>Update Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
