<script>
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
    });
});

$(document).on('click', '#update-lpg-cashiers-report', function (event) {
    event.preventDefault();

    const form = document.getElementById('LpgCashierReportUpdateForm');
    const button = $(this);
    if (!form) return;

    $('#LpgCashierReportUpdateForm .is-invalid').removeClass('is-invalid');
    $('#LpgCashierReportUpdateForm .invalid-feedback').removeClass('d-block');

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        form.reportValidity();
        return;
    }

    const reportId = Number({{ $CashiersReportId }});
    const originalButtonHtml = button.html();
    button.prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

    $.ajax({
        url: "{{ route('update_cashier_report_post') }}",
        type: 'POST',
        data: $(form).serialize() + '&' + $.param({ CashiersReportId: reportId }),
        success: function (response) {
            const successMessage = response.success || "Cashier's Report information updated.";

            const modalElement = document.getElementById('UpdateCashiersReportModal');
            if (modalElement && window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalElement).hide();
            }

            if (typeof LoadCashierReportInfo === 'function') {
                LoadCashierReportInfo();
            }
            if (typeof LoadCashiersReportSummary === 'function') {
                LoadCashiersReportSummary();
            }
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(successMessage);
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
            Object.keys(errors).forEach(function (field) {
                const input = $('[name="' + field + '"]');
                input.addClass('is-invalid');

                const errorElement = document.getElementById(field + 'Error');
                if (errorElement) {
                    const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    errorElement.textContent = message;
                    errorElement.classList.add('d-block');
                }
            });

            if (!Object.keys(errors).length && typeof showValidationErrorModal === 'function') {
                showValidationErrorModal(
                    (xhr.responseJSON && xhr.responseJSON.message) || 'Unable to update the LPG cashier report.'
                );
            }
        },
        complete: function () {
            button.prop('disabled', false).html(originalButtonHtml);
        }
    });
});
</script>
