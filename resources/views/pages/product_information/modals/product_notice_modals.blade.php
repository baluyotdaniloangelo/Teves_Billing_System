<div class="modal fade product-information-modal-theme" id="ProductSuccessModal" tabindex="-1" aria-labelledby="productSuccessModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-body text-center p-4">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                    <i class="bi bi-check-circle-fill text-success fs-1" aria-hidden="true"></i>
                </div>
                <h5 class="fw-bold mb-2" id="productSuccessModalTitle">Success</h5>
                <div class="text-muted" id="productSuccessModalMessage">Product saved successfully.</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade product-information-modal-theme" id="ProductValidationErrorModal" tabindex="-1" aria-labelledby="productValidationErrorModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-body text-center p-4">
                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                    <i class="bi bi-exclamation-circle-fill text-danger fs-1" aria-hidden="true"></i>
                </div>
                <h5 class="fw-bold text-danger mb-2" id="productValidationErrorModalTitle">Validation Error</h5>
                <div class="text-muted" id="productValidationErrorModalMessage">Please review the product information and try again.</div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-danger rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2" aria-hidden="true"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
