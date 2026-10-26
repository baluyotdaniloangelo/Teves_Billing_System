@php
    $editing = $editing ?? false;
    $dialogId = $editing ? 'so-editor-EditProductModal' : 'so-editor-AddProductModal';
    $formId = $editing ? 'so-editor-EditSalesOrderComponentProduct' : 'so-editor-AddProduct';
    $inputId = $editing ? 'so-editor-edit_product_idx' : 'so-editor-product_idx';
    $priceId = $editing ? 'so-editor-edit_product_manual_price' : 'so-editor-product_manual_price';
    $quantityId = $editing ? 'so-editor-edit_order_quantity' : 'so-editor-order_quantity';
    $submitId = $editing ? 'so-editor-update-product' : 'so-editor-save-product';
@endphp
<div class="modal fade so-editor-entry" id="{{ $dialogId }}" tabindex="-1" aria-labelledby="{{ $dialogId }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <form id="{{ $formId }}" data-so-editor-form="product" data-editing="{{ $editing ? '1' : '0' }}" action="{{ route('SalesOrderComponentCompose') }}" method="post" novalidate>
                @csrf
                <div class="modal-header">
                    <div class="so-entry-heading">
                        <span class="so-entry-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
                        <div><p class="so-modal-eyebrow">SALES ORDER / PRODUCT</p><h2 class="modal-title" id="{{ $dialogId }}-title">{{ $editing ? 'Edit product' : 'Add product' }}</h2><p class="so-modal-subtitle">Set the product, price, and quantity for this order.</p></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close product form"></button>
                </div>
                <div class="modal-body">
                    <div class="so-form-alert" data-form-alert role="alert" hidden></div>
                    <div class="so-form-grid">
                        <div class="so-field so-field-wide">
                            <label for="{{ $inputId }}">Product or item description <em>*</em></label>
                            <input class="form-control" list="so-editor-product_list" id="{{ $inputId }}" name="{{ $editing ? 'edit_product_name' : 'product_name' }}" data-product-input data-errors="item_description product_idx" aria-describedby="{{ $inputId }}Error" placeholder="Search a product or enter an item description" required autocomplete="off">
                            <span class="invalid-feedback" id="{{ $inputId }}Error"></span>
                            <small class="so-field-hint" data-product-price>Client pricing is used unless you enter a custom price.</small>
                        </div>
                        <div class="so-field">
                            <label for="{{ $priceId }}">Custom unit price <span class="so-label-optional">Optional</span></label>
                            <div class="so-input-money"><span>₱</span><input type="number" class="form-control" id="{{ $priceId }}" name="{{ $editing ? 'edit_product_manual_price' : 'product_manual_price' }}" data-product-price-input data-errors="product_manual_price" aria-describedby="{{ $priceId }}Error" min="0" step="0.01" placeholder="Use client price"></div>
                            <span class="invalid-feedback" id="{{ $priceId }}Error"></span>
                        </div>
                        <div class="so-field">
                            <label for="{{ $quantityId }}">Quantity <em>*</em></label>
                            <input type="number" class="form-control" id="{{ $quantityId }}" name="{{ $editing ? 'edit_order_quantity' : 'order_quantity' }}" data-product-quantity data-errors="order_quantity" aria-describedby="{{ $quantityId }}Error" min="0.01" step="0.01" placeholder="0.00" required>
                            <span class="invalid-feedback" id="{{ $quantityId }}Error"></span>
                        </div>
                    </div>
                    <div class="so-entry-total">
                        <div><span>Line amount</span><small>Unit price × quantity, before order tax adjustments</small></div>
                        <strong>₱ <span data-product-total id="{{ $editing ? 'so-editor-UpdateTotalAmount' : 'so-editor-TotalAmount' }}" aria-live="polite">0.00</span></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="so-entry-saving" data-submit-status role="status" hidden><span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Saving product…</span>
                    <button type="button" class="so-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="reset" class="so-btn-secondary">Reset changes</button>
                    <button type="submit" class="so-btn-primary" id="{{ $submitId }}" value="0" data-record-id><i class="bi bi-check2" aria-hidden="true"></i> {{ $editing ? 'Save changes' : 'Add product' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
