<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
    });

    initLpgExpensesTable();
});

let LpgExpensesTable;

function initLpgExpensesTable() {
    if (!$('#LpgExpensesTable').length) return;

    if ($.fn.DataTable.isDataTable('#LpgExpensesTable')) {
        $('#LpgExpensesTable').DataTable().destroy();
    }

    LpgExpensesTable = $('#LpgExpensesTable').DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('GetLpgExpensesList') }}",
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
                render: function (data, type, row, meta) { return meta.row + 1; }
            },
            { data: 'client_name', defaultContent: '-' },
            { data: 'expense_type', defaultContent: '-' },
            { data: 'purpose', defaultContent: '-' },
            {
                data: null,
                render: function (data, type, row) {
                    return row.product_name
                        ? [row.product_name, row.product_unit_measurement].filter(Boolean).join(' ')
                        : (row.item_description || '-');
                }
            },
            {
                data: 'order_quantity',
                className: 'text-end',
                render: function (value) {
                    return value === null || value === '' ? '-' : Number(value).toLocaleString('en-PH', {
                        minimumFractionDigits: 2, maximumFractionDigits: 2
                    });
                }
            },
            {
                data: 'unit_price',
                className: 'text-end',
                render: function (value) { return formatLpgExpenseMoney(value); }
            },
            {
                data: 'amount',
                className: 'text-end fw-semibold',
                render: function (value) { return formatLpgExpenseMoney(value); }
            },
            { data: 'remarks', defaultContent: '-' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center text-nowrap',
                render: function (data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary EditLpgExpense"
                                data-id="${row.cashiers_report_lpg_expenses_id}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger DeleteLpgExpense"
                                data-id="${row.cashiers_report_lpg_expenses_id}" title="Delete">
                            <i class="bi bi-trash3-fill"></i>
                        </button>`;
                }
            }
        ],
        order: [[1, 'asc']],
        language: { emptyTable: 'No LPG expenses found.' }
    });

    if (typeof autoAdjustColumns === 'function') autoAdjustColumns(LpgExpensesTable);
}

function formatLpgExpenseMoney(value) {
    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2, maximumFractionDigits: 2
    });
}

function reloadLpgExpensesTable() {
    if (LpgExpensesTable) LpgExpensesTable.ajax.reload(null, false);
}

function showLpgExpenseModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).show();
    }
}

function hideLpgExpenseModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).hide();
    }
}

function getLpgExpenseDatalistOption(listId, value) {
    let match = null;
    $('#' + listId + ' option').each(function () {
        if (this.value === value) {
            match = $(this);
            return false;
        }
    });
    return match;
}

function clearLpgExpenseErrors() {
    $('#CashierReportLpgExpensesForm .is-invalid').removeClass('is-invalid');
    $('#CashierReportLpgExpensesForm .invalid-feedback').removeClass('d-block');
    $('#lpg_expense_client, #lpg_expense_item').each(function () {
        this.setCustomValidity('');
    });
}

function resetLpgExpenseForm() {
    const reportId = $('#cashiers_report_lpg_expenses_report_id').val();
    const form = document.getElementById('CashierReportLpgExpensesForm');
    if (!form) return;

    form.reset();
    $('#cashiers_report_lpg_expenses_id').val(0);
    $('#cashiers_report_lpg_expenses_report_id').val(reportId);
    $('#CashierReportLpgExpensesModalLabel').text('Add Expense');
    clearLpgExpenseErrors();
    setLpgExpenseSaveButton(false);
}

function setLpgExpenseSaveButton(isUpdating, isSaving) {
    const button = $('#save-lpg-expense');
    button.prop('disabled', !!isSaving);
    button.html(isSaving
        ? '<span class="spinner-border spinner-border-sm me-2"></span>Saving...'
        : (isUpdating
            ? '<i class="bi bi-check-circle-fill me-2"></i>Update Expense'
            : '<i class="bi bi-save-fill me-2"></i>Save Expense'));
}

function updateLpgExpenseProductFields() {
    const itemValue = $('#lpg_expense_item').val();
    const option = getLpgExpenseDatalistOption('lpgExpenseProductList', itemValue);
    const isProduct = !!option;

    if (isProduct) {
        const price = Number(option.attr('data-price') || 0);
        $('#lpg_expense_unit_price').val(price.toFixed(2));
        $('#lpg_expense_quantity').attr('required', true);
        updateLpgExpenseCalculatedAmount();
    } else {
        $('#lpg_expense_quantity').removeAttr('required');
        // When changing to a manual description, do not leave a product price behind.
        $('#lpg_expense_unit_price').val('');
    }
}

function updateLpgExpenseCalculatedAmount() {
    const option = getLpgExpenseDatalistOption('lpgExpenseProductList', $('#lpg_expense_item').val());
    if (!option) return;

    const quantity = Number($('#lpg_expense_quantity').val() || 0);
    const price = Number(option.attr('data-price') || 0);
    $('#lpg_expense_unit_price').val(price.toFixed(2));
    $('#lpg_expense_amount').val((quantity * price).toFixed(2));
}

$('#lpg_expense_item').on('input change', updateLpgExpenseProductFields);
$('#lpg_expense_quantity').on('input change', updateLpgExpenseCalculatedAmount);

$('#lpg_expense_client').on('input change', function () {
    const value = this.value.trim();
    const option = value ? getLpgExpenseDatalistOption('lpgExpenseClientList', value) : null;
    this.setCustomValidity(value && !option ? 'Select a client from the list or clear this field.' : '');
});

$('#CashierReportLpgExpensesModal').on('show.bs.modal', function () {
    if (Number($('#cashiers_report_lpg_expenses_id').val() || 0) === 0) resetLpgExpenseForm();
});

$('#clear-lpg-expense').on('click', function () {
    window.setTimeout(resetLpgExpenseForm, 0);
});

$('#CashierReportLpgExpensesForm').on('submit', function (event) {
    event.preventDefault();
    clearLpgExpenseErrors();

    const form = this;
    const clientName = $('#lpg_expense_client').val().trim();
    const clientOption = clientName
        ? getLpgExpenseDatalistOption('lpgExpenseClientList', clientName)
        : null;
    const itemValue = $('#lpg_expense_item').val().trim();
    const productOption = itemValue
        ? getLpgExpenseDatalistOption('lpgExpenseProductList', itemValue)
        : null;

    if (clientName && !clientOption) {
        $('#lpg_expense_client')[0].setCustomValidity('Select a client from the list or clear this field.');
        $('#lpg_expense_client').addClass('is-invalid')[0].reportValidity();
        return;
    }

    $('#lpg_expense_client')[0].setCustomValidity('');
    if (productOption && !Number($('#lpg_expense_quantity').val())) {
        $('#lpg_expense_quantity').addClass('is-invalid').trigger('focus');
        return;
    }

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        form.reportValidity();
        return;
    }

    const recordId = Number($('#cashiers_report_lpg_expenses_id').val() || 0);
    const formData = $(form).serializeArray().filter(function (field) {
        return field.name !== 'client_idx' && field.name !== 'product_idx';
    });
    formData.push({ name: 'client_idx', value: clientOption ? clientOption.attr('data-id') : '' });
    formData.push({ name: 'product_idx', value: productOption ? productOption.attr('data-id') : '' });

    const url = recordId > 0
        ? "{{ route('UpdateLpgExpense') }}"
        : "{{ route('SaveLpgExpense') }}";
    setLpgExpenseSaveButton(recordId > 0, true);

    $.ajax({
        url: url,
        type: 'POST',
        data: $.param(formData),
        success: function (response) {
            hideLpgExpenseModal('#CashierReportLpgExpensesModal');
            resetLpgExpenseForm();
            reloadLpgExpensesTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'LPG expense saved successfully.');
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
            Object.keys(errors).forEach(function (field) {
                const input = $('[name="' + field + '"]');
                input.addClass('is-invalid');
                const errorElement = document.getElementById(field + 'Error');
                if (errorElement) {
                    errorElement.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    errorElement.classList.add('d-block');
                }
            });
            if (!Object.keys(errors).length && typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to save LPG expense.');
            }
        },
        complete: function () {
            setLpgExpenseSaveButton(recordId > 0, false);
        }
    });
});

$(document).on('click', '.EditLpgExpense', function () {
    const id = $(this).data('id');
    $.ajax({
        url: "{{ route('LpgExpenseInfo') }}",
        type: 'POST',
        data: { cashiers_report_lpg_expenses_id: id },
        success: function (response) {
            const row = Array.isArray(response) ? response[0] : ((response.data || [])[0] || response);
            if (!row || !row.cashiers_report_lpg_expenses_id) return;

            resetLpgExpenseForm();
            $('#cashiers_report_lpg_expenses_id').val(row.cashiers_report_lpg_expenses_id);
            $('#cashiers_report_lpg_expenses_report_id').val(row.cashiers_report_id);
            $('#lpg_expense_type').val(row.expense_type || '');
            $('#lpg_expense_purpose').val(row.purpose || '');
            $('#lpg_expense_item').val(row.product_name
                ? [row.product_name, row.product_unit_measurement].filter(Boolean).join(' ')
                : (row.item_description || ''));
            $('#lpg_expense_quantity').val(row.order_quantity ?? '');
            $('#lpg_expense_unit_price').val(row.unit_price ?? '');
            $('#lpg_expense_amount').val(row.amount ?? '');
            $('#lpg_expense_remarks').val(row.remarks || '');

            const clientOption = $('#lpgExpenseClientList option').filter(function () {
                return String($(this).attr('data-id')) === String(row.client_idx);
            }).first();
            $('#lpg_expense_client').val(clientOption.length ? clientOption.val() : (row.client_name || ''));
            $('#lpg_expense_quantity').prop('required', !!row.product_idx);
            $('#CashierReportLpgExpensesModalLabel').text('Update Expense');
            setLpgExpenseSaveButton(true, false);
            showLpgExpenseModal('#CashierReportLpgExpensesModal');
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to load LPG expense.');
            }
        }
    });
});

$(document).on('click', '.DeleteLpgExpense', function () {
    const row = LpgExpensesTable.row($(this).closest('tr')).data();
    if (!row) return;

    $('#lpg_expense_delete_id').val(row.cashiers_report_lpg_expenses_id);
    $('#lpg_expense_delete_client').text(row.client_name || '-');
    $('#lpg_expense_delete_type').text(row.expense_type || '-');
    $('#lpg_expense_delete_purpose').text(row.purpose || '-');
    $('#lpg_expense_delete_item').text(row.product_name
        ? [row.product_name, row.product_unit_measurement].filter(Boolean).join(' ')
        : (row.item_description || '-'));
    $('#lpg_expense_delete_quantity').text(row.order_quantity == null ? '-' : row.order_quantity);
    $('#lpg_expense_delete_unit_price').text(Number(row.unit_price || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2, maximumFractionDigits: 2
    }));
    $('#lpg_expense_delete_remarks').text(row.remarks || '-');
    $('#lpg_expense_delete_amount').text(Number(row.amount || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2, maximumFractionDigits: 2
    }));
    showLpgExpenseModal('#CashierReportLpgExpensesDeleteModal');
});

$('#deleteLpgExpenseConfirmed').on('click', function () {
    const button = $(this);
    const id = $('#lpg_expense_delete_id').val();
    if (!id) return;

    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');
    $.ajax({
        url: "{{ route('DeleteLpgExpense') }}",
        type: 'POST',
        data: { cashiers_report_lpg_expenses_id: id },
        success: function (response) {
            hideLpgExpenseModal('#CashierReportLpgExpensesDeleteModal');
            reloadLpgExpensesTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'LPG expense deleted successfully.');
            }
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to delete LPG expense.');
            }
        },
        complete: function () {
            button.prop('disabled', false).html('<i class="bi bi-trash3-fill me-2"></i>Delete Expense');
        }
    });
});
</script>
