<div class="modal fade" id="LpgSellOutSourcesModal" tabindex="-1" aria-labelledby="LpgSellOutSourcesTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="LpgSellOutSourcesTitle">Source Records</h5>
                    <small id="lpg-sell-out-source-item"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" role="alert" id="lpg-sell-out-source-error"></div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead><tr><th>Item</th><th>Supplier / Account</th><th>DR / Reference / Type</th><th class="text-end">Quantity</th><th class="text-end">SRP</th><th class="text-end">Gross Amount</th><th>Remarks</th><th>Action</th></tr></thead>
                        <tbody id="lpg-sell-out-source-rows"></tbody>
                    </table>
                </div>
                <div class="text-end fw-semibold mt-3">Total: <span id="lpg-sell-out-source-total">—</span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="lpg-sell-out-source-add"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add / Edit Entry</button>
            </div>
        </div>
    </div>
</div>

