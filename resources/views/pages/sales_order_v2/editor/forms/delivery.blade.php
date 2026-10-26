<div class="modal fade so-editor-entry" id="so-editor-AddProductDeliveryModal" tabindex="-1" aria-labelledby="so-editor-delivery-form-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <form id="so-editor-AddProductDelivery" data-so-editor-form="delivery" action="{{ route('SalesOrderDeliveryCompose') }}" method="post" novalidate>
                @csrf
                <div class="modal-header">
                    <div class="so-entry-heading">
                        <span class="so-entry-icon"><i class="bi bi-truck" aria-hidden="true"></i></span>
                        <div><p class="so-modal-eyebrow">SALES ORDER / DELIVERY</p><h2 class="modal-title" id="so-editor-delivery-form-title" data-record-title data-create-title="Record delivery" data-edit-title="Edit delivery">Record delivery</h2><p class="so-modal-subtitle">Choose an order item and enter its delivery details.</p></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close delivery form"></button>
                </div>
                <div class="modal-body">
                    <div class="so-form-alert" data-form-alert role="alert" hidden></div>
                    <div class="so-form-grid">
                        <div class="so-field so-field-wide">
                            <label for="so-editor-sales_order_component_product_idx">Order product <em>*</em></label>
                            <input class="form-control" list="so-editor-product_list_delivery" name="sales_order_component_product_idx" id="so-editor-sales_order_component_product_idx" data-delivery-product data-errors="sales_order_component_product_idx sales_order_component_idx" aria-describedby="so-editor-sales_order_component_product_idxError" required autocomplete="off" placeholder="Select a product from this order">
                            <datalist id="so-editor-product_list_delivery"><span></span></datalist>
                            <span class="invalid-feedback" id="so-editor-sales_order_component_product_idxError"></span>
                            <small class="so-field-hint">Only products already added to this Sales Order are listed.</small>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-sales_order_delivery_date">Delivery date <em>*</em></label>
                            <input type="date" class="form-control" id="so-editor-sales_order_delivery_date" name="sales_order_delivery_date" data-errors="sales_order_delivery_date" aria-describedby="so-editor-sales_order_delivery_dateError" value="{{ date('Y-m-d') }}" required>
                            <span class="invalid-feedback" id="so-editor-sales_order_delivery_dateError"></span>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-sales_order_delivery_quantity">Delivered quantity <em>*</em></label>
                            <input type="number" class="form-control" id="so-editor-sales_order_delivery_quantity" name="sales_order_delivery_quantity" data-errors="sales_order_delivery_quantity" aria-describedby="so-editor-sales_order_delivery_quantityError" min="0.01" step="0.01" placeholder="0.00" required>
                            <span class="invalid-feedback" id="so-editor-sales_order_delivery_quantityError"></span>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-sales_order_delivery_withdrawal_reference">Delivery reference <span class="so-label-optional">Optional</span></label>
                            <input class="form-control" id="so-editor-sales_order_delivery_withdrawal_reference" name="sales_order_delivery_withdrawal_reference" data-errors="sales_order_delivery_withdrawal_reference" aria-describedby="so-editor-sales_order_delivery_withdrawal_referenceError" placeholder="DR or withdrawal reference">
                            <span class="invalid-feedback" id="so-editor-sales_order_delivery_withdrawal_referenceError"></span>
                        </div>
                        <div class="so-field">
                            <label for="so-editor-sales_order_delivery_hauler_details">Hauler details <span class="so-label-optional">Optional</span></label>
                            <input class="form-control" id="so-editor-sales_order_delivery_hauler_details" name="sales_order_delivery_hauler_details" data-errors="sales_order_delivery_hauler_details" aria-describedby="so-editor-sales_order_delivery_hauler_detailsError" placeholder="Hauler, driver, or vehicle">
                            <span class="invalid-feedback" id="so-editor-sales_order_delivery_hauler_detailsError"></span>
                        </div>
                        <div class="so-field so-field-wide">
                            <label for="so-editor-sales_order_delivery_remarks">Delivery notes <span class="so-label-optional">Optional</span></label>
                            <textarea class="form-control" id="so-editor-sales_order_delivery_remarks" name="sales_order_delivery_remarks" data-errors="sales_order_delivery_remarks" aria-describedby="so-editor-sales_order_delivery_remarksError" rows="3" placeholder="Receiver, destination, or delivery remarks"></textarea>
                            <span class="invalid-feedback" id="so-editor-sales_order_delivery_remarksError"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="so-entry-saving" data-submit-status role="status" hidden><span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Saving delivery…</span>
                    <button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="reset" class="so-btn-secondary">Reset changes</button>
                    <button type="submit" class="so-btn-primary" id="so-editor-save-product-delivery" value="0" data-record-id><i class="bi bi-check2" aria-hidden="true"></i> Save delivery</button>
                </div>
            </form>
        </div>
    </div>
</div>
