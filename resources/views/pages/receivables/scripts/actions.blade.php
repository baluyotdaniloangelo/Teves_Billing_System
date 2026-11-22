<script>
(function (window, document, $) {
    'use strict';

    var token = document.querySelector('meta[name="csrf-token"]').content;
    var table = $('#getReceivablesListBilling');

    function notice(message) {
        var target = document.getElementById('br-list-feedback');
        if (!target) return;
        target.textContent = message;
        target.hidden = !message;
    }

    function reload() {
        if ($.fn.DataTable.isDataTable(table)) table.DataTable().ajax.reload(null, false);
    }

    function showModal(id) {
        window.bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).show();
    }

    function hideModal(id) {
        window.bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).hide();
    }

    function info(id) {
        return $.post('/receivable_info', { receivable_id: id, _token: token })
            .then(function (response) {
                if (!response || !response[0]) throw new Error('Receivable details were not found.');
                return response[0];
            });
    }

    function failed(message) {
        return function (error) {
            notice(error && error.message ? error.message : message);
        };
    }

    $(document).on('click', '.js-delete-receivable', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        notice('');
        info(id).then(function (record) {
            document.getElementById('deleteReceivableConfirmed').value = id;
            var details = {
                confirm_delete_billing_date: record.billing_date,
                confirm_delete_control_number: record.control_number,
                confirm_delete_client_info: record.client_name,
                confirm_delete_description: record.receivable_description,
                confirm_delete_amount: new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(Number(record.receivable_amount) || 0)
            };
            Object.keys(details).forEach(function (key) {
                document.getElementById(key).textContent = details[key] || '—';
            });
            showModal('ReceivableDeleteModal');
        }).catch(failed('Unable to load receivable details.'));
    });

    $('#deleteReceivableConfirmed').on('click', function () {
        var button = this;
        button.disabled = true;
        $.post('/delete_receivable_confirmed', {
            receivable_id: button.value,
            _token: token
        }).done(function () {
            hideModal('ReceivableDeleteModal');
            reload();
            notice('Receivable deleted.');
        }).fail(failed('Unable to delete the receivable.')).always(function () {
            button.disabled = false;
        });
    });

    window.receivable_print = function (id) {
        var selector = document.getElementById('receivable_print_' + id);
        if (!selector || !selector.value) return;
        var format = selector.value;
        selector.value = '';
        var simpleUrls = {
            PrintReceivables: "{{ URL::to('generate_receivable_pdf') }}",
            PrintStatement: "{{ URL::to('generate_receivable_soa_pdf') }}"
        };
        if (simpleUrls[format]) {
            window.open(simpleUrls[format] + '?' + $.param({receivable_id: id, _token: token}), '_blank');
            return;
        }
        var detailUrls = {
            PrintAll_Billing: "{{ URL::to('generate_billing_soa_receivable_pdf') }}",
            PrintBilling: "{{ URL::to('generate_receivable_covered_bill_pdf') }}"
        };
        if (!detailUrls[format]) return;
        info(id).then(function (record) {
            var params = {
                receivable_id: id,
                client_idx: record.client_id,
                start_date: record.billing_period_start,
                end_date: record.billing_period_end,
                company_header: record.company_header,
                less_per_liter: record.less_per_liter,
                withholding_tax_percentage: Number(record.receivable_withholding_tax_percentage) / 100,
                net_value_percentage: record.receivable_net_value_percentage,
                vat_value_percentage: Number(record.receivable_vat_value_percentage) / 100,
                _token: token
            };
            window.open(detailUrls[format] + '?' + $.param(params), '_blank');
        }).catch(failed('Unable to prepare the document.'));
    };

    window.LockReceivable = function (id) {
        notice('');
        info(id).then(function (record) {
            document.getElementById('LockConfirmed').value = id;
            var flags = String(record.receivable_lock_status || '00000').padEnd(3, '0');
            document.getElementById('lock_billing_information').checked = flags[0] === '1';
            document.getElementById('lock_billing_item').checked = flags[1] === '1';
            document.getElementById('lock_billing_payment_item').checked = flags[2] === '1';
            document.getElementById('lock_receivable_billing_date').textContent = record.billing_date || '—';
            document.getElementById('lock_receivable_confirm_control_number').textContent = record.control_number || '—';
            document.getElementById('lock_receivable_client_info').textContent = record.client_name || '—';
            document.getElementById('lock_receivable_amount').textContent = new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(Number(record.receivable_amount) || 0);
            document.getElementById('receivable_unlock_expiration').value =
                String(record.receivable_unlock_expiration || '').replace(' ', 'T').slice(0, 16);
            document.getElementById('receivable_unlock_expiration').classList.remove('is-invalid');
            document.getElementById('receivable_unlock_expirationError').textContent = '';
            showModal('BillingReceivableLockModal');
        }).catch(failed('Unable to load lock settings.'));
        return false;
    };

    $('#BillingReceivableLockStatusForm').on('submit', function (event) {
        event.preventDefault();
        var expiration = document.getElementById('receivable_unlock_expiration');
        var error = document.getElementById('receivable_unlock_expirationError');
        error.textContent = '';
        expiration.classList.remove('is-invalid');
        if (!expiration.value) {
            error.textContent = 'Choose an auto lock date and time.';
            expiration.classList.add('is-invalid');
            expiration.focus();
            return;
        }
        var button = document.getElementById('LockConfirmed');
        button.disabled = true;
        $.ajax({
            url: '/billing_receivables_lock_post',
            type: 'POST',
            data: {
                ReceivableID: button.value,
                lock_billing_information: document.getElementById('lock_billing_information').checked ? '1' : '0',
                lock_billing_item: document.getElementById('lock_billing_item').checked ? '1' : '0',
                lock_billing_payment_item: document.getElementById('lock_billing_payment_item').checked ? '1' : '0',
                lock_sales_order_information: '0',
                lock_sales_order_delivery: '0',
                receivable_unlock_expiration: expiration.value,
                lock_page: 'receivable_page',
                _token: token
            }
        }).done(function () {
            hideModal('BillingReceivableLockModal');
            reload();
            notice('Lock settings saved.');
        }).fail(function (xhr) {
            var message = xhr.responseJSON && xhr.responseJSON.errors &&
                xhr.responseJSON.errors.receivable_unlock_expiration;
            error.textContent = Array.isArray(message) ? message[0] : (message || 'Unable to save lock settings.');
            expiration.classList.add('is-invalid');
        }).always(function () {
            button.disabled = false;
        });
    });
})(window, document, window.jQuery);
</script>
