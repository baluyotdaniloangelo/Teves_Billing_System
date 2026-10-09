<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
    });

    initLpgNonCashPaymentTable();
    toggleLpgCheckExpiryDate();
});

let LpgNonCashPaymentTable;

function initLpgNonCashPaymentTable() {
    if (!$('#LpgNonCashPaymentsTable').length) return;

    if ($.fn.DataTable.isDataTable('#LpgNonCashPaymentsTable')) {
        $('#LpgNonCashPaymentsTable').DataTable().destroy();
    }

    LpgNonCashPaymentTable = $('#LpgNonCashPaymentsTable').DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('GetLpgNonCashPaymentList') }}",
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
            { data: 'mode_of_payment', defaultContent: '-' },
            { data: 'payer_name', defaultContent: '-' },
            { data: 'payer_number', defaultContent: '-' },
            { data: 'reference_no', defaultContent: '-' },
            {
                data: 'check_expiry_date',
                className: 'text-center',
                render: function (value) {
                    if (!value) return '-';
                    return String(value).substring(0, 10);
                }
            },
            {
                data: 'amount',
                className: 'text-end fw-semibold',
                render: function (value) { return formatLpgNonCashMoney(value); }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center text-nowrap',
                render: function (data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary EditLpgNonCashPayment"
                                data-id="${row.cashiers_report_lpg_non_cash_payment_id}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger DeleteLpgNonCashPayment"
                                data-id="${row.cashiers_report_lpg_non_cash_payment_id}" title="Delete">
                            <i class="bi bi-trash3-fill"></i>
                        </button>`;
                }
            }
        ],
        order: [[1, 'asc']],
        language: { emptyTable: 'No non-cash payments found.' }
    });

    if (typeof autoAdjustColumns === 'function') autoAdjustColumns(LpgNonCashPaymentTable);
}

function formatLpgNonCashMoney(value) {
    return '₱ ' + Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function reloadLpgNonCashPaymentTable() {
    if (LpgNonCashPaymentTable) LpgNonCashPaymentTable.ajax.reload(null, false);
}

function showLpgNonCashPaymentModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).show();
    }
}

function hideLpgNonCashPaymentModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(element).hide();
    }
}

function toggleLpgCheckExpiryDate() {
    const isCheck = $('#lpg_non_cash_mode').val() === 'check';
    $('#lpg_non_cash_check_expiry_wrap').prop('hidden', !isCheck);
    $('#lpg_non_cash_check_expiry_date').prop('required', isCheck);
    if (!isCheck) $('#lpg_non_cash_check_expiry_date').val('');
}

function clearLpgNonCashPaymentErrors() {
    $('#CashierReportLpgNonCashPaymentForm .is-invalid').removeClass('is-invalid');
    $('#CashierReportLpgNonCashPaymentForm .invalid-feedback').removeClass('d-block');
}

function resetLpgNonCashPaymentForm() {
    const form = document.getElementById('CashierReportLpgNonCashPaymentForm');
    if (!form) return;

    const reportId = $('#cashiers_report_lpg_non_cash_payment_report_id').val();
    form.reset();
    $('#cashiers_report_lpg_non_cash_payment_id').val(0);
    $('#cashiers_report_lpg_non_cash_payment_report_id').val(reportId);
    $('#CashierReportLpgNonCashPaymentModalLabel').text('Add Non-Cash Payment');
    clearLpgNonCashPaymentErrors();
    toggleLpgCheckExpiryDate();
    setLpgNonCashPaymentButton(false, false);
}

function setLpgNonCashPaymentButton(isUpdating, isSaving) {
    const button = $('#save-lpg-non-cash-payment');
    button.prop('disabled', !!isSaving);
    button.html(isSaving
        ? '<span class="spinner-border spinner-border-sm me-2"></span>Saving...'
        : (isUpdating
            ? '<i class="bi bi-check-circle-fill me-2"></i>Update Payment'
            : '<i class="bi bi-save-fill me-2"></i>Save Payment'));
}

$('#lpg_non_cash_mode').on('change', toggleLpgCheckExpiryDate);

$('#CashierReportLpgNonCashPaymentModal').on('show.bs.modal', function () {
    if (Number($('#cashiers_report_lpg_non_cash_payment_id').val() || 0) === 0) {
        resetLpgNonCashPaymentForm();
    }
});

$('#clear-lpg-non-cash-payment').on('click', function () {
    window.setTimeout(resetLpgNonCashPaymentForm, 0);
});

$('#CashierReportLpgNonCashPaymentForm').on('submit', function (event) {
    event.preventDefault();
    clearLpgNonCashPaymentErrors();
    toggleLpgCheckExpiryDate();

    const form = this;
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        form.reportValidity();
        return;
    }

    const recordId = Number($('#cashiers_report_lpg_non_cash_payment_id').val() || 0);
    const url = recordId > 0
        ? "{{ route('UpdateLpgNonCashPayment') }}"
        : "{{ route('SaveLpgNonCashPayment') }}";

    setLpgNonCashPaymentButton(recordId > 0, true);
    $.ajax({
        url: url,
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
            hideLpgNonCashPaymentModal('#CashierReportLpgNonCashPaymentModal');
            resetLpgNonCashPaymentForm();
            reloadLpgNonCashPaymentTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'Non-cash payment saved successfully.');
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
            Object.keys(errors).forEach(function (field) {
                $('[name="' + field + '"]').addClass('is-invalid');
                const errorElement = document.getElementById(field + 'Error');
                if (errorElement) {
                    errorElement.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    errorElement.classList.add('d-block');
                }
            });
            if (!Object.keys(errors).length && typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to save non-cash payment.');
            }
        },
        complete: function () {
            setLpgNonCashPaymentButton(recordId > 0, false);
        }
    });
});

$(document).on('click', '.EditLpgNonCashPayment', function () {
    const id = $(this).data('id');
    $.ajax({
        url: "{{ route('LpgNonCashPaymentInfo') }}",
        type: 'POST',
        data: { cashiers_report_lpg_non_cash_payment_id: id },
        success: function (response) {
            const record = Array.isArray(response) ? response[0] : (response.data || response);
            if (!record || !record.cashiers_report_lpg_non_cash_payment_id) return;

            resetLpgNonCashPaymentForm();
            $('#cashiers_report_lpg_non_cash_payment_id').val(record.cashiers_report_lpg_non_cash_payment_id);
            $('#cashiers_report_lpg_non_cash_payment_report_id').val(record.cashiers_report_id);
            $('#lpg_non_cash_mode').val(record.mode_of_payment || '');
            $('#lpg_non_cash_payer_name').val(record.payer_name || '');
            $('#lpg_non_cash_payer_number').val(record.payer_number || '');
            $('#lpg_non_cash_reference_no').val(record.reference_no || '');
            $('#lpg_non_cash_check_expiry_date').val(record.check_expiry_date
                ? String(record.check_expiry_date).substring(0, 10)
                : '');
            $('#lpg_non_cash_amount').val(record.amount ?? '');
            $('#CashierReportLpgNonCashPaymentModalLabel').text('Update Non-Cash Payment');
            toggleLpgCheckExpiryDate();
            setLpgNonCashPaymentButton(true, false);
            showLpgNonCashPaymentModal('#CashierReportLpgNonCashPaymentModal');
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to load non-cash payment.');
            }
        }
    });
});

$(document).on('click', '.DeleteLpgNonCashPayment', function () {
    const row = LpgNonCashPaymentTable.row($(this).closest('tr')).data();
    if (!row) return;

    $('#lpg_non_cash_payment_delete_id').val(row.cashiers_report_lpg_non_cash_payment_id);
    $('#lpg_non_cash_delete_mode').text(row.mode_of_payment || '-');
    $('#lpg_non_cash_delete_payer_name').text(row.payer_name || '-');
    $('#lpg_non_cash_delete_payer_number').text(row.payer_number || '-');
    $('#lpg_non_cash_delete_reference_no').text(row.reference_no || '-');
    $('#lpg_non_cash_delete_check_expiry_date').text(row.check_expiry_date
        ? String(row.check_expiry_date).substring(0, 10)
        : '-');
    $('#lpg_non_cash_delete_amount').text(Number(row.amount || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    showLpgNonCashPaymentModal('#CashierReportLpgNonCashPaymentDeleteModal');
});

$('#deleteLpgNonCashPaymentConfirmed').on('click', function () {
    const button = $(this);
    const id = $('#lpg_non_cash_payment_delete_id').val();
    if (!id) return;

    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');
    $.ajax({
        url: "{{ route('DeleteLpgNonCashPayment') }}",
        type: 'POST',
        data: { cashiers_report_lpg_non_cash_payment_id: id },
        success: function (response) {
            hideLpgNonCashPaymentModal('#CashierReportLpgNonCashPaymentDeleteModal');
            reloadLpgNonCashPaymentTable();
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'Non-cash payment deleted successfully.');
            }
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to delete non-cash payment.');
            }
        },
        complete: function () {
            button.prop('disabled', false).html('<i class="bi bi-trash3-fill me-2"></i>Delete Payment');
        }
    });
});
</script>
