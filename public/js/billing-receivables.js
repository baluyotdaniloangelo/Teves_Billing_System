(function (window, document, $) {
    'use strict';

    $(function () {
        var page = document.querySelector('.br-receivables-page');
        var tableElement = document.getElementById('getReceivablesListBilling');
        if (!page || !tableElement || !$.fn.DataTable) return;

        var branch = document.getElementById('br-filter-branch');
        var status = document.getElementById('br-filter-status');
        var period = document.getElementById('br-filter-period');
        var search = document.getElementById('br-filter-search');
        var feedback = document.getElementById('br-list-feedback');
        var viewModal = document.getElementById('BillingReceivableViewModal');
        var searchTimer;
        var currency = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' });

        function number(value) {
            var parsed = Number(value);
            return Number.isFinite(parsed) ? parsed : 0;
        }

        function money(value) {
            return currency.format(number(value));
        }

        function escaped(value) {
            return $('<div>').text(value == null ? '' : String(value)).html();
        }

        function balanceOf(row) {
            return row.receivable_remaining_balance == null
                ? number(row.receivable_amount)
                : number(row.receivable_remaining_balance);
        }

        function statusOf(row) {
            var due = number(row.receivable_amount);
            var balance = balanceOf(row);
            if (due > 0 && balance <= 0) return 'Paid';
            if (balance > 0 && balance < due) return 'Partial';
            return 'Pending';
        }

        function showSummary(summary) {
            if (!summary) return;
            ['total_due', 'collected', 'outstanding', 'open_accounts'].forEach(function (key) {
                var target = page.querySelector('[data-br-summary="' + key + '"]');
                if (!target) return;
                target.textContent = key === 'open_accounts'
                    ? new Intl.NumberFormat('en-PH').format(number(summary[key]))
                    : money(summary[key]);
            });
        }

        function showFeedback(message) {
            feedback.textContent = message;
            feedback.hidden = !message;
        }

        var table = $(tableElement).DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,
            stateSave: false,
            pageLength: 10,
            lengthMenu: [10, 20, 50],
            ajax: {
                url: tableElement.dataset.listUrl,
                data: function (request) {
                    request.branch = branch.value;
                    request.status = status.value;
                    request.period = period.value;
                    request.filter_search = search.value.trim();
                },
                dataSrc: function (response) {
                    showFeedback('');
                    showSummary(response.summary);
                    return response.data || [];
                },
                error: function () {
                    showFeedback('Unable to load receivables. Please refresh and try again.');
                }
            },
            columns: [
                {
                    data: 'billing_date', name: 'teves_receivable_table.billing_date',
                    render: function (value, type) { return type === 'display' ? escaped(value) : value; }
                },
                {
                    data: 'control_number', name: 'teves_receivable_table.control_number',
                    render: function (value, type) { return type === 'display' ? '<strong>' + escaped(value) + '</strong>' : value; }
                },
                {
                    data: 'client_name', name: 'teves_client_table.client_name',
                    render: function (value, type) { return type === 'display' ? escaped(value) : value; }
                },
                {
                    data: 'receivable_amount', name: 'teves_receivable_table.receivable_amount', className: 'br-numeric',
                    render: function (value, type) { return type === 'display' ? money(value) : value; }
                },
                {
                    data: 'receivable_remaining_balance', name: 'teves_receivable_table.receivable_remaining_balance', className: 'br-numeric',
                    render: function (value, type, row) { return type === 'display' ? money(balanceOf(row)) : value; }
                },
                {
                    data: null, orderable: false, searchable: false,
                    render: function (row, type) {
                        if (type !== 'display') return statusOf(row);
                        var label = statusOf(row);
                        return '<span class="br-status br-status-' + label.toLowerCase() + '">' + label + '</span>';
                    }
                },
                { data: 'action_print', orderable: false, searchable: false, className: 'br-generate' },
                {
                    data: 'action', orderable: false, searchable: false, className: 'br-actions-cell',
                    render: function (value, type) {
                        if (type !== 'display') return '';
                        return '<div class="br-row-actions"><button type="button" class="btn br-view-button" data-br-view-row>View</button>' + (value || '') + '</div>';
                    }
                }
            ],
            order: [[0, 'desc']]
        });

        [branch, status, period].forEach(function (filter) {
            filter.addEventListener('change', function () { table.ajax.reload(); });
        });
        search.addEventListener('input', function () {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(function () { table.ajax.reload(); }, 300);
        });
        document.getElementById('br-reset-filters').addEventListener('click', function () {
            branch.value = '';
            status.value = '';
            period.value = '';
            search.value = '';
            window.clearTimeout(searchTimer);
            table.ajax.reload();
        });

        $(tableElement).on('click', '[data-br-view-row]', function () {
            var rowElement = $(this).closest('tr');
            if (rowElement.hasClass('child')) rowElement = rowElement.prev();
            var row = table.row(rowElement).data();
            if (!row) return;

            var due = number(row.receivable_amount);
            var balance = balanceOf(row);
            var values = {
                account: row.client_name,
                control: row.control_number,
                status: statusOf(row),
                date: row.billing_date,
                branch: [row.branch_code, row.branch_name].filter(Boolean).join(' — ') || '—',
                due: money(due),
                collected: money(Math.max(0, due - balance)),
                balance: money(balance)
            };
            Object.keys(values).forEach(function (key) {
                var target = viewModal.querySelector('[data-br-view="' + key + '"]');
                if (target) target.textContent = values[key] || '—';
            });
            var badge = viewModal.querySelector('[data-br-view="status"]');
            badge.className = 'br-status br-status-' + values.status.toLowerCase();

            var detailUrl = new URL(page.dataset.detailUrl, window.location.href);
            detailUrl.searchParams.set('receivable_id', row.receivable_id);
            detailUrl.searchParams.set('tab', 'product');
            viewModal.querySelector('[data-br-view="open"]').href = detailUrl.toString();
            window.bootstrap.Modal.getOrCreateInstance(viewModal).show();
        });

        if ('ResizeObserver' in window) {
            new ResizeObserver(function () { table.columns.adjust(); }).observe(table.table().container());
        }
    });
})(window, document, window.jQuery);
