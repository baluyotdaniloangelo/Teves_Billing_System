(function (root) {
    'use strict';

    const script = document.getElementById('user-presence-client');
    const csrf = document.querySelector('meta[name="csrf-token"]');
    if (!script || !csrf || !root.fetch) return;
    let running = false;
    let stopped = false;
    let lastAttempt = 0;
    const interval = 60000;

    async function heartbeat() {
        if (stopped || running || document.visibilityState === 'hidden' || root.navigator.onLine === false || Date.now() - lastAttempt < interval) return;
        running = true;
        lastAttempt = Date.now();
        try {
            const response = await root.fetch(script.dataset.heartbeatUrl, {
                method: 'POST', credentials: 'same-origin', cache: 'no-store',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf.content }
            });
            if (response.redirected || [401, 403, 419].includes(response.status)) { stopped = true; return; }
            if (!response.ok) return;
            const result = await response.json();
            if (result.tracking_available === false) stopped = true;
        } catch (error) {
            // A temporary connection failure must not interrupt a billing form.
        } finally { running = false; }
    }

    root.setInterval(heartbeat, interval);
    document.addEventListener('visibilitychange', heartbeat);
    root.addEventListener('online', heartbeat);
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', heartbeat, { once: true });
    else heartbeat();
}(window));
