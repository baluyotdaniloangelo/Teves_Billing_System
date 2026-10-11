<style>
#ProductInformationModal .product-information-modal-content {
    border: 0;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, .2);
}

#ProductInformationModal .product-information-modal-header {
    color: #fff;
    border: 0;
    padding: .85rem 1.25rem;
    background: linear-gradient(115deg, var(--product-info-burgundy), var(--product-info-teal) 72%, var(--product-info-sage));
}

#ProductInformationModal .product-information-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    padding: .5rem;
    margin-bottom: 1rem;
    background: rgba(92, 131, 116, .1);
    border: 1px solid rgba(92, 131, 116, .28);
    border-radius: 1rem;
}

#ProductInformationModal .product-information-tabs .nav-item {
    flex: 1 1 8.5rem;
}

#ProductInformationModal .product-information-tabs .nav-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    width: 100%;
    min-height: 2.85rem;
    padding: .45rem .65rem;
    color: #1B4242;
    font-size: .9rem;
    font-weight: 600;
    white-space: nowrap;
    background: #fff;
    border: 1px solid rgba(92, 131, 116, .28);
    border-radius: .75rem;
    transition: background-color .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease;
}

#ProductInformationModal .product-information-tabs .nav-link:hover:not(.active) {
    color: #441827;
    background: #FBF9E8;
    border-color: #5C8374;
}

#ProductInformationModal .product-information-tabs .nav-link.active {
    color: #F5F2B8;
    background: #1B4242;
    border-color: #1B4242;
    box-shadow: 0 .25rem .75rem rgba(27, 66, 66, .2);
}

#ProductInformationModal .product-information-tabs .nav-link i {
    font-size: 1rem;
}

@media (max-width: 575.98px) {
    #ProductInformationModal .modal-body {
        padding: .75rem;
    }

    #ProductInformationModal .product-information-modal-header {
        padding: .75rem;
    }
}
</style>

<div class="modal fade product-information-modal-theme" id="ProductInformationModal" tabindex="-1" aria-labelledby="productInformationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content product-information-modal-content">
            <div class="modal-header product-information-modal-header">
                <div class="d-flex align-items-center text-white">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                        <i class="bi bi-box-seam-fill fs-4" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="productInformationModalTitle">Product Information</h5>
                        <small class="text-white-50">Manage product details, pricing, tanks, and pumps.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width:36px;height:36px;">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-3">
                @include('pages.product_information.tabs.product_details')
            </div>
        </div>
    </div>
</div>
