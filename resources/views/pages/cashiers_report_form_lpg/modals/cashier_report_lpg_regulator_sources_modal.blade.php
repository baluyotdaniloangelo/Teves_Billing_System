<div class="modal fade" id="LpgRegulatorSourcesModal" tabindex="-1" aria-labelledby="LpgRegulatorSourcesTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <div><h5 class="modal-title" id="LpgRegulatorSourcesTitle">Regulator Source Records</h5><small id="lpg-regulator-source-selection"></small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="lpg-regulator-source-error" role="alert"></div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead><tr><th>Item</th><th>Movement</th><th>Customer / Supplier</th><th>Reference</th><th class="text-end">Quantity</th><th>Remarks</th><th>Action</th></tr></thead>
                        <tbody id="lpg-regulator-source-rows"></tbody>
                    </table>
                </div>
                <div class="text-end fw-semibold">Total Qty: <span id="lpg-regulator-source-total">—</span></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
