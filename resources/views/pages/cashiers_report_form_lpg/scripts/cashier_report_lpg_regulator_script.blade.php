<script>
$(function () {
    const reportId = {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }};
    const productIds = @json(\App\Services\LpgRegulatorProducts::ids());
    const showInventoryDetails = @json((bool) config('lpg_inventory.regulator_inventory_details_enabled', false));
    const allFilters = {BEGINNING: 'Beginning Inventory', SWAP_IN: 'Swap Received', SWAP_OUT: 'Swap Issued', OTHER_IN: 'Other Received', OTHER_OUT: 'Other Issued'};
    const filters = showInventoryDetails ? allFilters : {SWAP_IN: 'Swap Received', SWAP_OUT: 'Swap Issued'};
    const columnCount = showInventoryDetails ? 7 : 3;
    const movements = {BEGINNING: 'Beginning Inventory', DELIVERY: 'Delivery', BO_REPLACEMENT: 'B.O. Replacement', SWAP_IN: 'Swap Received', SWAP_OUT: 'Swap Issued', BORROW: 'On-loan Borrow', LOAN_RETURN: 'On-loan Return', SALE: 'Sale', RETURN: 'Sales Return', EXPENSE: 'Product Expense'};
    const headers = {'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json'};
    const modal = document.getElementById('LpgRegulatorSourcesModal');
    const escape = value => $('<div>').text(value == null ? '' : String(value)).html().replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    const quantity = value => Number(value || 0).toLocaleString('en-PH', {maximumFractionDigits: 2});
    const label = row => row.product_name || 'Unavailable product';
    let summaryRequest = null, sourceRequest = null, summaryVersion = 0, sourceVersion = 0, reloadTimer = null, sourceRows = [];

    function error(selector, xhr, fallback) {
        $(selector).text(Object.values(xhr.responseJSON?.errors || {}).flat().join(' ') || xhr.responseJSON?.message || fallback).removeClass('d-none');
    }
    function sourceCell(row, mode) {
        const value = mode === 'BEGINNING' && !row.beginning_count ? 'Not set' : quantity(row[mode.toLowerCase() + '_quantity']);
        return '<td class="text-end"><button type="button" class="btn btn-sm btn-link lpg-regulator-view" data-source-mode="' + mode + '" data-product-id="' + Number(row.product_idx) + '">' + value + '</button></td>';
    }
    function reload() {
        clearTimeout(reloadTimer);
        const version = ++summaryVersion;
        if (summaryRequest) summaryRequest.abort();
        $('#refresh-lpg-regulators').prop('disabled', true);
        $('#lpg-regulator-error').addClass('d-none');
        summaryRequest = $.ajax({url: "{{ route('GetLpgRegulatorSummary') }}", type: 'POST', headers, data: {cashiers_report_id: reportId}})
            .done(response => {
                if (version !== summaryVersion) return;
                const rows = response.data || [];
                $('#LpgRegulatorInventoryTable tbody').html(rows.length ? rows.map(row => {
                    const endingCell = showInventoryDetails ? '<td class="text-end fw-bold ' + (row.ending_quantity < 0 ? 'text-danger' : '') + '">' + (row.ending_quantity == null ? 'Set beginning stock' : quantity(row.ending_quantity)) + '</td>' : '';
                    return '<tr><td>' + escape(label(row)) + '</td>' + Object.keys(filters).map(mode => sourceCell(row, mode)).join('') + endingCell + '</tr>';
                }).join('') : '<tr><td colspan="' + columnCount + '" class="text-center text-muted py-3">No regulator products configured.</td></tr>');
            }).fail((xhr, status) => {
                if (version !== summaryVersion || status === 'abort') return;
                $('#LpgRegulatorInventoryTable tbody').html('<tr><td colspan="' + columnCount + '" class="text-center py-3">Regulator totals could not be loaded.</td></tr>');
                error('#lpg-regulator-error', xhr, 'Unable to load regulator inventory.');
            }).always(() => { if (version === summaryVersion) { summaryRequest = null; $('#refresh-lpg-regulators').prop('disabled', false); } });
    }
    function sources(mode, productId) {
        return $.ajax({url: "{{ route('GetLpgRegulatorSources') }}", type: 'POST', headers, data: {cashiers_report_id: reportId, mode, product_idx: productId || ''}});
    }
    function afterClose(callback) {
        if (modal.classList.contains('show')) { $(modal).one('hidden.bs.modal', callback); bootstrap.Modal.getOrCreateInstance(modal).hide(); }
        else callback();
    }
    function edit(row, selector) {
        if (row.kind === 'inventory') {
            return $.ajax({url: "{{ route('LpgInventoryInfo') }}", type: 'POST', headers, data: {cashiers_report_lpg_inventory_id: row.record_id}})
                .done(response => afterClose(() => openLpgInventoryModal(response.data.inventory_mode, response.data, null, 'REGULATOR')))
                .fail(xhr => error(selector, xhr, 'Unable to load inventory entry.'));
        }
        afterClose(() => {
            if (row.kind === 'swap') window.LpgSwappingEditor.edit(row.record_id);
            else if (row.kind === 'loan') window.LpgOnLoanEditor.edit(row.record_id);
            else if (row.kind === 'sales') editLpgArSoRecord(row.record_id);
            else if (row.kind === 'expense') editLpgExpenseRecord(row.record_id);
        });
    }
    function withRegulators(modalSelector, selectId, callback) {
        const select = document.getElementById(selectId);
        const options = Array.from(select.options);
        options.forEach(option => { if (option.value && !productIds.includes(Number(option.value))) option.remove(); });
        $(modalSelector).one('hidden.bs.modal', () => options.forEach(option => select.appendChild(option)));
        callback();
    }
    $(document).on('click', '.lpg-regulator-add', function () {
        const mode = this.dataset.sourceMode, productId = Number(this.dataset.productId || 0);
        if (!filters[mode]) return;
        if (mode === 'BEGINNING') {
            const button = $(this).prop('disabled', true);
            sources(mode, productId).done(response => {
                if (productId && response.data?.length) edit(response.data[0], '#lpg-regulator-error');
                else openLpgInventoryModal('BEGINNING', null, productId, 'REGULATOR');
            }).fail(xhr => error('#lpg-regulator-error', xhr, 'Unable to load beginning inventory.')).always(() => button.prop('disabled', false));
        } else if (mode === 'SWAP_IN' || mode === 'SWAP_OUT') {
            const prefix = mode === 'SWAP_IN' ? 'received' : 'replaced';
            withRegulators('#CashierReportLpgSwappingModal', prefix + '_product_idx', () => {
                window.LpgSwappingEditor.add(productId, mode === 'SWAP_IN' ? 'RECEIVED' : 'REPLACED');
                $('#' + prefix + '_product_idx').val(productId || '').trigger('change');
            });
        }
    });
    $(document).on('click', '.lpg-regulator-view', function () {
        const mode = this.dataset.sourceMode, productId = Number(this.dataset.productId || 0);
        if (!filters[mode]) return;
        const version = ++sourceVersion;
        if (sourceRequest) sourceRequest.abort();
        sourceRows = [];
        $('#LpgRegulatorSourcesTitle').text(filters[mode] + ' — Regulator Sources');
        $('#lpg-regulator-source-selection').text(productId ? 'Selected regulator product' : 'All regulator products in this report');
        $('#lpg-regulator-source-error').addClass('d-none');
        $('#lpg-regulator-source-total').text('—');
        $('#lpg-regulator-source-rows').html('<tr><td colspan="7" class="text-center py-3">Loading source records...</td></tr>');
        bootstrap.Modal.getOrCreateInstance(modal).show();
        sourceRequest = sources(mode, productId).done(response => {
            if (version !== sourceVersion) return;
            sourceRows = response.data || [];
            if (productId && sourceRows.length) $('#lpg-regulator-source-selection').text(label(sourceRows[0]));
            $('#lpg-regulator-source-rows').html(sourceRows.length ? sourceRows.map((row, index) => '<tr><td>' + escape(label(row)) + '</td><td>' + escape(movements[row.mode] || row.mode) + '</td><td>' + escape(row.account || '—') + '</td><td>' + escape(row.reference || '—') + '</td><td class="text-end">' + quantity(row.quantity) + '</td><td>' + escape(row.remarks || '—') + '</td><td><button type="button" class="btn btn-sm btn-primary lpg-regulator-source-edit" data-row-index="' + index + '"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit</button></td></tr>').join('') : '<tr><td colspan="7" class="text-center text-muted py-3">No source records for this selection.</td></tr>');
            $('#lpg-regulator-source-total').text(quantity(sourceRows.reduce((sum, row) => sum + Number(row.quantity || 0), 0)));
        }).fail((xhr, status) => { if (version === sourceVersion && status !== 'abort') { $('#lpg-regulator-source-rows').empty(); error('#lpg-regulator-source-error', xhr, 'Unable to load source records.'); } })
            .always(() => { if (version === sourceVersion) sourceRequest = null; });
    });
    $('#lpg-regulator-source-rows').on('click', '.lpg-regulator-source-edit', function () {
        const row = sourceRows[Number(this.dataset.rowIndex)];
        if (row) edit(row, '#lpg-regulator-source-error');
    });
    $(modal).on('hidden.bs.modal', () => { ++sourceVersion; if (sourceRequest) sourceRequest.abort(); sourceRequest = null; });
    $('#refresh-lpg-regulators').on('click', reload);
    const scheduleReload = () => { clearTimeout(reloadTimer); reloadTimer = setTimeout(reload, 150); };
    $(document).on('lpg:inventory-changed', scheduleReload);
    $('#LpgSwappingTable, #LpgOnLoanTable, #LpgBeginningInventoryTable, #LpgDeliveryTable, #LpgBoReplacementTable').on('xhr.dt', scheduleReload);
    reload();
});
</script>
