<div class="modal fade" id="LpgSwappingEntriesModal" tabindex="-1" aria-labelledby="LpgSwappingEntriesTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <div><h5 class="modal-title" id="LpgSwappingEntriesTitle">Swapping Entries</h5><small id="lpg-swapping-entries-selection"></small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">Damaged items received from a client appear beside their swap replacements in the main table. Edit or delete the original entries below. B.O. replacements from suppliers are recorded separately.</p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead><tr><th>Entry #</th><th>Received Item</th><th>Received Qty</th><th>Replaced Item</th><th>Replaced Qty</th><th>Remarks</th><th>Action</th></tr></thead>
                        <tbody id="lpg-swapping-entry-rows"></tbody>
                    </table>
                </div>
                <div class="text-end small fw-semibold" id="lpg-swapping-entries-totals"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
