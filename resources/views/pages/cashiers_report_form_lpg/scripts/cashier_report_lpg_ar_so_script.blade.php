<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
    });

    initLpgArSoTable();
    updateLpgArSoAmount();
});

let LpgArSoTable;

function initLpgArSoTable() {
    if ($.fn.DataTable.isDataTable('#LpgArSoTable')) {
        $('#LpgArSoTable').DataTable().destroy();
    }

    LpgArSoTable = $('#LpgArSoTable').DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        stateSave: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('GetLpgArSoList') }}",
            type: 'POST',
            data: function (data) {
                data.CashiersReportId = {{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }};
            },
            dataSrc: function (response) {
                return Array.isArray(response) ? response : (response.data || []);
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'item_description', defaultContent: '-' },
            { data: 'client_name', defaultContent: '-' },
            { data: 'dr_number', defaultContent: '-' },
            {
                data: null,
                render: function (data, type, row) {
                    return [row.product_name, row.product_unit_measurement]
                        .filter(Boolean)
                        .join(' ')
                        || row.item_description
                        || '-';
                }
            },
            {
                data: 'order_quantity',
                className: 'text-end',
                render: function (value) {
                    return Number(value || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            {
                data: 'unit_price',
                className: 'text-end',
                render: function (value) {
                    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            {
                data: 'discounted_unit_price',
                className: 'text-end',
                render: function (value) {
                    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            {
                data: 'discount_per_unit',
                className: 'text-end',
                render: function (value) {
                    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            {
                data: 'discount_total_amount',
                className: 'text-end text-danger',
                render: function (value) {
                    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            {
                data: 'net_amount',
                className: 'text-end fw-semibold',
                render: function (value) {
                    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary" id="EditLpgArSo"
                                data-id="${row.cashiers_report_lpg_ar_so_id}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="DeleteLpgArSo"
                                data-id="${row.cashiers_report_lpg_ar_so_id}" title="Delete">
                            <i class="bi bi-trash3-fill"></i>
                        </button>`;
                }
            }
        ],
        order: [[1, 'asc']],
        language: { emptyTable: 'No LPG AR sales orders or returns found.' }
    });

    if (typeof autoAdjustColumns === 'function') {
        autoAdjustColumns(LpgArSoTable);
    }
}

function reloadLpgArSoTable() {
    if (LpgArSoTable) {
        LpgArSoTable.ajax.reload(null, false);
    }
}

function lpgArSoShowModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).show();
    }
}

function lpgArSoHideModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).hide();
    }
}

function findLpgArSoDatalistId(listId, selectedValue) {
    let id = '';
    $('#' + listId + ' option').each(function () {
        if (this.value === selectedValue) {
            id = $(this).attr('data-id') || '';
            return false;
        }
    });
    return id;
}

function findLpgArSoDatalistOption(listId, selectedValue) {
    let selected = null;
    $('#' + listId + ' option').each(function () {
        if (this.value === selectedValue) {
            selected = $(this);
            return false;
        }
    });
    return selected;
}

function updateLpgArSoAmount() {
    const quantity = parseFloat($('#ar_so_quantity').val()) || 0;
    const unitPrice = parseFloat($('#ar_so_unit_price').val()) || 0;
    const discountPerUnit = parseFloat($('#ar_so_discount_per_unit').val()) || 0;
    const discountedUnitPrice = Math.max(unitPrice - discountPerUnit, 0);
    const totalDiscount = quantity * discountPerUnit;
    const netAmount = quantity * discountedUnitPrice;

    $('#LpgArSoDiscountedUnitPrice').text(discountedUnitPrice.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    $('#LpgArSoTotalDiscount').text(totalDiscount.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    $('#LpgArSoTotalAmount').text(netAmount.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
}

$('#ar_so_quantity, #ar_so_unit_price, #ar_so_discount_per_unit').on('input change', updateLpgArSoAmount);

$('#ar_so_account_id').on('input change', function () {
    const clientId = findLpgArSoDatalistId('lpgArSoClientNameList', $(this).val());
    this.setCustomValidity(clientId ? '' : 'Please select an account from the list.');
});

$('#ar_so_product').on('input change', function () {
    const productOption = findLpgArSoDatalistOption('lpgArSoProductList', $(this).val());
    const productId = productOption ? productOption.attr('data-id') : '';

    this.setCustomValidity(productId ? '' : 'Please select a product from the list.');

    if (productOption && productOption.attr('data-price') !== undefined) {
        $('#ar_so_unit_price').val(productOption.attr('data-price'));
        updateLpgArSoAmount();
    }
});

function resetLpgArSoForm() {
    const form = document.getElementById('CashierReportLpgArSoForm');
    if (!form) return;

    form.reset();
    form.classList.remove('was-validated');
    $('#cashiers_report_lpg_ar_so_id').val('0');
    $('#cashiers_report_lpg_ar_so_report_id').val({{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }});
    $('#ar_so_account_id, #ar_so_product').each(function () {
        this.setCustomValidity('');
    });
    $('#LpgArSoDiscountedUnitPrice, #LpgArSoTotalDiscount').text('0.00');
    $('#LpgArSoTotalAmount').text('0.00');
    $('#CashierReportLpgArSoModalLabel').text('Add Sales Order / Return');
    $('#save-lpg-ar-so').prop('disabled', false).html(
        '<i class="bi bi-save-fill me-2"></i>Save Item'
    );
    $('#clear-lpg-ar-so').show();
}

$('#clear-lpg-ar-so').on('click', function () {
    setTimeout(resetLpgArSoForm, 0);
});

$('#CashierReportLpgArSoForm').on('submit', function (event) {
    event.preventDefault();

    const form = this;
    const clientInput = document.getElementById('ar_so_account_id');
    const productInput = document.getElementById('ar_so_product');
    const clientId = findLpgArSoDatalistId('lpgArSoClientNameList', clientInput.value);
    const productId = findLpgArSoDatalistId('lpgArSoProductList', productInput.value);

    clientInput.setCustomValidity(clientId ? '' : 'Please select an account from the list.');
    productInput.setCustomValidity(productId ? '' : 'Please select a product from the list.');
    form.classList.add('was-validated');

    if (!form.checkValidity()) {
        return;
    }

    const formData = {};
    $(form).serializeArray().forEach(function (field) {
        formData[field.name] = field.value;
    });

    // Send selected database IDs, not the client/product display labels.
    formData.client_idx = clientId;
    formData.product_idx = productId;
    delete formData.ar_so_account_id;
    delete formData.ar_so_product;

    const recordId = Number($('#cashiers_report_lpg_ar_so_id').val() || 0);
    const url = recordId > 0
        ? "{{ route('UpdateLpgArSo') }}"
        : "{{ route('SaveLpgArSo') }}";

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        beforeSend: function () {
            $('#save-lpg-ar-so').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Saving...'
            );
        },
        success: function (response) {
            lpgArSoHideModal('#CashierReportLpgArSoModal');
            resetLpgArSoForm();
            reloadLpgArSoTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'Item saved successfully.');
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON?.errors || {};
            Object.keys(errors).forEach(function (field) {
                const errorId = field === 'client_idx'
                    ? 'client_idxError'
                    : field === 'product_idx'
                        ? 'product_idxError'
                        : field + 'Error';
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.textContent = Array.isArray(errors[field])
                        ? errors[field][0]
                        : errors[field];
                    errorElement.classList.add('d-block');
                }
            });
            if (!Object.keys(errors).length && typeof showValidationErrorModal === 'function') {
                showValidationErrorModal(xhr.responseJSON?.message || 'Unable to save item.');
            }
        },
        complete: function () {
            $('#save-lpg-ar-so').prop('disabled', false).html(
                Number($('#cashiers_report_lpg_ar_so_id').val() || 0) > 0
                    ? '<i class="bi bi-check-circle-fill me-2"></i>Update Item'
                    : '<i class="bi bi-save-fill me-2"></i>Save Item'
            );
        }
    });
});

function loadLpgArSoInformation(recordId, callback) {
    $.ajax({
        url: "{{ route('LpgArSoInformation') }}",
        type: 'POST',
        data: {
            cashiers_report_lpg_ar_so_id: recordId,
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            const row = Array.isArray(response) ? response[0] : (response.data || response);
            if (!row || !row.cashiers_report_lpg_ar_so_id) {
                if (typeof showValidationErrorModal === 'function') {
                    showValidationErrorModal('Unable to load the selected item.');
                }
                return;
            }
            callback(row);
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal(xhr.responseJSON?.message || 'Unable to load item details.');
            }
        }
    });
}

$(document).on('click', '#EditLpgArSo', function (event) {
    event.preventDefault();
    const recordId = $(this).data('id');
    if (!recordId) return;

    loadLpgArSoInformation(recordId, function (row) {
        const productLabel = [row.product_name, row.product_unit_measurement]
            .filter(Boolean)
            .join(' ');

        $('#CashierReportLpgArSoForm')[0].classList.remove('was-validated');
        $('#cashiers_report_lpg_ar_so_id').val(row.cashiers_report_lpg_ar_so_id);
        $('#cashiers_report_lpg_ar_so_report_id').val(row.cashiers_report_id);
        $('#ar_so_dr_number').val(row.dr_number || '');
        $('#ar_so_type').val(row.item_description || '');
        $('#ar_so_account_id').val(row.client_name || '').get(0).setCustomValidity('');
        $('#ar_so_product').val(productLabel).get(0).setCustomValidity('');
        $('#ar_so_quantity').val(row.order_quantity ?? '');
        $('#ar_so_unit_price').val(row.unit_price ?? '');
        $('#ar_so_discount_per_unit').val(row.discount_per_unit ?? 0);
        updateLpgArSoAmount();
        $('#CashierReportLpgArSoModalLabel').text('Edit Sales Order / Return');
        $('#save-lpg-ar-so').html('<i class="bi bi-check-circle-fill me-2"></i>Update Item');
        $('#clear-lpg-ar-so').hide();
        lpgArSoShowModal('#CashierReportLpgArSoModal');
    });
});

function formatLpgArSoMoney(value) {
    return Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

$(document).on('click', '#DeleteLpgArSo', function (event) {
    event.preventDefault();
    const recordId = $(this).data('id');
    if (!recordId) return;

    loadLpgArSoInformation(recordId, function (row) {
        const productLabel = [row.product_name, row.product_unit_measurement]
            .filter(Boolean)
            .join(' ');

        $('#lpg_ar_so_delete_id').val(row.cashiers_report_lpg_ar_so_id);
        $('#lpg_ar_so_delete_client').text(row.client_name || '-');
        $('#lpg_ar_so_delete_type').text(row.item_description || '-');
        $('#lpg_ar_so_delete_dr_number').text(row.dr_number || '-');
        $('#lpg_ar_so_delete_product').text(productLabel || '-');
        $('#lpg_ar_so_delete_quantity').text(formatLpgArSoMoney(row.order_quantity));
        $('#lpg_ar_so_delete_unit_price').text(formatLpgArSoMoney(row.unit_price));
        $('#lpg_ar_so_delete_discounted_unit_price').text(formatLpgArSoMoney(row.discounted_unit_price));
        $('#lpg_ar_so_delete_discount_per_unit').text(formatLpgArSoMoney(row.discount_per_unit));
        $('#lpg_ar_so_delete_total_discount').text(formatLpgArSoMoney(row.discount_total_amount));
        $('#lpg_ar_so_delete_amount').text(formatLpgArSoMoney(row.net_amount));
        lpgArSoShowModal('#CashierReportLpgArSoDeleteModal');
    });
});

$('#deleteLpgArSoConfirmed').on('click', function () {
    const button = this;
    const recordId = $('#lpg_ar_so_delete_id').val();
    if (!recordId) {
        if (typeof showValidationErrorModal === 'function') {
            showValidationErrorModal('Invalid item selected.');
        }
        return;
    }

    $.ajax({
        url: "{{ route('DeleteLpgArSo') }}",
        type: 'POST',
        data: {
            cashiers_report_lpg_ar_so_id: recordId,
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function () {
            $(button).prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...'
            );
        },
        success: function (response) {
            lpgArSoHideModal('#CashierReportLpgArSoDeleteModal');
            reloadLpgArSoTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'Item deleted successfully.');
            }
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal(xhr.responseJSON?.message || 'Unable to delete item.');
            }
        },
        complete: function () {
            $(button).prop('disabled', false).html(
                '<i class="bi bi-trash3-fill me-2"></i>Delete Item'
            );
        }
    });
});

$('#CashierReportLpgArSoModal').on('hidden.bs.modal', function () {
    if (Number($('#cashiers_report_lpg_ar_so_id').val() || 0) === 0) {
        resetLpgArSoForm();
    }
});
</script>
