<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
    });

    calculateLpgCashTotals();
    loadLpgCashRecord();
});

const lpgCashInfoUrl = "{{ route('GetLpgCashInfo') }}";
const lpgCashSaveUrl = "{{ route('SaveLpgCash') }}";

function formatLpgCashAmount(value) {
    return Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function calculateLpgCashTotals() {
    let totalCashOnHand = 0;

    $('.lpg-cash-quantity').each(function () {
        const quantity = Math.max(parseInt($(this).val(), 10) || 0, 0);
        const denomination = Number($(this).data('denomination') || 0);
        const lineAmount = quantity * denomination;
        const field = this.name;

        $('#lpg_cash_amount_' + field).text(formatLpgCashAmount(lineAmount));
        totalCashOnHand += lineAmount;
    });

    const cashDrop = Math.max(parseFloat($('#lpg_cash_drop').val()) || 0, 0);
    $('#lpg-cash-drop-amount').text(formatLpgCashAmount(cashDrop));
    $('#lpg_cash_total_on_hand').text(formatLpgCashAmount(totalCashOnHand));
    $('#lpg_cash_total_actual').text(formatLpgCashAmount(totalCashOnHand + cashDrop));
}

$('.lpg-cash-quantity, #lpg_cash_drop').on('input change', calculateLpgCashTotals);

function loadLpgCashRecord() {
    const reportId = $('#cashiers_report_lpg_cash_report_id').val();
    if (!reportId || Number(reportId) <= 0) return;

    $.ajax({
        url: lpgCashInfoUrl,
        type: 'POST',
        data: { CashiersReportId: reportId },
        success: function (response) {
            const record = response.data || response;
            if (!record) return;

            $('#cashiers_report_lpg_cash_id').val(record.cashiers_report_lpg_cash_id || 0);
            $('.lpg-cash-quantity').each(function () {
                this.value = record[this.name] ?? 0;
            });
            $('#lpg_cash_drop').val(record.cash_drop ?? 0);
            setLpgCashSaveButton(Number(record.cashiers_report_lpg_cash_id || 0) > 0, false);
            calculateLpgCashTotals();
        },
        error: function (xhr) {
            if (typeof showValidationErrorModal === 'function') {
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to load the LPG cash count.');
            }
        }
    });
}

function setLpgCashSaveButton(isUpdate, isSaving) {
    const button = $('#save-lpg-cash');
    button.prop('disabled', !!isSaving);
    button.html(isSaving
        ? '<span class="spinner-border spinner-border-sm me-2"></span>Saving...'
        : (isUpdate
            ? '<i class="bi bi-check-circle-fill me-2"></i>Update Cash Count'
            : '<i class="bi bi-save-fill me-2"></i>Save Cash Count'));
}

$('#CashierReportLpgCashForm').on('submit', function (event) {
    event.preventDefault();
    calculateLpgCashTotals();

    const form = this;
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        form.reportValidity();
        return;
    }

    const isUpdate = Number($('#cashiers_report_lpg_cash_id').val() || 0) > 0;
    setLpgCashSaveButton(isUpdate, true);

    $.ajax({
        url: lpgCashSaveUrl,
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
            const record = response.data || {};
            if (record.cashiers_report_lpg_cash_id) {
                $('#cashiers_report_lpg_cash_id').val(record.cashiers_report_lpg_cash_id);
            }
            if (record.cash_drop !== undefined) $('#lpg_cash_drop').val(record.cash_drop);
            setLpgCashSaveButton(true, false);
            calculateLpgCashTotals();

            if (typeof showSuccessModal === 'function') {
                showSuccessModal(response.message || 'LPG cash count saved successfully.');
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
                showValidationErrorModal((xhr.responseJSON && xhr.responseJSON.message) || 'Unable to save the LPG cash count.');
            }
        },
        complete: function () {
            setLpgCashSaveButton(isUpdate, false);
        }
    });
});

$('#lpg-collection-cash-tab').on('shown.bs.tab', function () {
    loadLpgCashRecord();
});
</script>
