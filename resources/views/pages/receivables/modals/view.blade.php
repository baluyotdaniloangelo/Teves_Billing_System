<div class="modal fade br-modal" id="BillingReceivableViewModal" tabindex="-1" aria-labelledby="BillingReceivableViewTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <p class="br-modal-eyebrow">BILLING / RECEIVABLE</p>
                    <h5 class="modal-title" id="BillingReceivableViewTitle">Receivable details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="br-view-account">
                    <span class="br-view-icon"><i class="bi bi-receipt-cutoff" aria-hidden="true"></i></span>
                    <div>
                        <strong data-br-view="account">—</strong>
                        <span data-br-view="control">—</span>
                    </div>
                    <span class="br-status" data-br-view="status">—</span>
                </div>
                <dl class="br-view-details">
                    <div><dt>Billing date</dt><dd data-br-view="date">—</dd></div>
                    <div><dt>Branch</dt><dd data-br-view="branch">—</dd></div>
                    <div><dt>Amount due</dt><dd data-br-view="due">—</dd></div>
                    <div><dt>Collected</dt><dd data-br-view="collected">—</dd></div>
                    <div><dt>Remaining balance</dt><dd data-br-view="balance">—</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn br-btn-secondary" data-bs-dismiss="modal">Close</button>
                <a class="btn br-btn-primary" data-br-view="open" href="#"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Open full details</a>
            </div>
        </div>
    </div>
</div>
