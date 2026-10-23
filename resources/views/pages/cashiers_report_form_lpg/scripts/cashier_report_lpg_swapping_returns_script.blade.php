<script>
$(function () {
    const form = document.getElementById('CashierReportLpgSwappingForm');
    const client = document.getElementById('lpg_swapping_client_idx');
    const product = document.getElementById('replaced_product_idx');
    const quantity = document.getElementById('replaced_quantity');
    const clientOptions = Array.from(client.options);
    let productOptions = [], balances = [], request = null, version = 0;
    let loaded = false, failed = false, filtering = false, preferredProduct = 0;
    const mode = () => $('#lpg_swapping_entry_mode').val();
    const isReplacement = () => mode() !== 'RECEIVED';
    const format = value => Number(value || 0).toLocaleString('en-PH', {maximumFractionDigits: 2});
    const message = text => $(form).find('.lpg-container-form-error').text(text).removeClass('d-none');

    function restore(select, options, selected) {
        select.replaceChildren(...options);
        select.value = selected || '';
    }
    function currentBalances() {
        const rows = balances.map(row => ({...row}));
        // A legacy combined record's own return is included while editing that record.
        if (mode() === 'BOTH') {
            const clientId = Number(client.value || 0), productId = Number($('#received_product_idx').val() || 0), received = Number($('#received_quantity').val() || 0);
            if (clientId && productId && received > 0) {
                let row = rows.find(row => Number(row.client_idx) === clientId && Number(row.product_idx) === productId);
                if (!row) { row = {client_idx: clientId, product_idx: productId, received_quantity: 0, replaced_quantity: 0, available_quantity: 0}; rows.push(row); }
                row.received_quantity = Number(row.received_quantity || 0) + received;
                row.available_quantity = Number(row.available_quantity || 0) + received;
            }
        }
        const allowed = new Set(productOptions.map(option => Number(option.value)));
        // Keep customers and products that actually returned items visible even when fully replaced.
        return rows.filter(row => Number(row.received_quantity) > 0.0000001 && allowed.has(Number(row.product_idx)));
    }
    function selectedBalance() {
        return currentBalances().find(row => Number(row.client_idx) === Number(client.value) && Number(row.product_idx) === Number(product.value));
    }
    function updateQuantity() {
        quantity.setCustomValidity('');
        quantity.removeAttribute('max');
        if (!isReplacement() || !loaded) return;
        const row = selectedBalance();
        const available = Math.max(0, Number(row?.available_quantity || 0));
        quantity.max = String(available);
        if (Number(quantity.value || 0) - available > 0.0000001) quantity.setCustomValidity('Replacement quantity cannot exceed the available returned quantity.');
        $('#lpg-swapping-return-message').text(row
            ? 'Received / Damaged: ' + format(row.received_quantity) + ' / Already replaced: ' + format(row.replaced_quantity) + ' / Available: ' + format(available) + (available <= 0 ? '. This received quantity is already fully replaced.' : '. Current report only.')
            : 'Select a customer, then choose one of their returned items.');
        $('#save-lpg-swapping').prop('disabled', !row || available <= 0 || form.dataset.containerSaving === '1');
    }
    function render() {
        const active = isReplacement();
        $('#lpg-swapping-return-info').toggleClass('d-none', !active);
        filtering = true;
        if (!active) {
            restore(client, clientOptions, client.value);
            restore(product, productOptions, product.value);
            client.disabled = false;
            product.disabled = true;
            quantity.setCustomValidity(''); quantity.removeAttribute('max');
            $('#save-lpg-swapping').prop('disabled', form.dataset.containerSaving === '1');
        } else if (!loaded) {
            client.disabled = true; product.disabled = true;
            $('#save-lpg-swapping').prop('disabled', true);
            $('#lpg-swapping-return-message').text(failed ? 'Unable to load returned items. Click Refresh Returns to retry.' : 'Loading returned items from the current report...');
        } else {
            const rows = currentBalances();
            const selectedClient = client.value;
            const selectedProduct = product.value || String(preferredProduct || '');
            const clients = new Set(rows.map(row => Number(row.client_idx)));
            if (mode() === 'BOTH' && selectedClient) clients.add(Number(selectedClient));
            restore(client, clientOptions.filter(option => !option.value || clients.has(Number(option.value))), selectedClient);
            const products = new Set(rows.filter(row => Number(row.client_idx) === Number(client.value)).map(row => Number(row.product_idx)));
            restore(product, productOptions.filter(option => !option.value || products.has(Number(option.value))), selectedProduct);
            client.disabled = false; product.disabled = false;
            $('#save-lpg-swapping').prop('disabled', !clients.size || form.dataset.containerSaving === '1');
            updateQuantity();
            if (!rows.length) $('#lpg-swapping-return-message').text('No eligible received items are recorded in this report for these product choices. Record a Swapping Received entry or a Kitchen Damaged entry with its client / account first.');
        }
        $(product).trigger('change');
        filtering = false;
    }
    function load() {
        const current = ++version;
        if (request) request.abort();
        loaded = false; failed = false;
        request = $.ajax({url: "{{ route('GetLpgSwappingReturnBalances') }}", type: 'POST', data: {
            _token: "{{ csrf_token() }}", cashiers_report_id: {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }}, exclude_id: Number($('#cashiers_report_lpg_swapping_id').val() || 0)
        }}).done(response => {
            if (current !== version) return;
            balances = response.data || []; loaded = true; failed = false; render();
        }).fail((xhr, status) => {
            if (current !== version || status === 'abort') return;
            failed = true;
            message(Object.values(xhr.responseJSON?.errors || {}).flat().join(' ') || xhr.responseJSON?.message || 'Unable to load returned items.');
            render();
        }).always(() => { if (current === version) request = null; });
        render();
    }
    window.LpgSwappingReturns = {
        open: function (row) {
            ++version; if (request) request.abort(); request = null;
            loaded = false; failed = false; balances = []; preferredProduct = Number(row?.replaced_product_idx || 0);
            restore(client, clientOptions, client.value);
            productOptions = Array.from(product.options);
            quantity.setCustomValidity(''); quantity.removeAttribute('max');
        },
        syncMode: function () {
            if (isReplacement() && !loaded && !request && !failed) load();
            else render();
        },
        prefill: function (productId) { preferredProduct = Number(productId || 0); if (loaded) render(); },
        beforeSubmit: function () {
            if (!isReplacement()) return true;
            if (!loaded || request) { message('Wait for returned items to load, or click Refresh Returns to retry.'); return false; }
            updateQuantity();
            const row = selectedBalance();
            if (!row) { message('Select a customer and one of their returned products in this report.'); return false; }
            if (Number(row.available_quantity) <= 0) { message('This returned item is already fully replaced. No quantity is available for another replacement.'); return false; }
            return true;
        }
    };
    $(client).on('change.swappingReturns', function () { if (!filtering && isReplacement()) { preferredProduct = 0; product.value = ''; render(); } });
    $(product).on('change.swappingReturns', function () { if (!filtering) { preferredProduct = Number(product.value || 0); updateQuantity(); } });
    $(quantity).on('input change', updateQuantity);
    $('#received_product_idx, #received_quantity').on('input change', () => { if (mode() === 'BOTH' && loaded) render(); });
    $('#refresh-lpg-swapping-returns').on('click', load);
    $('#CashierReportLpgSwappingModal').on('hidden.bs.modal', function () {
        ++version; if (request) request.abort(); request = null;
        restore(client, clientOptions, client.value);
        restore(product, productOptions, product.value);
        quantity.setCustomValidity(''); quantity.removeAttribute('max');
    });
});
</script>
