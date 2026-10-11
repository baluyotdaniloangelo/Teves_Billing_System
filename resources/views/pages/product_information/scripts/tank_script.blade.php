<script>
function LoadProductTank() {
    const productId = getSelectedProductId();
    if (!productId) return;

    $.ajax({
        url: "{{ route('ProductTank') }}",
        type: 'POST',
        data: { productID: productId, _token: "{{ csrf_token() }}" }
    }).done(function (response) {
        if (!window.ProductTankListTable) return;
        window.ProductTankListTable.clear().rows.add(response.data || []).draw();
    });
}

if ($.fn.DataTable.isDataTable('#ProductTankListTable')) {
    window.ProductTankListTable = $('#ProductTankListTable').DataTable();
} else {
    window.ProductTankListTable = $('#ProductTankListTable').DataTable({
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
            { data: 'tank_name' },
            { data: 'tank_capacity', orderable: false, render: $.fn.dataTable.render.number(',', '.', 2, ''), className: 'text-end' },
            { data: 'product_unit_measurement' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });
}
		
function showTankSettingsSuccess(message, modal) {
    const successMessage = message || 'Tank details saved successfully.';

    if (modal && typeof showProductSuccessAfterModalClose === 'function') {
        showProductSuccessAfterModalClose(modal, successMessage);
        return;
    }

    showProductSuccessModal(successMessage);
}

$(document).on('click', '[data-product-modal-target="#AddProductTankModal"]', function () {
    const form = document.getElementById('AddProductTank');
    if (form) form.reset();
});

$('#AddProductTank').on('submit', function (event) {
    event.preventDefault();
    const form = this;
    const saveButton = document.getElementById('save-tank');
    if (!window.validateProductSettingsForm(form)) return;
    if (!getSelectedProductId()) {
        showProductValidationErrorModal('Select a product before adding a tank.');
        return;
    }

    const originalButton = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: '/create_tank_post',
        type: 'POST',
        data: {
            product_idx: getSelectedProductId(),
            tank_name: $('#tank_name').val().trim(),
            tank_capacity: $('#tank_capacity').val(),
            branch_idx: $('#branch_idx').val(),
            _token: "{{ csrf_token() }}"
        }
    }).done(function (response) {
        form.reset();
        LoadProductTank();
        showTankSettingsSuccess(response.success || 'Tank saved successfully.', document.getElementById('AddProductTankModal'));
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to save the tank. Review the details and try again.', form);
    }).always(function () {
        saveButton.disabled = false;
        saveButton.innerHTML = originalButton;
    });
});

$(document).on('click', '#ProductTank_Edit', function (event) {
    event.preventDefault();
    const tankId = $(this).data('id');

    $.ajax({
        url: '/product_tank_info',
        type: 'POST',
        data: { tankID: tankId, _token: "{{ csrf_token() }}" }
    }).done(function (tank) {
        if (!tank) return;
        $('#UpdateProductTank')[0].reset();
        $('#update-tank').val(tankId);
        $('#update_tank_name').val(tank.tank_name || '');
        $('#update_tank_capacity').val(tank.tank_capacity || '');
        $('#update_branch_idx').val(tank.branch_idx);
        showProductInformationChildModal('#UpdateProductTankModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this tank. Please try again.');
    });
});

$('#UpdateProductTank').on('submit', function (event) {
    event.preventDefault();
    const form = this;
    const saveButton = document.getElementById('update-tank');
    if (!window.validateProductSettingsForm(form)) return;
    if (!getSelectedProductId()) {
        showProductValidationErrorModal('Select a product before updating a tank.');
        return;
    }

    const originalButton = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: '/update_tank_post',
        type: 'POST',
        data: {
            tank_id: saveButton.value,
            product_idx: getSelectedProductId(),
            tank_name: $('#update_tank_name').val().trim(),
            tank_capacity: $('#update_tank_capacity').val(),
            branch_idx: $('#update_branch_idx').val(),
            _token: "{{ csrf_token() }}"
        }
    }).done(function (response) {
        form.reset();
        LoadProductTank();
        showTankSettingsSuccess(response.success || 'Tank updated successfully.', document.getElementById('UpdateProductTankModal'));
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to update the tank. Review the details and try again.', form);
    }).always(function () {
        saveButton.disabled = false;
        saveButton.innerHTML = originalButton;
    });
});

	$(document).on('click', '#ProductTank_delete', function (event) {
        event.preventDefault();
        const tankId = $(this).data('id');

        $.ajax({
            url: '/product_tank_info',
            type: 'POST',
            data: { tankID: tankId, _token: "{{ csrf_token() }}" }
        }).done(function (tank) {
            if (!tank) return;
            $('#delete_tank_name').text(tank.tank_name || '');
            $('#delete_tank_capacity').text(tank.tank_capacity || '');
            $('#deleteTankInfoConfirmed').val(tankId);
            showProductInformationChildModal('#TankInfoDeleteModal');
        }).fail(function (xhr) {
            window.showProductRequestError(xhr, 'Unable to load this tank. Please try again.');
        });
    });

    $(document).on('click', '#deleteTankInfoConfirmed', function (event) {
        event.preventDefault();
        const tankId = this.value;

        $.ajax({
            url: '/delete_product_tank_confirmed',
            type: 'POST',
            data: { tankID: tankId, _token: "{{ csrf_token() }}" }
        }).done(function () {
            LoadProductTank();
            showTankSettingsSuccess('Tank deleted.', document.getElementById('TankInfoDeleteModal'));
        }).fail(function (xhr) {
            window.showProductRequestError(xhr, 'Unable to delete this tank. Please try again.');
        });
    });
	  
	
  
</script>
