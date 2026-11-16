<style>
    #ProductUserManualModal .modal-content {
        overflow: hidden;
        border: 1px solid rgba(92, 131, 116, .3);
        border-radius: 1rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .2);
    }

    #ProductUserManualModal .modal-header {
        color: #fff;
        border: 0;
        background: linear-gradient(115deg, #441827, #1B4242 72%, #5C8374);
    }

    #ProductUserManualModal .modal-header .modal-title {
        color: #fff;
    }

    #ProductUserManualModal .manual-section {
        height: 100%;
        padding: 1rem;
        border: 1px solid rgba(92, 131, 116, .25);
        border-radius: .85rem;
        background: #fff;
    }

    #ProductUserManualModal .manual-section h6 {
        color: #441827;
        font-weight: 700;
    }

    #ProductUserManualModal .manual-section h6 i {
        color: #5C8374;
    }

    #ProductUserManualModal .manual-section p,
    #ProductUserManualModal .manual-section li {
        color: #40534e;
        font-size: .86rem;
        line-height: 1.6;
    }

    #ProductUserManualModal .manual-section ul,
    #ProductUserManualModal .manual-section ol {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }

    #ProductUserManualModal .manual-term {
        color: #1B4242;
        font-weight: 700;
    }

    #ProductUserManualModal .manual-tab {
        display: inline-block;
        margin: .1rem .15rem .1rem 0;
        padding: .2rem .5rem;
        border: 1px solid rgba(92, 131, 116, .25);
        border-radius: 99px;
        color: #1B4242;
        background: #FBF9E8;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }
</style>

<div class="modal fade" id="ProductUserManualModal" tabindex="-1" aria-labelledby="ProductUserManualModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-25" style="width:44px;height:44px;">
                        <i class="bi bi-journal-text fs-4" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="ProductUserManualModalLabel">Product Page User Manual</h5>
                        <small class="text-white-50">Gabay sa pag-manage ng product groups, variants, presyo, at detalye.</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 p-md-4" style="background:#FBF9E8;">
                <div class="alert mb-3" role="note" style="color:#1B4242;background:rgba(92,131,116,.12);border-color:rgba(92,131,116,.3);">
                    <i class="bi bi-lightbulb me-1" aria-hidden="true"></i>
                    <strong>Mabilis na paliwanag:</strong> ang <span class="manual-term">Variant Group</span> ay pang-grupo ng magkakaugnay na produkto. Ang <span class="manual-term">Product Variant</span> ang mismong product record na may sariling code, unit, presyo, at optional na larawan.
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <section class="manual-section" aria-labelledby="manual-setup-heading">
                            <h6 id="manual-setup-heading"><i class="bi bi-diagram-3 me-1" aria-hidden="true"></i> 1. Ayusin ang product catalog</h6>
                            <ul>
                                <li>Sa <span class="manual-tab">Variant Groups</span>, pindutin ang <span class="manual-term">Add Variant Group</span>, ilagay ang pangalan at category, saka i-save.</li>
                                <li>Gamitin ang group para pagsamahin ang magkakaugnay na variants. Kailangang pareho ang category ng group at variant.</li>
                                <li>Kung wala pang group, maaari pa ring gumawa ng variant at iwan itong unassigned.</li>
                            </ul>
                        </section>
                    </div>

                    <div class="col-12 col-md-6">
                        <section class="manual-section" aria-labelledby="manual-create-heading">
                            <h6 id="manual-create-heading"><i class="bi bi-plus-square me-1" aria-hidden="true"></i> 2. Magdagdag ng Product Variant</h6>
                            <ol>
                                <li>Buksan ang <span class="manual-tab">Product Variants</span> at pindutin ang <span class="manual-term">Add Product Variant</span>.</li>
                                <li>Pumili ng category at optional na Variant Group, pagkatapos ilagay ang pangalan, unit, default price, at capacity kung kailangan.</li>
                                <li>Mag-upload ng larawan kung mayroon, saka pindutin ang <span class="manual-term">Create Product</span>.</li>
                            </ol>
                            <p class="small mt-2 mb-0">Pagkatapos ma-save, awtomatikong bubukas ang Product Information para maidagdag ang iba pang presyo at detalye.</p>
                        </section>
                    </div>

                    <div class="col-12">
                        <section class="manual-section" aria-labelledby="manual-edit-heading">
                            <h6 id="manual-edit-heading"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i> 3. Mag-update ng detalye at presyo</h6>
                            <p>Sa row ng variant, pindutin ang edit icon para buksan ang Product Information. Piliin ang tab na kailangan:</p>
                            <div class="mb-2">
                                <span class="manual-tab">Product Information</span> Pangalan, category, group, unit, capacity, default price, at larawan.
                                <span class="manual-tab">Price per Branch</span> Presyong itinatalaga sa branch.
                                <span class="manual-tab">Seller Price</span> Presyong kaugnay ng supplier.
                                <span class="manual-tab">Selling Price</span> Presyong itinatalaga sa client.
                            </div>
                            <p class="mb-0">I-save ang bawat pagbabago gamit ang <span class="manual-term">Save Changes</span> o ang save button sa kaukulang tab.</p>
                        </section>
                    </div>

                    <div class="col-12 col-md-6">
                        <section class="manual-section" aria-labelledby="manual-set-heading">
                            <h6 id="manual-set-heading"><i class="bi bi-boxes me-1" aria-hidden="true"></i> 4. Para sa product na Set</h6>
                            <ul>
                                <li>Kapag <span class="manual-term">Set</span> ang Unit of Measurement, hindi na kailangan ang Capacity at Capacity Unit.</li>
                                <li>Sa <span class="manual-tab">Set Components</span>, ilista ang mga component product at dami ng bawat isa sa isang set.</li>
                                <li>Ang component list ay talaan ng laman ng set; hindi nito awtomatikong binabago ang inventory kapag naibenta ang set.</li>
                            </ul>
                        </section>
                    </div>

                    <div class="col-12 col-md-6">
                        <section class="manual-section" aria-labelledby="manual-fuel-heading">
                            <h6 id="manual-fuel-heading"><i class="bi bi-fuel-pump me-1" aria-hidden="true"></i> 5. Para sa Fuel products</h6>
                            <p class="mb-0">Kapag <span class="manual-term">Fuel</span> ang category, gamitin ang <span class="manual-tab">Tank</span> at <span class="manual-tab">Pump</span> tabs para idagdag o i-manage ang kaugnay na equipment. Hindi ipinapakita ang mga tab na ito para sa ibang category.</p>
                        </section>
                    </div>

                    <div class="col-12 col-md-6">
                        <section class="manual-section" aria-labelledby="manual-photo-heading">
                            <h6 id="manual-photo-heading"><i class="bi bi-image me-1" aria-hidden="true"></i> 6. Larawan at QR Code</h6>
                            <ul>
                                <li>Pindutin ang product thumbnail para makita ang mas malaking preview ng larawan.</li>
                                <li>Pindutin ang QR Code para palakihin, o i-scan ito gamit ang camera ng telepono para makita ang product price page.</li>
                            </ul>
                        </section>
                    </div>

                    <div class="col-12 col-md-6">
                        <section class="manual-section" aria-labelledby="manual-filter-heading">
                            <h6 id="manual-filter-heading"><i class="bi bi-funnel me-1" aria-hidden="true"></i> 7. Search at Product Categories</h6>
                            <ul>
                                <li>Gamitin ang search, category filter, at Rows to Display sa ilalim ng bawat table. Pindutin ang <span class="manual-term">Reset Filters</span> para ibalik ang default.</li>
                                <li>Makikita ng Admin at SUAdmin ang <span class="manual-tab">Product Categories</span> tab para gumawa, mag-edit, o magtanggal ng category.</li>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>

            <div class="modal-footer px-4 py-3" style="background:#f7f8f5;">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Got it
                </button>
            </div>
        </div>
    </div>
</div>
