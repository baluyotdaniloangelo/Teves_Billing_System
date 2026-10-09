<!-- ========================================== -->
<!-- DELETE LPG EXPENSE MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="CashierReportLpgExpensesDeleteModal"
     tabindex="-1"
     aria-labelledby="CashierReportLpgExpensesDeleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #dc3545, #b02a37);">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3"
                         style="width:44px;height:44px;">
                        <i class="bi bi-trash3-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="CashierReportLpgExpensesDeleteModalLabel">Delete Expense</h6>
                        <small class="text-white-50">Please confirm this deletion.</small>
                    </div>
                </div>
                <button type="button"
                        class="btn btn-light btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:32px;height:32px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-warning border-0 rounded-3 d-flex align-items-start mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <div class="small">
                        Are you sure you want to delete this expense?
                        <strong>This record will be removed from the report.</strong>
                    </div>
                </div>

                <input type="hidden" id="lpg_expense_delete_id">

                <div class="card border-0 bg-light rounded-3">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Account Name</span>
                            <span class="fw-semibold small text-end" id="lpg_expense_delete_client">-</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Type</span>
                            <span class="fw-semibold small text-end" id="lpg_expense_delete_type">-</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Purpose</span>
                            <span class="fw-semibold small text-end" id="lpg_expense_delete_purpose">-</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Item</span>
                            <span class="fw-semibold small text-end" id="lpg_expense_delete_item">-</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Quantity</span>
                            <span class="fw-semibold small text-end" id="lpg_expense_delete_quantity">-</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Unit Price</span>
                            <span class="fw-semibold small text-end">₱ <span id="lpg_expense_delete_unit_price">0.00</span></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Remarks</span>
                            <span class="fw-semibold small text-end" id="lpg_expense_delete_remarks">-</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2">
                            <span class="text-muted small fw-semibold">Amount</span>
                            <span class="text-danger fw-bold">₱ <span id="lpg_expense_delete_amount">0.00</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-2">
                <button type="button"
                        class="btn btn-light border btn-sm rounded-3 px-3"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button"
                        class="btn btn-danger btn-sm rounded-3 px-3"
                        id="deleteLpgExpenseConfirmed">
                    <i class="bi bi-trash3-fill me-2"></i>Delete Expense
                </button>
            </div>

        </div>
    </div>
</div>
