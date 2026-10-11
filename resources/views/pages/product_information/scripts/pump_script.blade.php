<script>
function LoadProductPump() {
    const productId = getSelectedProductId();
    if (!productId) return;

    $.ajax({
        url: "{{ route('ProductPump') }}",
        type: 'POST',
        data: { productID: productId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!window.ProductPumpListTable) return;
        window.ProductPumpListTable.clear().rows.add(response.data || []).draw();
    });
}

window.ProductPumpListTable = $('#ProductPumpListTable').DataTable({
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
        { data: 'pump_name' },
        { data: 'initial_reading', orderable: false, render: $.fn.dataTable.render.number(',', '.', 2, ''), className: 'text-end' },
        { data: 'product_unit_measurement' },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ]
});

function showProductSettingsSuccess(message, modal) {
    showProductSuccessAfterModalClose(modal, message || 'Changes saved successfully.');
}

function resetPumpForm() {
    const form = document.getElementById('AddProductPump');
    if (form) form.reset();
}

$(document).on('click', '[data-product-modal-target="#AddProductPumpModal"]', resetPumpForm);

$('#AddProductPump').on('submit', function (event) {
    event.preventDefault();
    const form = this;
    const saveButton = document.getElementById('save-pump');
    if (!window.validateProductSettingsForm(form)) return;
    if (!getSelectedProductId()) {
        showProductValidationErrorModal('Select a product before adding a pump.');
        return;
    }

    const originalButton = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: '/create_pump_post',
        type: 'POST',
        data: {
            product_idx: getSelectedProductId(),
            pump_name: $('#pump_name').val().trim(),
            initial_reading: $('#initial_reading').val(),
            branch_idx: $('#branch_idx_pump').val(),
            _token: "{{ csrf_token() }}"
        }
    }).done(function (response) {
        resetPumpForm();
        LoadProductPump();
        showProductSettingsSuccess(response.success || 'Pump saved successfully.', document.getElementById('AddProductPumpModal'));
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to save the pump. Review the details and try again.', form);
    }).always(function () {
        saveButton.disabled = false;
        saveButton.innerHTML = originalButton;
    });
});

$(document).on('click', '#ProductPump_Edit', function (event) {
    event.preventDefault();
    const pumpId = $(this).data('id');

    $.ajax({
        url: '/product_pump_info',
        type: 'POST',
        data: { pumpID: pumpId, _token: "{{ csrf_token() }}" }
    }).done(function (pump) {
        if (!pump) return;
        $('#UpdateProductPump')[0].reset();
        $('#update-pump').val(pumpId);
        $('#update_pump_name').val(pump.pump_name || '');
        $('#update_initial_reading').val(pump.initial_reading || '');
        $('#update_branch_idx_pump').val(pump.branch_idx);
        showProductInformationChildModal('#UpdateProductPumpModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this pump. Please try again.');
    });
});

$('#UpdateProductPump').on('submit', function (event) {
    event.preventDefault();
    const form = this;
    const saveButton = document.getElementById('update-pump');
    if (!window.validateProductSettingsForm(form)) return;
    if (!getSelectedProductId()) {
        showProductValidationErrorModal('Select a product before updating a pump.');
        return;
    }

    const originalButton = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: '/update_pump_post',
        type: 'POST',
        data: {
            pump_id: saveButton.value,
            product_idx: getSelectedProductId(),
            pump_name: $('#update_pump_name').val().trim(),
            initial_reading: $('#update_initial_reading').val(),
            branch_idx: $('#update_branch_idx_pump').val(),
            _token: "{{ csrf_token() }}"
        }
    }).done(function (response) {
        form.reset();
        LoadProductPump();
        showProductSettingsSuccess(response.success || 'Pump updated successfully.', document.getElementById('UpdateProductPumpModal'));
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to update the pump. Review the details and try again.', form);
    }).always(function () {
        saveButton.disabled = false;
        saveButton.innerHTML = originalButton;
    });
});

$(document).on('click', '#ProductPump_delete', function (event) {
    event.preventDefault();
    const pumpId = $(this).data('id');

    $.ajax({
        url: '/product_pump_info',
        type: 'POST',
        data: { pumpID: pumpId, _token: "{{ csrf_token() }}" }
    }).done(function (pump) {
        if (!pump) return;
        $('#delete_pump_name').text(pump.pump_name || '');
        $('#delete_pump_name_branch').text(pump.branch_code || '');
        $('#deletePumpInfoConfirmed').val(pumpId);
        showProductInformationChildModal('#PumpInfoDeleteModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this pump. Please try again.');
    });
});

$(document).on('click', '#deletePumpInfoConfirmed', function (event) {
    event.preventDefault();
    const pumpId = this.value;

    $.ajax({
        url: '/delete_product_pump_confirmed',
        type: 'POST',
        data: { pumpID: pumpId, _token: "{{ csrf_token() }}" }
    }).done(function () {
        LoadProductPump();
        showProductSettingsSuccess('Pump deleted.', document.getElementById('PumpInfoDeleteModal'));
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to delete this pump. Please try again.');
    });
});
</script>
