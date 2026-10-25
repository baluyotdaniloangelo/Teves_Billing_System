(function (root) {
    'use strict';

    let activeRequest = null;

    function dashboardRoot() {
        return document.querySelector('main.fuel-dashboard');
    }

    function refresh(url, options) {
        options = options || {};
        const target = new URL(url, root.location.href);
        if (target.origin !== root.location.origin) return;

        if (activeRequest) activeRequest.abort();
        const requestController = new AbortController();
        activeRequest = requestController;

        const current = dashboardRoot();
        if (current) {
            current.classList.add('is-filtering');
            current.setAttribute('aria-busy', 'true');
            const submit = current.querySelector('form button[type="submit"]');
            if (submit) {
                submit.disabled = true;
                submit.dataset.originalLabel = submit.textContent.trim();
                submit.textContent = 'Updating…';
            }
        }

        const body = options.body || null;
        const method = options.method || 'POST';
        const csrf = document.querySelector('meta[name="csrf-token"]');
        return root.fetch(target.pathname + target.search, {
            method: method,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
                ...(csrf ? { 'X-CSRF-TOKEN': csrf.content } : {})
            },
            body: method === 'GET' ? undefined : body,
            signal: requestController.signal
        }).then(response => {
            if (!response.ok) throw new Error('Dashboard request failed');
            return response.text();
        }).then(html => {
            const parsed = new DOMParser().parseFromString(html, 'text/html');
            const next = parsed.querySelector('main.fuel-dashboard');
            if (!next) throw new Error('Dashboard content was not returned');

            const oldFuelData = document.getElementById('fuel-dashboard-data');
            const nextFuelData = parsed.getElementById('fuel-dashboard-data');
            if (nextFuelData && oldFuelData) oldFuelData.replaceWith(nextFuelData);

            if (root.FuelDashboard) root.FuelDashboard.destroy();
            if (root.SalesReceivablesDashboard) root.SalesReceivablesDashboard.destroy();
            if (root.FuelSalesVolumeDashboard) root.FuelSalesVolumeDashboard.destroy();
            if (root.ProcurementExpensesDashboard) root.ProcurementExpensesDashboard.destroy();
            const oldMain = dashboardRoot();
            if (oldMain) oldMain.replaceWith(next);

            if (next.classList.contains('sr-dashboard')) {
                if (root.SalesReceivablesDashboard) root.SalesReceivablesDashboard.init();
            } else if (next.classList.contains('fsv-dashboard')) {
                if (root.FuelSalesVolumeDashboard) root.FuelSalesVolumeDashboard.init();
            } else if (next.classList.contains('pe-dashboard')) {
                if (root.ProcurementExpensesDashboard) root.ProcurementExpensesDashboard.init();
            } else if (root.FuelDashboard) {
                root.FuelDashboard.init();
            }
        }).catch(error => {
            if (error.name === 'AbortError') return;
            // Keep filtering usable if the server returns a full-page error or fetch is unavailable.
            root.location.assign(target.href);
        }).finally(() => {
            if (activeRequest === requestController) activeRequest = null;
        });
    }

    document.addEventListener('submit', event => {
        const form = event.target.closest('#fuel-filters, .sr-filterbar');
        if (!form) return;
        event.preventDefault();
        refresh(form.action, { method: 'POST', body: new FormData(form) });
    });

    document.addEventListener('click', event => {
        const reset = event.target.closest('.fuel-dashboard .fuel-reset');
        if (!reset) return;
        event.preventDefault();
        refresh(reset.href, { method: 'POST' });
    });

    // Remove query parameters left by an earlier GET-based filter submission.
    if (root.location.search) root.history.replaceState({}, '', root.location.pathname + root.location.hash);
}(window));
