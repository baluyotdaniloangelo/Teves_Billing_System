<div class="modal fade so-modal" id="CreateSalesOrderModal" tabindex="-1" aria-labelledby="so-create-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="SalesOrderformNew" class="needs-validation">
                <div class="modal-header">
                    <div>
                        <p class="so-modal-eyebrow">SHIP TO / NEW RECORD</p>
                        <h2 class="modal-title" id="so-create-title">Create Quotation</h2>
                        <p class="so-modal-subtitle">Enter the client and order terms. Add items after saving.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <section class="so-form-section">
                        <div class="so-section-heading"><span>01</span><div><h3>Order details</h3><p>Type, branch, and document date</p></div></div>
                        <div class="so-form-grid">
                            <div class="so-field"><label for="sales_order_type">Order type <em>*</em></label>
                                <select class="form-select" name="sales_order_type" id="sales_order_type" required>
                                    <option value="">Select order type</option>
                                    @foreach ($product_category_data as $item)
                                        <option value="{{ $item->category_id }}">{{ $item->category_name }}</option>
                                    @endforeach
                                </select><span class="invalid-feedback" id="sales_order_typeError"></span>
                            </div>
                            <div class="so-field"><label for="company_header">Branch <em>*</em></label>
                                <select class="form-select" name="company_header" id="company_header" required>
                                    @foreach ($teves_branch as $branch)
                                        <option value="{{ $branch->branch_id }}">{{ $branch->branch_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="so-field"><label for="sales_order_date">Date <em>*</em></label>
                                <input type="date" class="form-control" id="sales_order_date" name="sales_order_date" value="{{ date('Y-m-d') }}" required>
                                <span class="invalid-feedback" id="sales_order_dateError"></span>
                            </div>
                        </div>
                    </section>
                    <section class="so-form-section">
                        <div class="so-section-heading"><span>02</span><div><h3>Sold To</h3><p>Choose the client for this record</p></div></div>
                        <div class="so-form-grid">
                            <div class="so-field so-field-wide"><label for="client_id">Client <em>*</em></label>
                                <input class="form-control" list="client_name" name="client_name" id="client_id" placeholder="Search client name" required autocomplete="off" onChange="ClientInfo()">
                                <datalist id="client_name">
                                    @foreach ($client_data as $client)
                                        <option label="{{ $client->client_name }}" data-id="{{ $client->client_id }}" value="{{ $client->client_name }}">
                                    @endforeach
                                </datalist>
                                <span class="invalid-feedback" id="client_idxError"></span>
                            </div>
                        </div>
                    </section>
                    <section class="so-form-section">
                        <div class="so-section-heading"><span>03</span><div><h3>Payment terms</h3><p>Set invoicing and tax details</p></div></div>
                        <div class="so-form-grid">
                            <div class="so-field"><label for="sales_order_payment_type">Payment type</label>
                                <select class="form-select" name="sales_order_payment_type" id="sales_order_payment_type"><option value="Receivable">Receivable</option><option value="PBD">Paid Before Delivery</option></select>
                            </div>
                            <div class="so-field"><label for="sales_order_invoice">Sales invoice</label>
                                <select class="form-select" name="sales_order_invoice" id="sales_order_invoice" onchange="check_withholding_tax()"><option value="1" selected>Yes</option><option value="0">No</option></select>
                            </div>
                            <div class="so-field"><label for="sales_order_net_percentage">Net value</label><input type="number" class="form-control" id="sales_order_net_percentage" name="sales_order_net_percentage" disabled></div>
                            <div class="so-field"><label for="sales_order_withholding_tax">Withholding tax</label><input type="number" class="form-control" id="sales_order_withholding_tax" name="sales_order_withholding_tax" disabled></div>
                            <div class="so-field so-field-wide"><label for="payment_term">Payment term</label><input type="text" class="form-control" id="payment_term" name="payment_term" placeholder="e.g. Net 30 days"></div>
                        </div>
                    </section>
                    <section class="so-form-section so-form-section-last">
                        <div class="so-section-heading"><span>04</span><div><h3>Document options</h3><p>Quotations need a client decision before becoming sales orders</p></div></div>
                        <label class="so-switch-row" for="sales_order_quotation"><span><strong>Create as quotation</strong><small>Show this record in the Quotation tab</small></span><input class="form-check-input sales_order_quotation" type="checkbox" id="sales_order_quotation" name="sales_order_quotation" checked></label>
                        <label class="so-switch-row" for="sales_order_quotation_hide_volume"><span><strong>Hide volume</strong><small>Keep volume off the quotation printout</small></span><input class="form-check-input sales_order_quotation_hide_volume" type="checkbox" id="sales_order_quotation_hide_volume" name="sales_order_quotation_hide_volume"></label>
                    </section>
                </div>
                <div class="modal-footer">
                    <div id="loading_data" style="display:none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...</div>
                    <button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="so-btn-primary" id="save-sales-order"><i class="bi bi-check2"></i> Save record</button>
                </div>
            </form>
        </div>
    </div>
</div>
