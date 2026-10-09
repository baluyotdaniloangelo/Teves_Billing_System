<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    initLpgArTable();
});

let LpgArTable;

function initLpgArTable() {
    if ($.fn.DataTable.isDataTable('#LpgArTable')) {
        $('#LpgArTable').DataTable().destroy();
    }

    LpgArTable = $('#LpgArTable').DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        stateSave: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('GetLpgArList') }}",
            type: 'POST',
            data: function (data) {
                data.CashiersReportId = {{ $CashiersReportId }};
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
            /*{ data: 'ar_date', defaultContent: '-' },*/
            { data: 'client_name', defaultContent: '-' },
            { data: 'dr_number', defaultContent: '-' },
            { data: 'remarks', defaultContent: '-' },
            {
                data: 'amount_received',
                className: 'text-end',
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
                        <button type="button" class="btn btn-sm btn-primary" id="EditLpgAr"
                                data-id="${row.cashiers_report_lpg_ar_id}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="DeleteLpgAr"
                                data-id="${row.cashiers_report_lpg_ar_id}" title="Delete">
                            <i class="bi bi-trash3-fill"></i>
                        </button>`;
                }
            }
        ],
        order: [[1, 'asc']],
        language: { emptyTable: 'No AR collections found.' }
    });

    if (typeof autoAdjustColumns === 'function') {
        autoAdjustColumns(LpgArTable);
    }
}

function reloadLpgArTable() {
    if (LpgArTable) {
        LpgArTable.ajax.reload(null, false);
    }
}

function lpgArShowModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).show();
    }
}

function lpgArHideModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).hide();
    }
}

function resetLpgArForm() {
    const form = document.getElementById('CashierReportLpgArForm');
    if (!form) return;

    form.reset();
    form.classList.remove('was-validated');
    $('#cashiers_report_lpg_ar_id').val('0');
    $('#client_idx').val('');
    $('#cashiers_report_id').val({{ $CashiersReportId }});
    $('#CashierReportLpgArModalLabel').text('Add Accounts Receivable Collection');
    $('#save-lpg-ar').prop('disabled', false).html(
        '<i class="bi bi-save-fill me-2"></i>Save Collection'
    );
    $('#clear-lpg-ar').show();
}

function selectedLpgArClientId(clientName) {
    let clientId = '';
    $('#lpgArClientNameList option').each(function () {
        if ($(this).val() === clientName) {
            clientId = $(this).attr('data-id') || '';
            return false;
        }
    });
    return clientId;
}

$('#ar_account_id').on('input change', function () {
    const clientId = selectedLpgArClientId($(this).val());
    $('#client_idx').val(clientId);
    this.setCustomValidity(clientId ? '' : 'Please select a client from the list.');
});

$('#clear-lpg-ar').on('click', function () {
    setTimeout(resetLpgArForm, 0);
});

$('#CashierReportLpgArForm').on('submit', function (event) {
    event.preventDefault();

    const form = this;
    const accountInput = document.getElementById('ar_account_id');

    // Hanapin ang napiling pangalan sa datalist,
    // at kunin ang client ID mula sa data-id.
    const clientName = $('#ar_account_id').val();

    const selectedOption = $('#lpgArClientNameList option').filter(function () {
        return this.value === clientName;
    }).first();

    const clientId = selectedOption.attr('data-id') || '';

    // I-reset muna ang dating custom validation error.
    accountInput.setCustomValidity('');

    if (!clientId) {
        accountInput.setCustomValidity(
            'Please select a client from the list.'
        );
        accountInput.reportValidity();
        return;
    }

    // Suriin ang required fields: date, DR number, at amount.
    form.classList.add('was-validated');

    if (!form.checkValidity()) {
        return;
    }

    // Kunin ang form fields, pero huwag ipadala ang account name.
    const formData = {};

    $(form).serializeArray().forEach(function (field) {
        if (
            field.name !== 'ar_account_id' &&
            field.name !== 'account_name'
        ) {
            formData[field.name] = field.value;
        }
    });

    // ID lang ng napiling client ang ipadala sa controller.
    formData.client_idx = clientId;

    const recordId = Number(
        $('#cashiers_report_lpg_ar_id').val() || 0
    );
	
	formData.cashiers_report_id = {{ $CashiersReportId }};
	
    const url = recordId > 0
        ? "{{ route('UpdateLpgAr') }}"
        : "{{ route('SaveLpgAr') }}";

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,

        beforeSend: function () {
            $('#save-lpg-ar')
                .prop('disabled', true)
                .html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Saving...'
                );
        },

        success: function (response) {
            lpgArHideModal('#CashierReportLpgArModal');
            resetLpgArForm();
            reloadLpgArTable();

            if (typeof showSuccessModal === 'function') {
                showSuccessModal(
                    response.message ||
                    'AR collection saved successfully.'
                );
            }
        },

        error: function (xhr) {
            const errors = xhr.responseJSON?.errors || {};

            Object.keys(errors).forEach(function (field) {
                // Ipakita ang client_idx validation error sa Account Name field.
                const errorId = field === 'client_idx'
                    ? 'account_nameError'
                    : field + 'Error';

                const errorElement = document.getElementById(errorId);

                if (errorElement) {
                    errorElement.textContent = Array.isArray(errors[field])
                        ? errors[field][0]
                        : errors[field];

                    errorElement.classList.add('d-block');
                }
            });

            if (
                Object.keys(errors).length === 0 &&
                typeof showValidationErrorModal === 'function'
            ) {
                showValidationErrorModal(
                    xhr.responseJSON?.message ||
                    'Unable to save AR collection.'
                );
            }
        },

        complete: function () {
            $('#save-lpg-ar')
                .prop('disabled', false)
                .html(
                    Number($('#cashiers_report_lpg_ar_id').val() || 0) > 0
                        ? '<i class="bi bi-check-circle-fill me-2"></i>Update Collection'
                        : '<i class="bi bi-save-fill me-2"></i>Save Collection'
                );
        }
    });
});

function clearLpgArValidation() {
    const form = document.getElementById('CashierReportLpgArForm');
    if (form) {
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }
    $('#CashierReportLpgArForm .invalid-feedback').removeClass('d-block');
    $('#ar_account_id').get(0)?.setCustomValidity('');
}

function loadLpgArInformation(recordId, onSuccess) {
    $.ajax({
        url: "{{ route('LpgArInformation') }}",
        type: 'POST',
        data: {
            cashiers_report_lpg_ar_id: recordId,
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            const row = Array.isArray(response) ? response[0] : (response.data || response);
            if (!row || !row.cashiers_report_lpg_ar_id) {
                if (typeof showValidationErrorModal === 'function') {
                    showValidationErrorModal('Unable to load the selected AR collection.');
                }
                return;
            }
            onSuccess(row);
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal(xhr.responseJSON?.message || 'Unable to load AR collection.');
            }
        }
    });
}

$(document).on('click', '#EditLpgAr', function (event) {
    event.preventDefault();
    const recordId = $(this).data('id');
    if (!recordId) return;

    loadLpgArInformation(recordId, function (row) {
        clearLpgArValidation();
        $('#cashiers_report_lpg_ar_id').val(row.cashiers_report_lpg_ar_id);
        $('#cashiers_report_id').val(row.cashiers_report_id);
        $('#ar_date').val(String(row.ar_date || '').substring(0, 10));
        $('#client_idx').val(row.client_idx || '');
        $('#ar_account_id').val(row.client_name || '');
        $('#dr_number').val(row.dr_number || '');
        $('#remarks').val(row.remarks || '');
        $('#amount_received').val(row.amount_received || '');
        $('#CashierReportLpgArModalLabel').text('Edit AR Collection');
        $('#save-lpg-ar').html('<i class="bi bi-check-circle-fill me-2"></i>Update Collection');
        $('#clear-lpg-ar').hide();
        lpgArShowModal('#CashierReportLpgArModal');
    });
});

function formatLpgArDate(value) {
    if (!value) return '-';
    const parts = String(value).substring(0, 10).split('-');
    return parts.length === 3 ? `${parts[1]}/${parts[2]}/${parts[0]}` : value;
}

$(document).on('click', '#DeleteLpgAr', function (event) {
    event.preventDefault();
    const recordId = $(this).data('id');
    if (!recordId) return;

    loadLpgArInformation(recordId, function (row) {
        $('#lpg_ar_delete_id').val(row.cashiers_report_lpg_ar_id);
        $('#lpg_ar_delete_date').text(formatLpgArDate(row.ar_date));
        $('#lpg_ar_delete_client').text(row.client_name || '-');
        $('#lpg_ar_delete_dr_number').text(row.dr_number || '-');
        $('#lpg_ar_delete_remarks').text(row.remarks || '-');
        $('#lpg_ar_delete_amount_received').text(Number(row.amount_received || 0).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        lpgArShowModal('#CashierReportLpgArDeleteModal');
    });
});

$('#deleteLpgArConfirmed').on('click', function () {
    const button = this;
    const recordId = $('#lpg_ar_delete_id').val();
    if (!recordId) {
        if (typeof showValidationErrorModal === 'function') {
            showValidationErrorModal('Invalid AR collection selected.');
        }
        return;
    }

    $.ajax({
        url: "{{ route('DeleteLpgAr') }}",
        type: 'POST',
        data: {
            cashiers_report_lpg_ar_id: recordId,
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function () {
            $(button).prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...'
            );
        },
        success: function (response) {
            lpgArHideModal('#CashierReportLpgArDeleteModal');
            reloadLpgArTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'AR collection deleted successfully.');
            }
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal(xhr.responseJSON?.message || 'Unable to delete AR collection.');
            }
        },
        complete: function () {
            $(button).prop('disabled', false).html(
                '<i class="bi bi-trash3-fill me-2"></i>Delete AR Collection'
            );
        }
    });
});

$('#CashierReportLpgArModal').on('hidden.bs.modal', function () {
    if (Number($('#cashiers_report_lpg_ar_id').val() || 0) === 0) {
        resetLpgArForm();
    }
});
</script>
