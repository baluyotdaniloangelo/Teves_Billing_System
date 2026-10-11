<script>
function loadProductSellingPrice() {
    const productId = getSelectedProductId();
    if (!productId) return;

    $.ajax({
        url: "{{ route('ProductSellingPriceList') }}",
        type: 'POST',
        data: { productID: productId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!window.ProductPricePerClientListTable) return;
        window.ProductPricePerClientListTable.clear().rows.add(response.data || []).draw();
    });
}

window.ProductPricePerClientListTable = $('#ProductPricePerClientListTable').DataTable({
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
        { data: 'client_name' },
        { data: 'product_price', orderable: false, render: $.fn.dataTable.render.number(',', '.', 2, ''), className: 'text-end' },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ]
});

function resetSellingPriceForm() {
    const form = document.getElementById('ProductClientPrice');
    if (form) form.reset();
    $('#ProductPricePerClientModal_title').text('Add Selling Price');
    $('#save-selling-price').val('0').prop('disabled', false).html('<i class="bi bi-check-circle me-1" aria-hidden="true"></i>Save Selling Price');
}

$(document).on('click', '[data-product-modal-target="#ProductPricePerClientModal"]', resetSellingPriceForm);

$('#ProductClientPrice').on('submit', function (event) {
    event.preventDefault();
    const form = this;
    const saveButton = document.getElementById('save-selling-price');

    if (!window.validateProductSettingsForm(form)) return;

    const clientName = $('#client_idx_selling').val().trim();
    const clientId = $('#client_name_selling option').filter(function () { return this.value === clientName; }).attr('data-id');
    if (!clientId) {
        showProductValidationErrorModal('Choose a client from the suggestions before saving.');
        return;
    }
    if (!getSelectedProductId()) {
        showProductValidationErrorModal('Select a product before adding a selling price.');
        return;
    }

    const sellingPriceId = saveButton.value || '0';
    const originalButton = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: '/create_product_selling_price_post',
        type: 'POST',
        data: {
            product_idx: getSelectedProductId(),
            selling_price_id: sellingPriceId,
            branch_idx: $('#branch_idx_selling').val(),
            client_idx: clientId,
            selling_price: $('#selling_price').val(),
            _token: "{{ csrf_token() }}"
        }
    }).done(function (response) {
        resetSellingPriceForm();
        loadProductSellingPrice();
        showProductSuccessAfterModalClose(
            document.getElementById('ProductPricePerClientModal'),
            response.success || 'Selling price saved successfully.'
        );
    }).fail(function (xhr) {
        const errors = (xhr.responseJSON && xhr.responseJSON.errors) || {};
        if (errors.product_idx) {
            showProductValidationErrorModal('A selling price for this client and branch is already set for this product.');
            return;
        }
        window.showProductRequestError(xhr, 'Unable to save the selling price. Review the details and try again.', form);
    }).always(function () {
        saveButton.disabled = false;
        saveButton.innerHTML = originalButton;
    });
});

$(document).on('click', '#ProductSellingPrice_Edit', function (event) {
    event.preventDefault();
    const sellingPriceId = $(this).data('id');

    $.ajax({
        url: '/product_selling_price_info',
        type: 'POST',
        data: { selling_price_id: sellingPriceId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!response || !response[0]) return;
        const selling = response[0];
        $('#ProductClientPrice')[0].reset();
        $('#ProductPricePerClientModal_title').text('Edit Selling Price');
        $('#save-selling-price').val(sellingPriceId);
        $('#client_idx_selling').val(selling.client_name || '');
        $('#selling_price').val(selling.product_price || '');
        $('#branch_idx_selling').val(selling.branch_idx);
        showProductInformationChildModal('#ProductPricePerClientModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this selling price. Please try again.');
    });
});

$(document).on('click', '#ProductSellingPrice_delete', function (event) {
    event.preventDefault();
    const sellingPriceId = $(this).data('id');

    $.ajax({
        url: '/product_selling_price_info',
        type: 'POST',
        data: { selling_price_id: sellingPriceId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!response || !response[0]) return;
        const selling = response[0];
        $('#branch_client_selling_price_delete').text(selling.branch_code || '');
        $('#client_name_selling_price_delete').text(selling.client_name || '');
        $('#client_selling_price_delete').text(selling.product_price || '');
        $('#deleteSellingPriceInfoConfirmed').val(sellingPriceId);
        showProductInformationChildModal('#ProductSellingPriceInfoDeleteModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this selling price. Please try again.');
    });
});

$(document).on('click', '#deleteSellingPriceInfoConfirmed', function (event) {
    event.preventDefault();
    const sellingPriceId = this.value;

    $.ajax({
        url: '/delete_selling_price_info_confirmed',
        type: 'POST',
        data: { selling_price_id: sellingPriceId, _token: "{{ csrf_token() }}" }
    }).done(function () {
        loadProductSellingPrice();
        showProductSuccessAfterModalClose(
            document.getElementById('ProductSellingPriceInfoDeleteModal'),
            "Client's selling price deleted."
        );
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to delete this selling price. Please try again.');
    });
});
</script>
