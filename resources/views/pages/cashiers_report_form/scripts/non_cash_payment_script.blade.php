<script>
/*==============================================================
  NON-CASH PAYMENT SCRIPT
  Purpose: Manage listing, adding, editing, deleting, validation,
  notifications, and modal behavior for Fuel Non-Cash Payments.
==============================================================*/
$(function () {
    // Cache frequently used elements so we do not query the DOM repeatedly.
    const table = $('#non_cash_payments_table');
    const paymentModal = document.getElementById('nonCashPaymentModal');
    const deleteModal = document.getElementById('deleteNonCashPaymentModal');
    // Stores the success-notification timeout so an older timer can be cleared
    // before starting a new one. This timer must never close the Add form.
    let successModalCloseTimer = null;
    let errorModalCloseTimer = null;

    /* Escape values before inserting them into HTML attributes. This helps
       prevent user-supplied values from breaking the generated action buttons. */
    function escapeAttribute(value) {
        return $('<div>').text(value == null ? '' : String(value)).html().replace(/"/g, '&quot;');
    }

    /* Make payment type labels easier to read in the table. */
    function formatPaymentType(value) {
        return String(value || '').replace(/_/g, ' ').toUpperCase();
    }

    /*==============================================================
      DATATABLE INITIALIZATION / ACCESS
      Create the table only once; later calls return the existing instance.
    ==============================================================*/
    function getTable() {
        if (!$.fn.DataTable || !$.fn.dataTable) {
            console.error('DataTables is not available for Non-Cash Payments.');
            return null;
        }
        if ($.fn.DataTable.isDataTable(table)) return table.DataTable();

        return table.DataTable({
            // Show a processing indicator while DataTables loads data.
            processing: true,
            responsive: true,
            autoWidth: false,
            // Request the payments belonging to the current Cashier's Report.
            ajax: {
                url: "{{ route('cashiers_report_non_cash_payment.list') }}",
                type: 'POST',
                data: function (request) {
                    request.cash_report_id = {{ $CashiersReportId }};
                    request._token = "{{ csrf_token() }}";
                },
                // Support either a plain array response or a { data: [...] } response.
                dataSrc: function (response) {
                    return Array.isArray(response) ? response : (response && Array.isArray(response.data) ? response.data : []);
                },
                error: function (xhr) { console.error('Non-Cash Payments load failed.', xhr); }
            },
            // Map backend fields to table columns and format values for display.
            columns: [
                { data: 'payment_type', render: function (value, type) { return type === 'display' ? $('<span>').text(formatPaymentType(value)).html() : value; } },
                { data: 'payment_amount', className: 'text-end', render: function (value, type) { return type === 'display' ? '₱ ' + (Number(value) || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : value; } },
                { data: 'payer_name', render: $.fn.dataTable.render.text() },
                { data: 'payer_number', render: $.fn.dataTable.render.text() },
                { data: 'reference_number', render: $.fn.dataTable.render.text() },
                {
                    data: 'non_cash_payment_id', orderable: false, searchable: false, className: 'text-center',
                    // Build the Edit/Delete dropdown for each payment row.
                    render: function (id, type, row) {
                        if (type !== 'display') return '';
                        const safeId = Number.parseInt(id, 10) || 0;
                        return '<div class="dropdown dropstart non-cash-payment-actions">' +
                            '<button type="button" class="btn btn-light btn-sm rounded-3 shadow-sm border non-cash-payment-menu-toggle" aria-expanded="false"><i class="bi bi-three-dots"></i></button>' +
                            '<ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">' +
                                '<li><button type="button" class="dropdown-item edit-non-cash-payment" data-id="' + safeId + '" data-payment-type="' + escapeAttribute(row.payment_type) + '" data-payment-amount="' + escapeAttribute(row.payment_amount) + '" data-payer-name="' + escapeAttribute(row.payer_name) + '" data-payer-number="' + escapeAttribute(row.payer_number) + '" data-reference-number="' + escapeAttribute(row.reference_number) + '"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit</button></li>' +
                                '<li><button type="button" class="dropdown-item text-danger delete-non-cash-payment" data-id="' + safeId + '"><i class="bi bi-trash3 me-2"></i>Delete</button></li>' +
                            '</ul></div>';
                    }
                }
            ],
            pageLength: 10,
            order: []
        });
    }

    /*==============================================================
      FORM MODE RESET
      isEdit=true prepares the form for editing; false prepares a new record.
    ==============================================================*/
    const paymentFieldMap = {
        payment_type: '#non_cash_payment_type',
        payment_amount: '#non_cash_payment_amount',
        payer_name: '#non_cash_payment_name',
        payer_number: '#non_cash_payment_number',
        reference_number: '#non_cash_payment_reference'
    };
    const paymentFieldDefaults = {
        payment_type: 'Please select a payment mode.',
        payment_amount: 'Please enter an amount greater than zero.',
        payer_name: 'Please enter the payer name.',
        payer_number: 'Please enter the account number.',
        reference_number: 'Please enter the reference number.'
    };

    /* Clear inline validation states without changing the user's entered values. */
    function clearFieldError(fieldSelector) {
        const input = $(fieldSelector);
        input.removeClass('is-invalid is-valid');
        input.closest('.input-group').removeClass('is-invalid');
        const container = input.closest('.input-group').length ? input.closest('.input-group').parent() : input.parent();
        const feedback = container.find('> .invalid-feedback, > .field-error-message').first();
        const fieldName = Object.keys(paymentFieldMap).find(function (key) { return paymentFieldMap[key] === fieldSelector; });
        if (feedback.length && fieldName) feedback.text(paymentFieldDefaults[fieldName]).removeClass('d-block');
    }

    function clearAllFieldErrors() {
        Object.values(paymentFieldMap).forEach(clearFieldError);
        $('#non_cash_payment_error').addClass('d-none').text('');
    }

    /* Reset the form and title for Add or Edit mode. */
    function setMode(isEdit) {
        const form = document.getElementById('non_cash_payment_form');
        if (!form) return;
        form.reset();
        form.classList.remove('was-validated');
        clearAllFieldErrors();
        $('#non_cash_payment_id').val('0');
        $('#nonCashPaymentModalTitle').text(isEdit ? 'Edit Non-Cash Payment' : 'Add Non-Cash Payment');
        $('#save_non_cash_payment span').text(isEdit ? 'Update Payment' : 'Save Payment');
    }

    /* Show a specific server/client error under its corresponding input. */
    function showFieldError(fieldName, message) {
        const selector = paymentFieldMap[fieldName];
        if (!selector) return false;

        const input = $(selector);
        input.removeClass('is-valid').addClass('is-invalid');
        const container = input.closest('.input-group').length ? input.closest('.input-group').parent() : input.parent();
        let feedback = container.find('> .invalid-feedback').first();
        if (!feedback.length) {
            feedback = $('<div class="invalid-feedback field-error-message"></div>').appendTo(container);
        }
        feedback.text(message || 'Please check this field.').addClass('d-block');
        return true;
    }

    /*
     * Show an error popup for exactly 5 seconds without requiring extra Blade HTML.
     * The modal is created once and reused. It appears above the form modal without
     * adding a second backdrop, so the user can read the error and continue editing.
     */
    function showPaymentErrorModal(message) {
        let modalElement = document.getElementById('nonCashPaymentErrorNoticeModal');

        if (!modalElement) {
            const modalHtml = `
                <div class="modal fade" id="nonCashPaymentErrorNoticeModal" tabindex="-1"
                     aria-labelledby="nonCashPaymentErrorNoticeTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-body text-center p-4">
                                <div class="text-danger mb-3"><i class="bi bi-exclamation-triangle-fill" style="font-size:2.5rem;"></i></div>
                                <h5 class="modal-title fw-bold text-danger mb-2" id="nonCashPaymentErrorNoticeTitle">Unable to Complete</h5>
                                <div id="nonCashPaymentErrorNoticeMessage" class="text-body"></div>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('body').append(modalHtml);
            modalElement = document.getElementById('nonCashPaymentErrorNoticeModal');

            // Keep the existing payment form visible behind the short error notice.
            modalElement.addEventListener('hidden.bs.modal', function () {
                modalElement.setAttribute('aria-hidden', 'true');
            });
        }

        $('#nonCashPaymentErrorNoticeMessage').text(message || 'Something went wrong. Please try again.');

        if (errorModalCloseTimer) {
            window.clearTimeout(errorModalCloseTimer);
            errorModalCloseTimer = null;
        }

        if (window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement, {
                backdrop: false,
                keyboard: true,
                focus: false
            });
            modal.show();
            errorModalCloseTimer = window.setTimeout(function () {
                modal.hide();
                errorModalCloseTimer = null;
            }, 5000);
        } else {
            // Bootstrap is expected on this page; this fallback avoids a blocking alert.
            console.error(message);
        }
    }

    /* Show general errors through the timed error popup. */
    function showGeneralErrorNotice(message) {
        showPaymentErrorModal(message);
    }

    /* Route Laravel validation errors to inputs and also show a 5-second error popup. */
    function showSaveError(xhr) {
        const response = xhr.responseJSON || {};
        const validationErrors = response.errors || {};
        const popupMessages = [];
        let hasFieldError = false;

        Object.keys(validationErrors).forEach(function (fieldName) {
            const messages = validationErrors[fieldName];
            const message = Array.isArray(messages) ? messages[0] : messages;
            if (message) popupMessages.push(String(message));
            if (showFieldError(fieldName, message)) hasFieldError = true;
        });

        // Compatibility with controllers returning { error: '...' }.
        const errorText = String(response.error || response.message || '');
        if (errorText && !popupMessages.length) popupMessages.push(errorText);
        if (/reference number.*(exist|duplicate|already)/i.test(errorText)) {
            showFieldError('reference_number', errorText);
            hasFieldError = true;
        }

        const message = popupMessages.length
            ? popupMessages.join(' ')
            : (errorText || `Unable to save payment (HTTP ${xhr.status || 'error'}). Please try again.`);
        showPaymentErrorModal(message);
        console.error('Non-Cash Payment save failed.', xhr);
    }

    /* Open a Bootstrap modal safely when Bootstrap's Modal API is available. */
    function showModal(element) {
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(element).show();
        } else {
            console.error('Bootstrap modal plugin is unavailable.');
        }
    }


    /*==============================================================
      SUCCESS NOTIFICATION
      Show a modal notification for 10 seconds. Only the notification modal is
      hidden by the timer; it must not close the Add form.
    ==============================================================*/
    // Show the success modal for 10 seconds, then close ONLY the success modal.
    // Add keeps the form open; Update closes the form after a successful save.
    function showNonCashPaymentSuccessModal(message) {
        if (typeof showSuccessModal !== 'function') {
            window.alert(message);
            return;
        }

        if (successModalCloseTimer) {
            window.clearTimeout(successModalCloseTimer);
            successModalCloseTimer = null;
        }

        // This function is expected to display the shared success modal.
        showSuccessModal(message);

        // Wait 10 seconds, then hide the topmost visible modal that is not
        // the payment form or delete confirmation. The payment form is excluded.
        successModalCloseTimer = window.setTimeout(function () {
            if (window.bootstrap && bootstrap.Modal) {
                // Find the topmost visible Bootstrap modal other than the form
                // and delete-confirmation modals. This should be the success modal.
                const visibleModals = Array.from(document.querySelectorAll('.modal.show'))
                    .filter(function (modal) {
                        return modal !== paymentModal && modal !== deleteModal;
                    });

                const successModal = visibleModals[visibleModals.length - 1];
                if (successModal) {
                    bootstrap.Modal.getOrCreateInstance(successModal).hide();
                }
            }

            successModalCloseTimer = null;
        }, 10000);
    }

    /*==============================================================
      SAVE / UPDATE PAYMENT
      Validate the form, send the request to Laravel, refresh the table/summary,
      and apply the different modal behavior for Add versus Update.
    ==============================================================*/
    function savePayment(event) {
        event.preventDefault();
        console.debug('[Fuel non-cash] Form submit reached.');
        const form = document.getElementById('non_cash_payment_form');
        if (!form) return;
        // Validate each field inline; do not use a general form alert.
        clearAllFieldErrors();
        let isValid = true;
        let firstValidationMessage = '';
        Object.keys(paymentFieldMap).forEach(function (fieldName) {
            const input = $(paymentFieldMap[fieldName])[0];
            if (input && !input.checkValidity()) {
                const fieldMessage = paymentFieldDefaults[fieldName] || 'Please check this field.';
                showFieldError(fieldName, fieldMessage);
                if (!firstValidationMessage) firstValidationMessage = fieldMessage;
                isValid = false;
            }
        });
        if (!isValid) {
            showPaymentErrorModal(firstValidationMessage || 'Please complete all required fields.');
            return;
        }

        // A payment ID greater than zero means Update; zero means Add. Capture
        // this before the AJAX request because the form state can change later.
        const paymentId = Number($('#non_cash_payment_id').val() || 0);
        const isEdit = paymentId > 0;

        console.debug('[Fuel non-cash] Sending save request.');
        $.ajax({
            url: "{{ route('cashiers_report_non_cash_payment.save') }}", type: 'POST',
            // Keep the existing Laravel field names and CSRF token unchanged.
            data: {
                non_cash_payment_id: paymentId,
                cash_report_id: {{ $CashiersReportId }},
                payment_type: $('#non_cash_payment_type').val(),
                payment_amount: $('#non_cash_payment_amount').val(),
                payer_name: $('#non_cash_payment_name').val(),
                payer_number: $('#non_cash_payment_number').val(),
                reference_number: $('#non_cash_payment_reference').val(),
                _token: "{{ csrf_token() }}"
            },
            // Clear stale errors and prevent duplicate submissions while saving.
            beforeSend: function () {
                clearAllFieldErrors();
                $('#save_non_cash_payment').prop('disabled', true).find('span').text('Saving...');
            },
            // The server confirmed the save/update succeeded.
            success: function (response) {
                // Refresh the payment table and report summary.
                const dt = getTable();
                if (dt) dt.ajax.reload(null, false);
                if (typeof LoadCashiersReportSummary === 'function') LoadCashiersReportSummary();

                const successMessage = response.success ||
                    (isEdit ? 'Payment updated successfully.' : 'Payment saved successfully.');

                // Add: keep the form open. Update: close the form only after
                // the server confirms success, then show the success notification.
                if (isEdit && paymentModal && window.bootstrap && bootstrap.Modal) {
                    // UPDATE ONLY: close the form, then show the success modal.
                    // Waiting for hidden.bs.modal avoids overlapping Bootstrap modal transitions.
                    $(paymentModal).one('hidden.bs.modal.nonCashPaymentSuccess', function () {
                        showNonCashPaymentSuccessModal(successMessage);
                    });
                    bootstrap.Modal.getOrCreateInstance(paymentModal).hide();
                } else {
                    // ADD: keep the form open and show the success modal for 10 seconds.
                    showNonCashPaymentSuccessModal(successMessage);
                }
            },
            // Reuse the common error handler for validation/server failures.
            error: showSaveError,
            // Always restore the button after success or failure.
            complete: function () {
                $('#save_non_cash_payment').prop('disabled', false)
                    .find('span').text(isEdit ? 'Update Payment' : 'Save Payment');
            }
        });
    }

    /*==============================================================
      ADD BUTTON
      Reset the form to its default Add state when the user clicks Add.
    ==============================================================*/
    $('#add_non_cash_payment').on('click', function () {
        setMode(false);
    });

    /*==============================================================
      FORM SUBMISSION HANDLERS
      Delegated handlers continue working if the form is outside the report tab
      or if parts of the page are dynamically rendered.
    ==============================================================*/
    // Delegate because the modal is rendered outside the report tab and the
    // page loads/reloads jQuery in its layout footer.
    $(document)
        .off('submit.fuelNonCashPayment', '#non_cash_payment_form')
        .on('submit.fuelNonCashPayment', '#non_cash_payment_form', savePayment);

    // The submit button sits in the modal footer, outside the form element.
    // Explicitly submit the associated form so the save path is reliable.
    $(document)
        .off('click.fuelNonCashPayment', '#save_non_cash_payment')
        .on('click.fuelNonCashPayment', '#save_non_cash_payment', function (event) {
            event.preventDefault();
            console.debug('[Fuel non-cash] Save button clicked.');

            const form = document.getElementById('non_cash_payment_form');
            if (!form) {
                console.error('[Fuel non-cash] Form #non_cash_payment_form was not found.');
                return;
            }

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                savePayment(event);
            }
        });

    /*==============================================================
      DELETE PAYMENT
      Send the delete request, close the delete-confirmation modal, refresh data,
      and show a success modal instead of an inline notice.
    ==============================================================*/
    $('#confirm_delete_non_cash_payment').on('click', function () {
        const id = $(this).val();
        $.ajax({
            url: "{{ route('cashiers_report_non_cash_payment.delete') }}", type: 'POST',
            data: { non_cash_payment_id: id, _token: "{{ csrf_token() }}" },
            success: function () {
                bootstrap.Modal.getOrCreateInstance(deleteModal).hide();
                const dt = getTable(); if (dt) dt.ajax.reload(null, false);
                if (typeof LoadCashiersReportSummary === 'function') LoadCashiersReportSummary();

                // Use a modal notification instead of the inline delete notice.
                showNonCashPaymentSuccessModal('Payment deleted successfully.');
            },
            error: function (xhr) {
                const response = xhr.responseJSON || {};
                showPaymentErrorModal(response.error || response.message || `Unable to delete payment (HTTP ${xhr.status || 'error'}). Please try again.`);
                console.error('Non-Cash Payment delete failed.', xhr);
            }
        });
    });

    /*==============================================================
      EDIT ACTION
      Copy the selected row's values into the form and open the payment modal.
    ==============================================================*/
    $(document).on('click', '.edit-non-cash-payment', function () {
        const button = $(this);
        setMode(true);
        $('#non_cash_payment_id').val(button.attr('data-id'));
        $('#non_cash_payment_type').val(button.attr('data-payment-type'));
        $('#non_cash_payment_amount').val(button.attr('data-payment-amount'));
        $('#non_cash_payment_name').val(button.attr('data-payer-name'));
        $('#non_cash_payment_number').val(button.attr('data-payer-number'));
        $('#non_cash_payment_reference').val(button.attr('data-reference-number'));
        showModal(paymentModal);
    });

    /* Open the delete confirmation modal and store the selected payment ID. */
    $(document).on('click', '.delete-non-cash-payment', function () {
        $('#confirm_delete_non_cash_payment').val($(this).data('id'));
        showModal(deleteModal);
    });

    /*==============================================================
      ACTION DROPDOWN BEHAVIOR
      Manually toggle row action menus and close them when clicking elsewhere.
    ==============================================================*/
    $(document).on('click', '.non-cash-payment-menu-toggle', function (event) {
        event.preventDefault(); event.stopPropagation();
        const button = $(this), menu = button.siblings('.dropdown-menu'), open = !menu.hasClass('show');
        $('.non-cash-payment-actions .dropdown-menu').removeClass('show');
        $('.non-cash-payment-menu-toggle').attr('aria-expanded', 'false');
        menu.toggleClass('show', open); button.attr('aria-expanded', open ? 'true' : 'false');
    });
    // Close any open action dropdown when the user clicks outside it.
    $(document).on('click', function () {
        $('.non-cash-payment-actions .dropdown-menu').removeClass('show');
        $('.non-cash-payment-menu-toggle').attr('aria-expanded', 'false');
    });
    $(document).on('click', '.non-cash-payment-actions .dropdown-menu', function (event) { event.stopPropagation(); });
    /* Recalculate responsive table columns when the payment tab becomes visible. */
    $('#payment-tab, #fuel-non-cash-payment-tab').on('shown.bs.tab', function () {
        const dt = getTable(); if (dt) dt.columns.adjust().responsive.recalc();
    });

    // Clear a field's inline error as soon as the user edits that field.
    $(document).on('input.fuelNonCashPayment change.fuelNonCashPayment',
        '#non_cash_payment_type, #non_cash_payment_amount, #non_cash_payment_name, #non_cash_payment_number, #non_cash_payment_reference',
        function () {
            const fieldSelector = '#' + this.id;
            clearFieldError(fieldSelector);
        }
    );

    // Initialize/load the table when this script starts.
    getTable();
});
</script>
