<script>
$(function () {
    const modalElement = document.getElementById('UpdateCashiersReportModal');
    const form = document.getElementById('FuelCashierReportUpdateForm');
    const button = $('#update-fuel-cashiers-report');

    if (!modalElement || !form) return;

    // Refresh the report fields whenever Edit is opened so the modal always
    // reflects the saved report information.
    modalElement.addEventListener('show.bs.modal', function () {
        if (typeof LoadCashierReportInfo === 'function') {
            LoadCashierReportInfo();
        }
    });

    $(form).on('input change', 'input, select, textarea', function () {
        this.classList.remove('is-invalid');
        const feedback = document.getElementById(this.name + 'Error');
        if (feedback) feedback.classList.remove('d-block');
    });

    button.on('click', function (event) {
        event.preventDefault();

        form.classList.add('was-validated');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(function (feedback) {
            feedback.classList.remove('d-block');
        });

        const originalButtonHtml = button.html();
        button.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

        $.ajax({
            url: "{{ route('update_cashier_report_post') }}",
            type: 'POST',
            data: $(form).serialize(),
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            success: function (response) {
                const successMessage = response.success || "Cashier's Report information updated.";
                $('#success_modal_message').text(successMessage);

                if (typeof LoadCashierReportInfo === 'function') {
                    LoadCashierReportInfo();
                }
                if (typeof LoadCashiersReportSummary === 'function') {
                    LoadCashiersReportSummary();
                }

                if (window.bootstrap && bootstrap.Modal) {
                    modalElement.addEventListener('hidden.bs.modal', function () {
                        if (typeof showSuccessModal === 'function') {
                            showSuccessModal(successMessage);
                        }
                    }, { once: true });
                    bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                } else {
                    if (typeof showSuccessModal === 'function') {
                        showSuccessModal(successMessage);
                    }
                    $(modalElement).modal('hide');
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors
                    ? xhr.responseJSON.errors
                    : {};

                Object.keys(errors).forEach(function (field) {
                    const input = form.elements.namedItem(field);
                    if (input && input.classList) input.classList.add('is-invalid');

                    const feedback = document.getElementById(field + 'Error');
                    if (feedback) {
                        const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                        feedback.textContent = message;
                        feedback.classList.add('d-block');
                    }
                });

                if (!Object.keys(errors).length && typeof showValidationErrorModal === 'function') {
                    const message = (xhr.responseJSON && xhr.responseJSON.message)
                        || 'Unable to update the Fuel cashier report.';
                    showValidationErrorModal(message);
                }
            },
            complete: function () {
                button.prop('disabled', false).html(originalButtonHtml);
            }
        });
    });

    modalElement.addEventListener('hidden.bs.modal', function () {
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(function (feedback) {
            feedback.classList.remove('d-block');
        });
    });
});
</script>
