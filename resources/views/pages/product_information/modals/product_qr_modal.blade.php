<div class="modal fade product-information-modal-theme" id="ProductQrModal" tabindex="-1" aria-labelledby="ProductQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header text-white border-0" style="background: linear-gradient(120deg, #471323, #173e3a);">
                <div>
                    <span class="small text-uppercase opacity-75">Product price lookup</span>
                    <h5 class="modal-title fw-bold mb-0" id="ProductQrModalLabel">Product QR Code</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h6 class="fw-bold mb-1" id="productQrName">Product</h6>
                <div class="small text-muted mb-3">Product Code: <span class="font-monospace" id="productQrCodeLabel"></span></div>
                <div class="d-flex justify-content-center mb-3">
                    <div id="productQrCanvas" class="bg-white border rounded-3 p-2" style="min-width: 240px; min-height: 240px;"></div>
                </div>
                <p class="small text-muted mb-0">Scan with a phone camera to open the current product price page.</p>
                <div id="productQrError" class="small text-danger mt-2" role="alert" hidden></div>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex justify-content-between">
                <a href="#" id="productQrOpenPage" target="_blank" rel="noopener" class="btn btn-light btn-sm rounded-3">
                    <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Open Page
                </a>
                <button type="button" id="downloadProductQr" class="btn btn-primary btn-sm rounded-3">
                    <i class="bi bi-download me-1" aria-hidden="true"></i>Download QR
                </button>
            </div>
        </div>
    </div>
</div>
