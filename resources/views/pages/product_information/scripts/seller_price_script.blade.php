<script>
function loadPricingListPerSeller() {
    const productId = getSelectedProductId();
    if (!productId) return;

    $.ajax({
        url: "{{ route('ProductPricePerSellerList') }}",
        type: 'POST',
        data: { productID: productId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!window.ProductPricePerSellerListTable) return;
        window.ProductPricePerSellerListTable.clear().rows.add(response.data || []).draw();
    });
}

window.ProductPricePerSellerListTable = $('#ProductPricePerSellerListTable').DataTable({
    language: {
        lengthMenu: '<select class="dt-input"><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="-1">All</option></select>'
    },
    responsive: false,
    paging: true,
    searching: false,
    info: false,
    data: [],
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'branch_code' },
        { data: 'supplier_name' },
        { data: 'product_price', orderable: false, render: $.fn.dataTable.render.number(',', '.', 2, ''), className: 'text-end' },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ]
});

function resetSellerPriceForm() {
    const form = document.getElementById('ProductSellerPrice');
    if (form) form.reset();
    $('#ProductPricePerSellerModal_title').text('Add Seller Price');
    $('#save-seller-price').val('0').prop('disabled', false).html('<i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Seller Price');
}

$(document).on('click', '[data-product-modal-target="#ProductPricePerSellerModal"]', resetSellerPriceForm);

$('#ProductSellerPrice').on('submit', function (event) {
    event.preventDefault();
    const form = this;
    const saveButton = document.getElementById('save-seller-price');

    if (!window.validateProductSettingsForm(form)) return;

    const supplierName = $('#supplier_idx').val().trim();
    const supplierId = $('#supplier_name option').filter(function () { return this.value === supplierName; }).attr('data-id');
    if (!supplierId) {
        showProductValidationErrorModal('Choose a supplier from the suggestions before saving.');
        return;
    }
    if (!getSelectedProductId()) {
        showProductValidationErrorModal('Select a product before adding a seller price.');
        return;
    }

    const sellerPriceId = saveButton.value || '0';
    const originalButton = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: '/create_product_seller_price_post',
        type: 'POST',
        data: {
            product_idx: getSelectedProductId(),
            seller_price_id: sellerPriceId,
            branch_idx: $('#branch_idx_seller').val(),
            supplier_idx: supplierId,
            seller_price: $('#seller_price').val(),
            _token: "{{ csrf_token() }}"
        }
    }).done(function (response) {
        resetSellerPriceForm();
        loadPricingListPerSeller();
        showProductSuccessAfterModalClose(
            document.getElementById('ProductPricePerSellerModal'),
            response.success || 'Seller price saved successfully.'
        );
    }).fail(function (xhr) {
        const errors = (xhr.responseJSON && xhr.responseJSON.errors) || {};
        if (errors.product_idx) {
            showProductValidationErrorModal('A seller price for this supplier and branch is already set for this product.');
            return;
        }
        window.showProductRequestError(xhr, 'Unable to save the seller price. Review the details and try again.', form);
    }).always(function () {
        saveButton.disabled = false;
        saveButton.innerHTML = originalButton;
    });
});

$(document).on('click', '#ProductSellerPrice_Edit', function (event) {
    event.preventDefault();
    const sellerPriceId = $(this).data('id');

    $.ajax({
        url: '/product_price_per_seller_info',
        type: 'POST',
        data: { seller_price_id: sellerPriceId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!response || !response[0]) return;
        const seller = response[0];
        $('#ProductSellerPrice')[0].reset();
        $('#ProductPricePerSellerModal_title').text('Edit Seller Price');
        $('#save-seller-price').val(sellerPriceId);
        $('#supplier_idx').val(seller.supplier_name || '');
        $('#seller_price').val(seller.product_price || '');
        $('#branch_idx_seller').val(seller.branch_idx);
        showProductInformationChildModal('#ProductPricePerSellerModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this seller price. Please try again.');
    });
});

$(document).on('click', '#ProductSellerPrice_delete', function (event) {
    event.preventDefault();
    const sellerPriceId = $(this).data('id');

    $.ajax({
        url: '/product_price_per_seller_info',
        type: 'POST',
        data: { seller_price_id: sellerPriceId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!response || !response[0]) return;
        const seller = response[0];
        $('#branch_suppliers_price_delete').text(seller.branch_code || '');
        $('#suppliers_name_suppliers_price_delete').text(seller.supplier_name || '');
        $('#sellers_price_suppliers_price_delete').text(seller.product_price || '');
        $('#deleteSuppliersProductPriceInfoConfirmed').val(sellerPriceId);
        showProductInformationChildModal('#SuppliersProductPriceInfoDeleteModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this seller price. Please try again.');
    });
});

$(document).on('click', '#deleteSuppliersProductPriceInfoConfirmed', function (event) {
    event.preventDefault();
    const sellerPriceId = this.value;

    $.ajax({
        url: '/delete_product_price_per_seller_info_confirmed',
        type: 'POST',
        data: { seller_price_id: sellerPriceId, _token: "{{ csrf_token() }}" }
    }).done(function () {
        loadPricingListPerSeller();
        showProductSuccessAfterModalClose(
            document.getElementById('SuppliersProductPriceInfoDeleteModal'),
            "Seller's price deleted."
        );
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to delete this seller price. Please try again.');
    });
});
</script>
