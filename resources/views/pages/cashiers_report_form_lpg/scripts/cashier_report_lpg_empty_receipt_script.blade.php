<script>
$(function () {
    let lookup = null, version = 0, loadedClient = null;
    const mode = document.getElementById('ar_so_empty_receipt_mode');
    const swap = $('#ar_so_empty_swapping_idx');
    const quantityInput = document.getElementById('ar_so_empty_received_quantity');
    function isRefill() { return $('#ar_so_type').val() !== 'Return' && $('#ar_so_stock_type').val() === 'REFILL_SWAP'; }
    function sync() {
        const active = isRefill();
        const linked = active && mode.value === 'SWAPPING';
        $('#ar-so-empty-receipt-fields').toggleClass('d-none', !active);
        mode.disabled = !active;
        if (!linked) mode.setCustomValidity('');
        $('#ar-so-empty-swap-field').toggleClass('d-none', !linked);
        $('#ar-so-empty-direct-fields').toggleClass('d-none', linked);
        swap.prop('disabled', !linked).prop('required', linked);
        $('#ar_so_empty_received_product_idx').prop('disabled', !active || linked).prop('required', active && !linked && Number(quantityInput.value || 0) > 0);
        $(quantityInput).prop('disabled', !active || linked).prop('required', active && !linked);
        if (active && !linked && Number($('#cashiers_report_lpg_ar_so_id').val() || 0) === 0) {
            const product = findLpgArSoDatalistOption('lpgArSoProductList', $('#ar_so_product').val());
            if (!$('#ar_so_empty_received_product_idx').val() && product) $('#ar_so_empty_received_product_idx').val(product.attr('data-id')).trigger('change');
            const qty = Number($('#ar_so_quantity').val());
            if (!quantityInput.dataset.userSet && $('#ar_so_quantity').val() !== '' && Number.isInteger(qty)) quantityInput.value = qty;
        }
        $('#ar_so_empty_received_product_idx').prop('required', active && !linked && Number(quantityInput.value || 0) > 0);
    }
    function loadSwaps(selectedId) {
        if (!isRefill() || mode.value !== 'SWAPPING') return;
        const clientId = findLpgArSoDatalistId('lpgArSoClientNameList', $('#ar_so_account_id').val());
        const current = ++version;
        if (lookup) lookup.abort();
        loadedClient = clientId || null;
        swap.empty().append($('<option>').val('').text('Select swapping record'));
        mode.setCustomValidity(clientId ? 'Wait for swapping records to load.' : 'Select a client before linking a swapping record.');
        $('#ar-so-empty-swap-note').text(clientId ? 'Loading swapping records...' : 'Select the account name first.');
        if (!clientId) return;
        lookup = $.post("{{ route('GetLpgSwappingList') }}", {CashiersReportId: {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }}, cashiers_report_id: {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }}})
            .done(response => {
                if (current !== version) return;
                const rows = Array.isArray(response) ? response : (response.data || []);
                const allowedProducts = new Set($('#ar_so_empty_received_product_idx option').map(function () { return Number(this.value); }).get());
                rows.filter(row => String(row.client_idx) === String(clientId) && row.received_stock_state === 'EMPTY' && allowedProducts.has(Number(row.received_product_idx))).forEach(row => {
                    const name = [row.received_product_name, row.received_product_unit_measurement].filter(Boolean).join(' ');
                    swap.append($('<option>').val(row.cashiers_report_lpg_swapping_id).text('#' + row.cashiers_report_lpg_swapping_id + ' — ' + name + ' — ' + row.received_quantity + ' pcs'));
                });
                if (selectedId) swap.val(String(selectedId));
                mode.setCustomValidity('');
                $('#ar-so-empty-swap-note').text('Only records for this client with Received = Empty are listed. The same receipt appears in both columns and counts once in ending stock.');
            }).fail((xhr, status) => {
                if (current !== version || status === 'abort') return;
                mode.setCustomValidity('Unable to load swapping records. Select Receipt Source again to retry.');
                $('#ar-so-empty-swap-note').text(xhr.responseJSON?.message || 'Unable to load swapping records.');
            }).always(() => { if (current === version) lookup = null; });
    }
    window.LpgEmptyReceiptForm = {
        reset: function () {
            ++version; if (lookup) lookup.abort(); lookup = null; loadedClient = null;
            mode.value = 'DIRECT'; mode.setCustomValidity(''); quantityInput.value = ''; delete quantityInput.dataset.userSet;
            $('#ar_so_empty_received_product_idx').val('').trigger('change'); swap.empty().append($('<option>').val('').text('Select swapping record'));
            sync();
        },
        load: function (row) {
            ++version; if (lookup) lookup.abort(); lookup = null; loadedClient = null;
            quantityInput.dataset.userSet = '1'; quantityInput.value = row.empty_received_quantity ?? '';
            $('#ar_so_empty_received_product_idx').val(row.empty_received_product_idx || '').trigger('change');
            mode.value = row.empty_swapping_idx ? 'SWAPPING' : 'DIRECT'; sync();
            if (row.empty_swapping_idx) loadSwaps(row.empty_swapping_idx);
        },
        sync: sync
    };
    $('#ar_so_type, #ar_so_stock_type, #ar_so_product, #ar_so_quantity').on('input change', sync);
    $('#ar_so_empty_received_quantity').on('input change', function () { quantityInput.dataset.userSet = '1'; sync(); });
    $(mode).on('change', function () { sync(); if (mode.value === 'SWAPPING') loadSwaps(); });
    $('#ar_so_account_id').on('input change', function () {
        const clientId = findLpgArSoDatalistId('lpgArSoClientNameList', this.value);
        if (isRefill() && mode.value === 'SWAPPING' && clientId !== loadedClient) loadSwaps();
    });
    $('#CashierReportLpgArSoModal').on('hidden.bs.modal', function () { ++version; if (lookup) lookup.abort(); lookup = null; });
    sync();
});
</script>
