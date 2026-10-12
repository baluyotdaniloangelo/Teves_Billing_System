<script>
$(function () {
    loadDipstickFuelProducts();
    loadDipstickInventory();

    $('#add_dipstick_inventory').on('click', function () {
        setDipstickInventoryFormMode('add');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('dipstickInventoryModal')).show();
    });

    $('#reset_dipstick_inventory_form').on('click', function () {
        const currentId = $('#dipstick_inventory_id').val() || '0';
        resetDipstickInventoryFields(currentId);
    });

    $('#product_idx_dipstick_inventory').on('change', function () {
        $('#product_tank_idx_dipstick_inventory').val('');
        loadDipstickInventoryTanks();
    });

    $('#dipstickInventoryModal').on('input change', 'input[type="number"]', calculateDipstickInventory);
    $('#dipstickInventoryModal').on('input change', 'input', function () {
        clearDipstickInventoryError(this.id);
    });

    $('#save-dipstick_inventory').on('click', saveDipstickInventory);
    $('#dipstickInventoryModal').on('hidden.bs.modal', function () {
        setDipstickInventoryFormMode('add');
    });

    $(document).on('click.dipstickInventory', '#table_product_dipstick_inventory .dipstick-inventory-edit', function () {
        editDipstickInventory($(this).data('id'));
    });
    $(document).on('click.dipstickInventory', '#table_product_dipstick_inventory .dipstick-inventory-delete', function () {
        confirmDeleteDipstickInventory($(this).data('id'));
    });
    $('#delete_dipstick_inventory_confirmed').on('click', deleteDipstickInventory);
});

let DipstickFuelProductsLoaded = false;
let DipstickFuelProductsRequest = null;

function loadDipstickFuelProducts()
{
    if (DipstickFuelProductsLoaded) {
        return $.Deferred().resolve().promise();
    }

    if (DipstickFuelProductsRequest) {
        return DipstickFuelProductsRequest;
    }

    const productInput = $('#product_idx_dipstick_inventory');
    productInput.prop('disabled', true);

    DipstickFuelProductsRequest = $.ajax({
        url: "{{ route('GetFuelSalesProducts') }}",
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}" }
    }).done(function (products) {
        const productList = $('#product_list_inventory').empty();
        const hasProducts = Array.isArray(products) && products.length > 0;

        if (Array.isArray(products)) {
            products.forEach(function (product) {
                productList.append($('<option>')
                    .attr('data-id', product.product_id)
                    .val(product.product_name));
            });
        }

        DipstickFuelProductsLoaded = true;
        productInput
            .prop('disabled', false)
            .attr('placeholder', hasProducts
                ? 'Select Fuel product'
                : 'No Fuel products available');
    }).fail(function (xhr) {
        DipstickFuelProductsRequest = null;
        productInput
            .prop('disabled', true)
            .attr('placeholder', 'Unable to load Fuel products');

        const message = xhr.responseJSON?.message ||
            'Unable to load Fuel products for Dipstick Inventory.';

        if (typeof showFuelSalesValidationModal === 'function') {
            showFuelSalesValidationModal(message);
        } else if (typeof showValidationErrorModal === 'function') {
            showValidationErrorModal(message);
        }
    });

    return DipstickFuelProductsRequest;
}

function loadDipstickInventory()
{
    const table = $('#table_product_dipstick_inventory');

    if ($.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
        table.DataTable().ajax.reload(null, false);
        return;
    }

    table.DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: true,
        info: true,
        stateSave: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('cashiers_report_dipstick_inventory.list') }}",
            type: 'POST',
            data: function (request) {
                request.CashiersReportId = {{ $CashiersReportId }};
                request._token = "{{ csrf_token() }}";
            },
            dataSrc: function (response) {
                if (Array.isArray(response)) {
                    return response;
                }
                return response && Array.isArray(response.data) ? response.data : [];
            },
            error: function (xhr) {
                console.error('Could not load Dipstick Inventory.', xhr);
            }
        },
        columns: [
            { data: 'product_name', render: $.fn.dataTable.render.text() },
            { data: 'tank_name', render: $.fn.dataTable.render.text() },
            { data: 'tank_capacity', render: $.fn.dataTable.render.text() },
            { data: 'beginning_inventory', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            { data: 'sales_in_liters', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            { data: 'ugt_pumping', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            { data: 'delivery', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            { data: 'ending_inventory', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            { data: 'book_stock', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            { data: 'variance', className: 'text-end', render: function (value, type) { return type === 'display' ? formatDipstickValue(value) : value; } },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center text-nowrap',
                render: function (data, type, item) {
                    if (type !== 'display') {
                        return '';
                    }

                    const itemId = Number.parseInt(item.dipstick_inventory_id, 10) || 0;
                    return '<div class="text-center text-nowrap">' +
                        '<button type="button" class="btn btn-sm btn-primary dipstick-inventory-edit" data-id="' + itemId + '" title="Edit" aria-label="Edit Dipstick Inventory">' +
                            '<i class="bi bi-pencil-square"></i>' +
                        '</button> ' +
                        '<button type="button" class="btn btn-sm btn-danger dipstick-inventory-delete" data-id="' + itemId + '" title="Delete" aria-label="Delete Dipstick Inventory">' +
                            '<i class="bi bi-trash3-fill"></i>' +
                        '</button>' +
                    '</div>';
                }
            }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        order: [],
        columnDefs: [{ targets: 10, orderable: false, searchable: false }]
    });
}
function formatDipstickValue(value)
{
    const number = Number.parseFloat(value);
    return Number.isFinite(number)
        ? number.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        : '0.00';
}

function resolveDipstickOptionId(listId, value)
{
    const option = Array.from(document.querySelectorAll('#' + listId + ' option'))
        .find(function (item) { return item.value === value; });
    return option ? option.dataset.id : '';
}

function loadDipstickInventoryTanks(selectedTank = '')
{
    if (!DipstickFuelProductsLoaded) {
        loadDipstickFuelProducts().done(function () {
            loadDipstickInventoryTanks(selectedTank);
        });
        return;
    }

    const productName = $('#product_idx_dipstick_inventory').val();
    const productId = resolveDipstickOptionId('product_list_inventory', productName);
    const tankList = $('#product_tank_list');
    const branchId = {{ $CashiersReportData[0]['teves_branch'] }};

    tankList.empty();
    if (!productId || !branchId) return;

    $.ajax({
        url: "{{ route('ProductTankPerBranch') }}",
        type: 'POST',
        data: {
            branchID: branchId,
            productID: productId,
            _token: "{{ csrf_token() }}"
        },
        success: function (tanks) {
            (tanks || []).forEach(function (tank) {
                tankList.append($('<option>')
                    .attr('data-id', tank.tank_id)
                    .val(tank.tank_name));
            });
            if (selectedTank) $('#product_tank_idx_dipstick_inventory').val(selectedTank);
        },
        error: function (xhr) {
            console.error('Could not load tanks for Dipstick Inventory.', xhr);
        }
    });
}

function setDipstickInventoryFormMode(mode, id = 0)
{
    const isEdit = mode === 'edit';
    clearDipstickInventoryFields(id || 0);
    $('#dipstickInventoryModalTitle').text(isEdit ? 'Edit Dipstick Inventory' : 'Add Dipstick Inventory');
    $('#dipstick_inventory_submit_label').text(isEdit ? 'Update Item' : 'Save Item');
    $('#reset_dipstick_inventory_form').toggle(!isEdit);
    $('#product_tank_list').empty();
}

function resetDipstickInventoryFields(id)
{
    clearDipstickInventoryFields(id);
}

function clearDipstickInventoryFields(id = 0)
{
    $('#product_idx_dipstick_inventory, #product_tank_idx_dipstick_inventory').val('');
    $('#beginning_dipstick_inventory, #sales_in_liters_dipstick_inventory, #ugt_pumping_dipstick_inventory, #delivery_dipstick_inventory, #ending_dipstick_inventory').val('');
    $('#dipstick_inventory_id').val(id);
    $('#product_tank_list').empty();
    clearAllDipstickInventoryErrors();
    calculateDipstickInventory();
}

function calculateDipstickInventory()
{
    const beginning = Number.parseFloat($('#beginning_dipstick_inventory').val()) || 0;
    const sales = Number.parseFloat($('#sales_in_liters_dipstick_inventory').val()) || 0;
    const pumping = Number.parseFloat($('#ugt_pumping_dipstick_inventory').val()) || 0;
    const delivery = Number.parseFloat($('#delivery_dipstick_inventory').val()) || 0;
    const ending = Number.parseFloat($('#ending_dipstick_inventory').val()) || 0;
    const bookStock = beginning - sales - pumping + delivery;

    $('#TotalBookStock_dipstick_inventory').text(formatDipstickValue(bookStock));
    // Preserve the current browser-side formula; the server currently stores Ending - Book Stock.
    $('#TotalVariance_dipstick_inventory').text(formatDipstickValue(bookStock - ending));
}

function clearDipstickInventoryError(inputId)
{
    $('#' + inputId).removeClass('is-invalid');
    $('#' + inputId + 'Error')
        .text('')
        .removeClass('d-block');
}

function clearAllDipstickInventoryErrors()
{
    $('#dipstickInventoryModal .is-invalid').removeClass('is-invalid');
    $('#dipstickInventoryModal .invalid-feedback')
        .text('')
        .removeClass('d-block');
}

function showDipstickInventoryValidationModal(message)
{
    if (typeof showFuelSalesValidationModal === 'function') {
        showFuelSalesValidationModal(message);
    } else if (typeof showValidationErrorModal === 'function') {
        showValidationErrorModal(message);
    } else {
        window.alert(message);
    }
}

function showDipstickInventoryErrors(errors)
{
    const fieldMap = {
        product_idx: 'product_idx_dipstick_inventory',
        tank_idx: 'product_tank_idx_dipstick_inventory',
        beginning_inventory: 'beginning_dipstick_inventory',
        sales_in_liters_inventory: 'sales_in_liters_dipstick_inventory',
        ugt_pumping_inventory: 'ugt_pumping_dipstick_inventory',
        delivery_inventory: 'delivery_dipstick_inventory',
        ending_inventory: 'ending_dipstick_inventory'
    };

    let firstError = '';

    Object.keys(fieldMap).forEach(function (key) {
        if (errors[key]) {
            const message = Array.isArray(errors[key])
                ? errors[key][0]
                : errors[key];
            const input = $('#' + fieldMap[key]);
            input.addClass('is-invalid');
            $('#' + fieldMap[key] + 'Error')
                .text(message)
                .addClass('d-block');

            if (!firstError) firstError = message;
        }
    });

    if (!firstError) {
        firstError = 'Please check the Dipstick Inventory fields.';
    }

    showDipstickInventoryValidationModal(firstError);
}

function saveDipstickInventory(event)
{
    event.preventDefault();
    clearAllDipstickInventoryErrors();

    const productName = $('#product_idx_dipstick_inventory').val();
    const tankName = $('#product_tank_idx_dipstick_inventory').val();
    const productId = resolveDipstickOptionId('product_list_inventory', productName);
    const tankId = resolveDipstickOptionId('product_tank_list', tankName);

    if (!productId) {
        showDipstickInventoryErrors({ product_idx: ['Select a valid product.'] });
        return;
    }
    if (!tankId) {
        showDipstickInventoryErrors({ tank_idx: ['Select a valid tank.'] });
        return;
    }

    const requestData = {
        dipstick_inventory_id: $('#dipstick_inventory_id').val() || 0,
        CashiersReportId: {{ $CashiersReportId }},
        product_idx: productId,
        tank_idx: tankId,
        beginning_inventory: $('#beginning_dipstick_inventory').val(),
        sales_in_liters_inventory: $('#sales_in_liters_dipstick_inventory').val(),
        ugt_pumping_inventory: $('#ugt_pumping_dipstick_inventory').val(),
        delivery_inventory: $('#delivery_dipstick_inventory').val(),
        ending_inventory: $('#ending_dipstick_inventory').val(),
        _token: "{{ csrf_token() }}"
    };

    $.ajax({
        url: "{{ route('cashiers_report_dipstick_inventory.save') }}",
        type: 'POST',
        data: requestData,
        success: function (response) {
            // Show success as a modal instead of an inline notice.
            const successMessage =
                (response && response.success) ||
                'Dipstick Inventory saved successfully.';

            // Clear the fields and return the form to Add mode,
            // but keep dipstickInventoryModal open.
            setDipstickInventoryFormMode('add');

            if (typeof showSuccessModal === 'function') {
                showSuccessModal(successMessage);
            } else {
                window.alert(successMessage);
            }

            // Refresh the table without closing the form modal.
            loadDipstickInventory();
        },
        error: function (xhr) {
            const errors = xhr.responseJSON && xhr.responseJSON.errors;
            if (errors) showDipstickInventoryErrors(errors);
            else {
                console.error('Could not save Dipstick Inventory.', xhr);
                showDipstickInventoryValidationModal(
                    (xhr.responseJSON && xhr.responseJSON.message) ||
                    'Could not save Dipstick Inventory.'
                );
            }
        }
    });
}

function editDipstickInventory(id)
{
    $.ajax({
        url: "{{ route('cashiers_report_dipstick_inventory.info') }}",
        type: 'POST',
        data: { dipstick_inventory_id: id, _token: "{{ csrf_token() }}" },
        success: function (response) {
            if (!response || !response.length) return;
            const item = response[0];

            setDipstickInventoryFormMode('edit', item.dipstick_inventory_id);
            $('#product_idx_dipstick_inventory').val(item.product_name);
            $('#beginning_dipstick_inventory').val(item.beginning_inventory);
            $('#sales_in_liters_dipstick_inventory').val(item.sales_in_liters);
            $('#ugt_pumping_dipstick_inventory').val(item.ugt_pumping);
            $('#delivery_dipstick_inventory').val(item.delivery);
            $('#ending_dipstick_inventory').val(item.ending_inventory);
            calculateDipstickInventory();

            bootstrap.Modal.getOrCreateInstance(document.getElementById('dipstickInventoryModal')).show();
            loadDipstickInventoryTanks(item.tank_name);
        },
        error: function (xhr) {
            console.error('Could not load Dipstick Inventory for editing.', xhr);
            showDipstickInventoryValidationModal(
                (xhr.responseJSON && xhr.responseJSON.message) ||
                'Could not load Dipstick Inventory for editing.'
            );
        }
    });
}

function confirmDeleteDipstickInventory(id)
{
    $.ajax({
        url: "{{ route('cashiers_report_dipstick_inventory.info') }}",
        type: 'POST',
        data: { dipstick_inventory_id: id, _token: "{{ csrf_token() }}" },
        success: function (response) {
            if (!response || !response.length) return;
            const item = response[0];
            $('#delete_dipstick_inventory_confirmed').val(item.dipstick_inventory_id);
            $('#delete_product_idx_dipstick_inventory').text(item.product_name || '');
            $('#delete_product_tank_idx_dipstick_inventory').text(item.tank_name || '');
            $('#delete_ending_dipstick_inventory').text(formatDipstickValue(item.ending_inventory));
            $('#dipstick_inventory_delete_TotalBookStock').text(formatDipstickValue(item.book_stock));
            $('#dipstick_inventory_delete_TotalVariance').text(formatDipstickValue(item.variance));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('dipstick_inventoryDeleteModal')).show();
        },
        error: function (xhr) {
            console.error('Could not load Dipstick Inventory details.', xhr);
            showDipstickInventoryValidationModal(
                (xhr.responseJSON && xhr.responseJSON.message) ||
                'Could not load Dipstick Inventory details.'
            );
        }
    });
}

function deleteDipstickInventory()
{
    const id = $('#delete_dipstick_inventory_confirmed').val();
    if (!id || id === '0') return;

    $.ajax({
        url: "{{ route('cashiers_report_dipstick_inventory.delete') }}",
        type: 'POST',
        data: { dipstick_inventory_id: id, _token: "{{ csrf_token() }}" },
        success: function (response) {
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('dipstick_inventoryDeleteModal')
            ).hide();

            // Show delete success as a modal instead of an inline notice.
            const successMessage =
                (response && response.success) ||
                'Dipstick Inventory deleted successfully.';

            if (typeof showSuccessModal === 'function') {
                showSuccessModal(successMessage);
            } else {
                window.alert(successMessage);
            }

            loadDipstickInventory();
        },
        error: function (xhr) {
            console.error('Could not delete Dipstick Inventory.', xhr);
            showDipstickInventoryValidationModal(
                (xhr.responseJSON && xhr.responseJSON.message) ||
                'Could not delete Dipstick Inventory.'
            );
        }
    });
}
</script>
