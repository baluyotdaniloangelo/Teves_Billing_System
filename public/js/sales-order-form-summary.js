(function (window, document) {
    'use strict';

    var timer;
    var request;
    var currency = new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function setText(root, selector, value) {
        var element = root.querySelector(selector);
        if (element) element.textContent = value;
    }

    function refresh() {
        var root = document.querySelector('.so-order-editor-content[data-sales-order-id]');
        var summary = root && root.querySelector('[data-summary-url]');
        if (!summary) return;
        if (request) request.abort();
        request = new AbortController();
        var url = new URL(summary.dataset.summaryUrl, window.location.origin);
        url.searchParams.set('sales_order_id', root.dataset.salesOrderId);
        window.fetch(url.toString(), { credentials: 'same-origin', signal: request.signal, headers: { Accept: 'application/json' } })
            .then(function (response) { if (!response.ok) throw new Error('Summary unavailable'); return response.json(); })
            .then(function (data) {
                if (!root.isConnected) return;
                setText(root, '#so-summary-total', '₱' + currency.format(data.order_total));
                setText(root, '#so-summary-paid', '₱' + currency.format(data.payments_received));
                setText(root, '#so-summary-balance', '₱' + currency.format(data.balance_due));
                setText(root, '#so-summary-payment-percent', Number(data.payment_percentage).toFixed(1) + '%');
                setText(root, '#so-summary-delivery-percent', data.delivery_progress + '%');
                setText(root, '#so-summary-delivery-value', '₱' + currency.format(data.delivered_value) + ' of ₱' + currency.format(data.delivery_basis) + ' in ordered items');
                setText(root, '#so-summary-status', data.status);
                var totalCard = root.querySelector('#so-summary-total')?.closest('.so-form-stat');
                if (totalCard) totalCard.querySelector('p').textContent = 'Total due for ' + data.item_count + (data.item_count === 1 ? ' item' : ' items');
                var progress = root.querySelector('.so-form-progress');
                if (progress) {
                    progress.setAttribute('aria-valuenow', String(data.delivery_progress));
                    progress.querySelector('span').style.width = data.delivery_progress + '%';
                }
            })
            .catch(function (error) { if (error.name !== 'AbortError') window.console.warn('Sales Order summary:', error); });
    }

    window.SalesOrderFormSummary = {
        refresh: refresh,
        schedule: function () {
            window.clearTimeout(timer);
            timer = window.setTimeout(refresh, 220);
        }
    };
})(window, document);
