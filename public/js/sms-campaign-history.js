(function () {
    'use strict';
    const dialog = document.getElementById('sms-history-dialog');
    if (!dialog) return;
    const content = document.getElementById('sms-history-content');
    const closeButton = document.getElementById('sms-history-close');
    let activeRequest = null, requestNumber = 0, previousTrigger = null, currentUrl = '';

    function open() {
        if (!dialog.open) dialog.showModal();
        document.body.classList.add('sms-history-open');
    }
    async function load(url) {
        currentUrl = url;
        const number = ++requestNumber;
        if (activeRequest) activeRequest.abort();
        activeRequest = new AbortController();
        const loading = document.createElement('p');
        loading.className = 'sms-history-state'; loading.setAttribute('role', 'status'); loading.textContent = 'Loading campaign details…';
        content.replaceChildren(loading); content.setAttribute('aria-busy', 'true');
        open();
        try {
            const response = await fetch(url, { headers: { Accept: 'text/html', 'X-Requested-With': 'XMLHttpRequest' }, signal: activeRequest.signal });
            if (response.redirected || response.status === 401 || response.status === 419) throw new Error('Your session has expired. Refresh this page and sign in again.');
            if (!response.ok) throw new Error(response.status === 404 ? 'This campaign could not be found.' : 'Unable to load campaign details. Try again.');
            const html = await response.text();
            if (number !== requestNumber || !dialog.open) return;
            content.innerHTML = html;
            content.scrollTop = 0;
        } catch (error) {
            if (error.name === 'AbortError' || number !== requestNumber || !dialog.open) return;
            const notice = document.createElement('p'); notice.className = 'sms-history-state'; notice.setAttribute('role', 'alert'); notice.textContent = error.message;
            const retry = document.createElement('button'); retry.type = 'button'; retry.className = 'fuel-button'; retry.dataset.smsHistoryRetry = '1'; retry.textContent = 'Try again';
            content.replaceChildren(notice, retry);
        } finally {
            if (number === requestNumber) content.setAttribute('aria-busy', 'false');
        }
    }
    document.addEventListener('click', function (event) {
        const link = event.target.closest('a[data-sms-campaign-url]');
        if (!link || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault(); previousTrigger = link; load(link.dataset.smsCampaignUrl);
    });
    content.addEventListener('click', function (event) {
        const page = event.target.closest('.sms-pagination a');
        if (page) { event.preventDefault(); load(page.href); return; }
        if (event.target.closest('[data-sms-history-retry]')) load(currentUrl);
    });
    closeButton.addEventListener('click', function () { dialog.close(); });
    dialog.addEventListener('click', function (event) {
        if (event.target !== dialog) return;
        const bounds = dialog.getBoundingClientRect();
        if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dialog.close();
    });
    dialog.addEventListener('close', function () {
        ++requestNumber; if (activeRequest) activeRequest.abort();
        document.body.classList.remove('sms-history-open');
        content.setAttribute('aria-busy', 'false');
        if (previousTrigger?.isConnected) previousTrigger.focus();
    });
    if (dialog.dataset.autoOpen === '1') open();
}());
