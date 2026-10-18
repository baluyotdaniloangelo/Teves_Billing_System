<div class="modal fade" id="SummaryExportModal" tabindex="-1" aria-labelledby="summaryExportTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="summaryExportTitle">Export Sales Order Summary</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="summary_export_form">
            <div class="modal-body p-4">
                <div class="small text-muted mb-3" id="summary_export_scope"></div>
                <label for="summary_export_layout" class="form-label">Branch Layout</label>
                <select class="form-select mb-3" id="summary_export_layout">
                    <option value="combined">Combined - all branches together</option>
                    <option value="per_branch">Separate - new page for each branch</option>
                </select>
                <div id="summary_export_content_group"><label for="summary_export_content" class="form-label">Content</label><select class="form-select" id="summary_export_content"><option value="summary">Branch summary and grand total</option><option value="details">Summary and sales order details</option></select></div>
                <div class="form-text mt-2" id="summary_export_description">One PDF with totals for every selected branch.</div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success"><i class="bi bi-file-earmark-pdf me-1"></i>Open PDF</button></div>
        </form>
    </div></div>
</div>
