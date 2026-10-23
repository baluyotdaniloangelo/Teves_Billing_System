<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });

    initLpgInventoryTables();

    $(document).on('click', '.lpg-inventory-add', function () {
        openLpgInventoryModal($(this).data('inventory-mode'));
    });

    $('#clear-lpg-inventory').on('click', function () {
        openLpgInventoryModal($('#lpg_inventory_mode').val());
    });

    $('#CashierReportLpgInventoryForm').on('submit', function (event) {
        event.preventDefault();
        clearLpgInventoryErrors();

        const mode = $('#lpg_inventory_mode').val();
        const supplierRequired = ['DELIVERY', 'BO_REPLACEMENT'].includes(mode);
        $('#lpg_inventory_supplier_idx').prop('required', supplierRequired);

        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        const recordId = Number($('#cashiers_report_lpg_inventory_id').val() || 0);
        const url = recordId > 0
            ? "{{ route('UpdateLpgInventory') }}"
            : "{{ route('SaveLpgInventory') }}";

        const button = $('#save-lpg-inventory');
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                hideLpgInventoryModal('#CashierReportLpgInventoryModal');
                reloadLpgInventoryTables();
                if (typeof showSuccessModal === 'function') {
                    showSuccessModal(response.message || 'Inventory saved successfully.');
                }
            },
            error: function (xhr) {
                showLpgInventoryErrors(xhr.responseJSON?.errors || {});
            },
            complete: function () {
                button.prop('disabled', false).html('<i class="bi bi-save-fill me-2"></i>Save Inventory');
            }
        });
    });

    $(document).on('click', '.EditLpgInventory', function () {
        const id = $(this).data('id');
        $.post("{{ route('LpgInventoryInfo') }}", { cashiers_report_lpg_inventory_id: id })
            .done(function (response) {
                const record = response.data || response;
                openLpgInventoryModal(record.inventory_mode, record);
            })
            .fail(function () {
                if (typeof showValidationErrorModal === 'function') {
                    showValidationErrorModal('Unable to load the inventory record.');
                }
            });
    });

    $(document).on('click', '.DeleteLpgInventory', function () {
        $('#lpg_inventory_delete_id').val($(this).data('id'));
        $('#lpg_inventory_delete_item').text($(this).data('item') || 'this inventory record');
        showLpgInventoryModal('#CashierReportLpgInventoryDeleteModal');
    });

    $('#confirm-delete-lpg-inventory').on('click', function () {
        const id = $('#lpg_inventory_delete_id').val();
        const button = $(this);
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');

        $.post("{{ route('DeleteLpgInventory') }}", { cashiers_report_lpg_inventory_id: id })
            .done(function (response) {
                hideLpgInventoryModal('#CashierReportLpgInventoryDeleteModal');
                reloadLpgInventoryTables();
                if (typeof showSuccessModal === 'function') {
                    showSuccessModal(response.message || 'Inventory record deleted successfully.');
                }
            })
            .fail(function (xhr) {
                if (typeof showValidationErrorModal === 'function') {
                    showValidationErrorModal(xhr.responseJSON?.message || 'Unable to delete the inventory record.');
                }
            })
            .always(function () {
                button.prop('disabled', false).html('<i class="bi bi-trash3-fill me-2"></i>Delete');
            });
    });
});

let LpgBeginningInventoryTable;
let LpgDeliveryTable;
let LpgBoReplacementTable;

function initLpgInventoryTables() {
    LpgBeginningInventoryTable = createLpgInventoryTable('#LpgBeginningInventoryTable', 'BEGINNING', [
        { data: null, className: 'text-center', orderable: false, render: function (data, type, row, meta) { return meta.row + 1; } },
        { data: null, render: function (data, type, row) { return lpgInventoryProductLabel(row); } },
        { data: 'quantity', className: 'text-end fw-semibold', render: lpgInventoryQuantity },
        { data: 'remarks', defaultContent: '-' },
        lpgInventoryActionColumn()
    ]);

    LpgDeliveryTable = createLpgInventoryTable('#LpgDeliveryTable', 'DELIVERY', [
        { data: null, className: 'text-center', orderable: false, render: function (data, type, row, meta) { return meta.row + 1; } },
        { data: 'supplier_name', defaultContent: '-' },
        { data: 'delivery_reference_no', defaultContent: '-' },
        { data: null, render: function (data, type, row) { return lpgInventoryProductLabel(row); } },
        { data: 'quantity', className: 'text-end fw-semibold', render: lpgInventoryQuantity },
        { data: 'remarks', defaultContent: '-' },
        lpgInventoryActionColumn()
    ]);

    LpgBoReplacementTable = createLpgInventoryTable('#LpgBoReplacementTable', 'BO_REPLACEMENT', [
        { data: null, className: 'text-center', orderable: false, render: function (data, type, row, meta) { return meta.row + 1; } },
        { data: 'supplier_name', defaultContent: '-' },
        { data: 'delivery_reference_no', defaultContent: '-' },
        { data: null, render: function (data, type, row) { return lpgInventoryProductLabel(row); } },
        { data: 'quantity', className: 'text-end fw-semibold', render: lpgInventoryQuantity },
        { data: 'remarks', defaultContent: '-' },
        lpgInventoryActionColumn()
    ]);
}

function createLpgInventoryTable(selector, mode, columns) {
    if (!$(selector).length) return null;
    if ($.fn.DataTable.isDataTable(selector)) $(selector).DataTable().destroy();

    const table = $(selector).DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('GetLpgInventoryList') }}",
            type: 'POST',
            data: function (data) {
                data.CashiersReportId = {{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }};
                data.inventory_mode = mode;
            },
            dataSrc: function (response) { return Array.isArray(response) ? response : (response.data || []); }
        },
        columns: columns,
        order: [[1, 'asc']],
        language: { emptyTable: inventoryEmptyMessage(mode) }
    });

    if (typeof autoAdjustColumns === 'function') autoAdjustColumns(table);
    return table;
}

function lpgInventoryActionColumn() {
    return {
        data: null,
        orderable: false,
        searchable: false,
        className: 'text-center text-nowrap',
        render: function (data, type, row) {
            const item = lpgInventoryProductLabel(row).replace(/"/g, '&quot;');
            return `<button type="button" class="btn btn-sm btn-primary EditLpgInventory" data-id="${row.cashiers_report_lpg_inventory_id}" title="Edit"><i class="bi bi-pencil-square"></i></button>
                    <button type="button" class="btn btn-sm btn-danger DeleteLpgInventory" data-id="${row.cashiers_report_lpg_inventory_id}" data-item="${item}" title="Delete"><i class="bi bi-trash3-fill"></i></button>`;
        }
    };
}

function lpgInventoryProductLabel(row) {
    return [row.product_name, row.product_unit_measurement].filter(Boolean).join(' ') || '-';
}

function lpgInventoryQuantity(value) {
    return Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function inventoryEmptyMessage(mode) {
    if (mode === 'BEGINNING') return 'No beginning inventory recorded.';
    if (mode === 'BO_REPLACEMENT') return 'No B.O. replacements recorded.';
    return 'No LPG deliveries recorded.';
}

function openLpgInventoryModal(mode, record) {
    const form = document.getElementById('CashierReportLpgInventoryForm');
    form.reset();
    form.classList.remove('was-validated');
    clearLpgInventoryErrors();

    const requiresSupplier = ['DELIVERY', 'BO_REPLACEMENT'].includes(mode);
    const modeLabel = mode === 'BO_REPLACEMENT' ? 'B.O. Replacement' : (mode === 'DELIVERY' ? 'Delivery' : 'Beginning Inventory');
    $('#cashiers_report_lpg_inventory_id').val(record?.cashiers_report_lpg_inventory_id || 0);
    $('#lpg_inventory_mode').val(mode);
    $('#lpg_inventory_product_idx').val(record?.product_idx || '');
    $('#lpg_inventory_supplier_idx').val(record?.supplier_idx || '');
    $('#lpg_inventory_delivery_reference_no').val(record?.delivery_reference_no || '');
    $('#lpg_inventory_quantity').val(record?.quantity ?? '');
    $('#lpg_inventory_remarks').val(record?.remarks || '');
    $('.lpg-supplier-field').toggleClass('d-none', !requiresSupplier);
    $('#lpg_inventory_supplier_idx').prop('required', requiresSupplier);
    $('#CashierReportLpgInventoryModalLabel').text(`${record ? 'Edit' : 'Add'} ${modeLabel}`);
    $('#CashierReportLpgInventoryModalDescription').text(mode === 'BO_REPLACEMENT' ? 'Record stock received from a supplier as replacement for bad-order items.' : (mode === 'DELIVERY' ? 'Record the LPG quantity delivered by a supplier.' : 'Record the actual LPG stock available at the start of the report.'));
    $('#save-lpg-inventory').html(`<i class="bi bi-save-fill me-2"></i>${record ? 'Update' : 'Save'} ${modeLabel}`);
    showLpgInventoryModal('#CashierReportLpgInventoryModal');
}

function reloadLpgInventoryTables() {
    if (LpgBeginningInventoryTable) LpgBeginningInventoryTable.ajax.reload(null, false);
    if (LpgDeliveryTable) LpgDeliveryTable.ajax.reload(null, false);
    if (LpgBoReplacementTable) LpgBoReplacementTable.ajax.reload(null, false);
}

function showLpgInventoryModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(element).show();
}

function hideLpgInventoryModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(element).hide();
}

function clearLpgInventoryErrors() {
    $('#CashierReportLpgInventoryForm .is-invalid').removeClass('is-invalid');
    $('#CashierReportLpgInventoryForm .invalid-feedback').text('');
}

function showLpgInventoryErrors(errors) {
    Object.keys(errors).forEach(function (field) {
        const input = $('[name="' + field + '"]');
        input.addClass('is-invalid');
        $('#' + field + 'Error').text(Array.isArray(errors[field]) ? errors[field][0] : errors[field]);
    });
}
</script>
