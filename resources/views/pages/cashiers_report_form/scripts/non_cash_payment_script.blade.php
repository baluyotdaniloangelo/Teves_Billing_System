<script>
$(function () {
    const table = $('#non_cash_payments_table');
    const paymentModal = document.getElementById('nonCashPaymentModal');
    const deleteModal = document.getElementById('deleteNonCashPaymentModal');

    function escapeAttribute(value) {
        return $('<div>').text(value == null ? '' : String(value)).html().replace(/"/g, '&quot;');
    }

    function formatPaymentType(value) {
        return String(value || '').replace(/_/g, ' ').toUpperCase();
    }

    function getTable() {
        if (!$.fn.DataTable || !$.fn.dataTable) {
            console.error('DataTables is not available for Non-Cash Payments.');
            return null;
        }
        if ($.fn.DataTable.isDataTable(table)) return table.DataTable();

        return table.DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('cashiers_report_non_cash_payment.list') }}",
                type: 'POST',
                data: function (request) {
                    request.cash_report_id = {{ $CashiersReportId }};
                    request._token = "{{ csrf_token() }}";
                },
                dataSrc: function (response) {
                    return Array.isArray(response) ? response : (response && Array.isArray(response.data) ? response.data : []);
                },
                error: function (xhr) { console.error('Non-Cash Payments load failed.', xhr); }
            },
            columns: [
                { data: 'payment_type', render: function (value, type) { return type === 'display' ? $('<span>').text(formatPaymentType(value)).html() : value; } },
                { data: 'payment_amount', className: 'text-end', render: function (value, type) { return type === 'display' ? '₱ ' + (Number(value) || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : value; } },
                { data: 'payer_name', render: $.fn.dataTable.render.text() },
                { data: 'payer_number', render: $.fn.dataTable.render.text() },
                { data: 'reference_number', render: $.fn.dataTable.render.text() },
                {
                    data: 'non_cash_payment_id', orderable: false, searchable: false, className: 'text-center',
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

    function setMode(isEdit) {
        const form = document.getElementById('non_cash_payment_form');
        if (!form) return;
        form.reset();
        form.classList.remove('was-validated');
        $('#non_cash_payment_error').addClass('d-none').text('');
        $('#non_cash_payment_id').val('0');
        $('#nonCashPaymentModalTitle').text(isEdit ? 'Edit Non-Cash Payment' : 'Add Non-Cash Payment');
        $('#save_non_cash_payment span').text(isEdit ? 'Update Payment' : 'Save Payment');
    }

    function showSaveError(xhr) {
        const response = xhr.responseJSON || {};
        const validationErrors = response.errors || {};
        const details = Object.values(validationErrors).flat().filter(Boolean).join(' ');
        const message = [response.message, details].filter(Boolean).join(' ')
            || `Unable to save payment (HTTP ${xhr.status || 'error'}). Check that all fields are valid, then try again.`;

        $('#non_cash_payment_error').text(message).removeClass('d-none');
        console.error('Non-Cash Payment save failed.', xhr);
    }

    function showModal(element) {
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(element).show();
        } else {
            console.error('Bootstrap modal plugin is unavailable.');
        }
    }

    function savePayment(event) {
        event.preventDefault();
        console.debug('[Fuel non-cash] Form submit reached.');
        const form = document.getElementById('non_cash_payment_form');
        if (!form) return;
        if (!form.checkValidity()) {
            console.debug('[Fuel non-cash] Submit blocked: required fields are incomplete or invalid.');
            form.reportValidity();
            $('#non_cash_payment_error')
                .text('Please complete all required fields before saving.')
                .removeClass('d-none');
            return;
        }
        $('#non_cash_payment_error').addClass('d-none').text('');
        console.debug('[Fuel non-cash] Sending save request.');
        $.ajax({
            url: "{{ route('cashiers_report_non_cash_payment.save') }}", type: 'POST',
            data: {
                non_cash_payment_id: $('#non_cash_payment_id').val() || 0,
                cash_report_id: {{ $CashiersReportId }},
                payment_type: $('#non_cash_payment_type').val(),
                payment_amount: $('#non_cash_payment_amount').val(),
                payer_name: $('#non_cash_payment_name').val(),
                payer_number: $('#non_cash_payment_number').val(),
                reference_number: $('#non_cash_payment_reference').val(),
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function () {
                $('#non_cash_payment_error').addClass('d-none').text('');
                $('#save_non_cash_payment').prop('disabled', true).find('span').text('Saving...');
            },
            success: function (response) {
                bootstrap.Modal.getOrCreateInstance(paymentModal).hide();
                const dt = getTable(); if (dt) dt.ajax.reload(null, false);
                if (typeof LoadCashiersReportSummary === 'function') LoadCashiersReportSummary();
                $('#switch_notice_on').show(); $('#sw_on').text(response.success || 'Payment saved.');
                setTimeout(function () { $('#switch_notice_on').fadeOut('fast'); }, 1200);
            },
            error: showSaveError,
            complete: function () {
                $('#save_non_cash_payment').prop('disabled', false)
                    .find('span').text(Number($('#non_cash_payment_id').val() || 0) > 0 ? 'Update Payment' : 'Save Payment');
            }
        });
    }

    $('#add_non_cash_payment').on('click', function () { setMode(false); });
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
    $('#confirm_delete_non_cash_payment').on('click', function () {
        const id = $(this).val();
        $.ajax({
            url: "{{ route('cashiers_report_non_cash_payment.delete') }}", type: 'POST',
            data: { non_cash_payment_id: id, _token: "{{ csrf_token() }}" },
            success: function () {
                bootstrap.Modal.getOrCreateInstance(deleteModal).hide();
                const dt = getTable(); if (dt) dt.ajax.reload(null, false);
                if (typeof LoadCashiersReportSummary === 'function') LoadCashiersReportSummary();
                $('#switch_notice_off').show(); $('#sw_off').text('Deleted');
                setTimeout(function () { $('#switch_notice_off').fadeOut('slow'); }, 1200);
            },
            error: function (xhr) { console.error('Non-Cash Payment delete failed.', xhr); }
        });
    });

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

    $(document).on('click', '.delete-non-cash-payment', function () {
        $('#confirm_delete_non_cash_payment').val($(this).data('id'));
        showModal(deleteModal);
    });

    $(document).on('click', '.non-cash-payment-menu-toggle', function (event) {
        event.preventDefault(); event.stopPropagation();
        const button = $(this), menu = button.siblings('.dropdown-menu'), open = !menu.hasClass('show');
        $('.non-cash-payment-actions .dropdown-menu').removeClass('show');
        $('.non-cash-payment-menu-toggle').attr('aria-expanded', 'false');
        menu.toggleClass('show', open); button.attr('aria-expanded', open ? 'true' : 'false');
    });
    $(document).on('click', function () {
        $('.non-cash-payment-actions .dropdown-menu').removeClass('show');
        $('.non-cash-payment-menu-toggle').attr('aria-expanded', 'false');
    });
    $(document).on('click', '.non-cash-payment-actions .dropdown-menu', function (event) { event.stopPropagation(); });
    $('#payment-tab, #fuel-non-cash-payment-tab').on('shown.bs.tab', function () {
        const dt = getTable(); if (dt) dt.columns.adjust().responsive.recalc();
    });

    getTable();
});
</script>
