<script>
$(function () {
    const reportId = {{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }};
    const columnCount = 23;
    const modes = {
        BEGINNING: 'Starting Audit', DELIVERY: 'Suppliers Delivery', BO_REPLACEMENT: 'B.O. Replacement',
        BO_FROM_CUSTOMER: 'B.O. From Customer/s', BO_DURING_DELIVERY: 'B.O. During Delivery',
        BO_RETURNED_TO_SUPPLIER: 'B.O. Returned to Supplier', RETURN: 'Return', ON_LOAN: 'On-Loan',
        REFILL_SWAP: 'Refill / Swap', CYLINDER_WITH_CONTENT: 'Cylinder Sold with Content',
        UNCLASSIFIED: 'Unclassified Sales', EXPENSE: 'Product Expenses'
    };
    const inventoryModes = ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'BO_FROM_CUSTOMER', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'];
    const salesModes = ['RETURN', 'REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED'];
    const summaryBody = $('#LpgSellOutTable tbody');
    const modalElement = document.getElementById('LpgSellOutSourcesModal');
    let summaryRequest = null, sourceRequest = null;
    let summaryVersion = 0, sourceVersion = 0;
    let sourceState = null, reloadTimer = null;
    const escape = value => $('<div>').text(value == null ? '' : String(value)).html().replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    const quantity = value => Number(value || 0).toLocaleString('en-PH', { maximumFractionDigits: 2 });
    const money = value => Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const productCapacityLabel = row => {
        if (row.product_capacity !== null && row.product_capacity !== undefined && row.product_capacity !== '') {
            const value = Number(row.product_capacity);
            const capacity = Number.isFinite(value)
                ? value.toLocaleString('en-PH', { maximumFractionDigits: 2 })
                : String(row.product_capacity);
            return capacity + (row.product_capacity_unit || '');
        }
        return row.product_unit_measurement || '';
    };
    const productLabel = row => [row.product_name || 'Unavailable product', productCapacityLabel(row)].filter(Boolean).join(' ');

    function sourceCell(row, mode, value) {
        const id = Number(row.product_idx);
        const missing = 'Not set';
        const text = value == null ? missing : quantity(value);
        return '<td class="text-end text-nowrap"><button type="button" class="btn btn-sm btn-link lpg-sell-out-view" data-source-mode="' + mode + '" data-product-id="' + id + '" title="View source records">' + escape(text) + '</button>' +
            '<button type="button" class="btn btn-sm btn-light lpg-sell-out-add" data-source-mode="' + mode + '" data-product-id="' + id + '" aria-label="Add or edit ' + escape(modes[mode]) + ' for ' + escape(productLabel(row)) + '"><i class="bi bi-plus" aria-hidden="true"></i></button></td>';
    }
    function saleCells(row, mode) {
        const key = mode.toLowerCase();
        const price = row[key + '_mixed_prices'] ? 'Mixed' : (row[key + '_unit_price'] == null ? '—' : money(row[key + '_unit_price']));
        return sourceCell(row, mode, row[key + '_quantity']) + '<td class="text-end" title="Use View for individual prices">' + price + '</td><td class="text-end">' + money(row[key + '_amount']) + '</td>';
    }
    function reloadSummary() {
        clearTimeout(reloadTimer);
        const version = ++summaryVersion;
        if (summaryRequest) summaryRequest.abort();
        $('#refresh-lpg-sell-out').prop('disabled', true);
        $('#lpg-sell-out-error').addClass('d-none').text('');
        summaryRequest = $.post("{{ route('GetLpgSellOutSummary') }}", { cashiers_report_id: reportId })
            .done(function (response) {
                if (version !== summaryVersion) return;
                const rows = response.data || [];
                summaryBody.html(rows.length ? rows.map(function (row) {
                    return '<tr><td>' + escape(productLabel(row)) + '</td>' +
                        sourceCell(row, 'BEGINNING', row.beginning_count ? row.beginning_filled_quantity : null) +
                        sourceCell(row, 'BEGINNING', row.beginning_count ? row.beginning_bo_returned_customer_quantity : null) +
                        sourceCell(row, 'BEGINNING', row.beginning_count ? row.beginning_bo_during_delivery_quantity : null) +
                        sourceCell(row, 'DELIVERY', row.delivery_quantity) + sourceCell(row, 'BO_REPLACEMENT', row.bo_replacement_quantity) +
                        sourceCell(row, 'BO_FROM_CUSTOMER', row.bo_from_customer_quantity) + sourceCell(row, 'BO_DURING_DELIVERY', row.bo_during_delivery_quantity) +
                        sourceCell(row, 'BO_RETURNED_TO_SUPPLIER', row.bo_returned_to_supplier_quantity) + sourceCell(row, 'ON_LOAN', row.on_loan_quantity) +
                        saleCells(row, 'REFILL_SWAP') + saleCells(row, 'CYLINDER_WITH_CONTENT') +
                        '<td class="text-end fw-semibold">' + (row.theoretical_filled_quantity == null ? '—' : quantity(row.theoretical_filled_quantity)) + '</td>' +
                        '<td class="text-end fw-semibold">' + quantity(row.theoretical_bo_returned_customer_quantity) + '</td>' +
                        '<td class="text-end fw-semibold">' + quantity(row.theoretical_bo_during_delivery_quantity) + '</td>' +
                        window.LpgActualInventoryUI.cells(row) +
                        '<td class="text-end" title="Net after discounts: ' + money(row.net_sales) + '">' + money(row.total_sales) + '</td></tr>';
            }).join('') : '<tr><td colspan="' + columnCount + '" class="text-center text-muted py-4">No records yet. Use Starting Audit or Add in a source column.</td></tr>');
            }).fail(function (xhr, status) {
                if (status === 'abort' || version !== summaryVersion) return;
                summaryBody.html('<tr><td colspan="' + columnCount + '" class="text-center py-4">Summary could not be loaded.</td></tr>');
                showError('#lpg-sell-out-error', xhr, 'Unable to load sell-out quantities. Please refresh.');
            }).always(function () {
                if (version === summaryVersion) { summaryRequest = null; $('#refresh-lpg-sell-out').prop('disabled', false); }
            });
    }
    function showError(selector, xhr, fallback) {
        const errors = xhr.responseJSON?.errors || {};
        $(selector).removeClass('d-none').text(Object.values(errors).flat().join(' ') || xhr.responseJSON?.message || fallback);
    }
    function fetchSource(mode, productId) {
        return $.post("{{ route('GetLpgSellOutSources') }}", { cashiers_report_id: reportId, mode: mode, product_idx: productId || '' });
    }
    function afterSourcesClose(callback) {
        if (modalElement.classList.contains('show')) {
            $(modalElement).one('hidden.bs.modal', callback);
            bootstrap.Modal.getOrCreateInstance(modalElement).hide();
        } else callback();
    }
    function fillProduct(inputSelector, listSelector, productId) {
        const option = $(listSelector + ' option').filter(function () { return Number($(this).attr('data-id')) === productId; }).first();
        $(inputSelector).val(option.length ? option.val() : '').trigger('change');
    }
    function editSource(row) {
        if (row.kind === 'inventory') {
            $.post("{{ route('LpgInventoryInfo') }}", { cashiers_report_lpg_inventory_id: row.record_id })
                .done(r => afterSourcesClose(() => openLpgInventoryModal(r.data.inventory_mode, r.data)))
                .fail(xhr => showError('#lpg-sell-out-source-error', xhr, 'Unable to load inventory record.'));
        } else afterSourcesClose(function () {
            if (row.kind === 'sales') {
                $('#ar_so_product').attr('data-product-category-filter', 'lpg');
                editLpgArSoRecord(row.record_id);
            }
            else if (row.kind === 'expense') editLpgExpenseRecord(row.record_id);
            else if (row.kind === 'loan') window.LpgOnLoanEditor.edit(row.record_id);
        });
    }
    function addEntry(mode, productId, button) {
        if (!modes[mode]) return;
        if (inventoryModes.includes(mode)) {
            if (mode !== 'BEGINNING' || !productId) {
                afterSourcesClose(() => openLpgInventoryModal(mode, null, productId));
                return;
            }
            button.prop('disabled', true);
            fetchSource(mode, productId).then(function (response) {
                const row = response.data[0];
                if (row && row.kind === 'inventory') {
                    return $.post("{{ route('LpgInventoryInfo') }}", { cashiers_report_lpg_inventory_id: row.record_id })
                        .done(r => afterSourcesClose(() => openLpgInventoryModal(mode, r.data)));
                } else afterSourcesClose(() => openLpgInventoryModal(mode, null, productId));
            }).fail(xhr => showError(modalElement.classList.contains('show') ? '#lpg-sell-out-source-error' : '#lpg-sell-out-error', xhr, 'Unable to load beginning inventory.'))
                .always(() => button.prop('disabled', false));
            return;
        }
        afterSourcesClose(function () {
            if (salesModes.includes(mode)) {
                resetLpgArSoForm();
                $('#ar_so_product').attr('data-product-category-filter', 'lpg');
                $('#ar_so_type').val(mode === 'RETURN' ? 'Return' : 'Sales Order').trigger('change');
                $('#ar_so_stock_type').val(['REFILL_SWAP', 'CYLINDER_WITH_CONTENT'].includes(mode) ? mode : '').trigger('change');
                fillProduct('#ar_so_product', '#lpgArSoProductList', productId);
                lpgArSoShowModal('#CashierReportLpgArSoModal');
            } else if (mode === 'ON_LOAN') window.LpgOnLoanEditor.add(productId);
            else if (mode === 'EXPENSE') {
                resetLpgExpenseForm();
                showLpgExpenseModal('#CashierReportLpgExpensesModal');
                fillProduct('#lpg_expense_item', '#lpgExpenseProductList', productId);
            }
        });
    }
    $(document).on('click', '.lpg-sell-out-add', function () {
        addEntry($(this).attr('data-source-mode'), Number($(this).attr('data-product-id') || 0), $(this));
    });
    $(document).on('click', '.lpg-sell-out-view', function () {
        const mode = $(this).attr('data-source-mode');
        if (!modes[mode]) return;
        const productId = Number($(this).attr('data-product-id') || 0);
        const version = ++sourceVersion;
        if (sourceRequest) sourceRequest.abort();
        sourceState = { mode: mode, productId: productId, rows: [] };
        $('#LpgSellOutSourcesTitle').text(modes[mode] + ' — Source Records');
        $('#lpg-sell-out-source-item').text(productId ? 'Selected product in this report' : 'All items in this report');
        $('#lpg-sell-out-source-error').addClass('d-none').text('');
        $('#lpg-sell-out-source-rows').html('<tr><td colspan="8" class="text-center py-3">Loading records...</td></tr>');
        $('#lpg-sell-out-source-total').text('—');
        $('#lpg-sell-out-source-add').prop('disabled', true).text(mode === 'BEGINNING' ? 'Add / Edit Entry' : 'Add Entry');
        bootstrap.Modal.getOrCreateInstance(modalElement).show();
        sourceRequest = fetchSource(mode, productId).done(function (response) {
            if (version !== sourceVersion) return;
            const rows = response.data || [];
            sourceState.rows = rows;
            if (productId && rows.length) $('#lpg-sell-out-source-item').text(productLabel(rows[0]));
            $('#lpg-sell-out-source-rows').html(rows.length ? rows.map(function (row, index) {
                const action = row.kind === 'audit_carry_forward' ? '—' : '<button type="button" class="btn btn-sm btn-primary lpg-sell-out-source-edit" data-row-index="' + index + '"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit</button>';
                const auditDetails = row.mode === 'BEGINNING' ? 'B.O. returned from customers: ' + quantity(row.bo_returned_customer_quantity || 0) + '; B.O. during delivery: ' + quantity(row.bo_during_delivery_quantity || 0) : '';
                const remarks = [auditDetails, row.remarks].filter(Boolean).join(' · ') || '—';
                return '<tr><td>' + escape(productLabel(row)) + '</td><td>' + escape(row.account || '—') + '</td><td>' + escape(row.reference || '—') + '</td><td class="text-end">' + quantity(row.quantity) + '</td><td class="text-end">' + (row.unit_price == null ? '—' : money(row.unit_price)) + '</td><td class="text-end">' + (row.amount == null ? '—' : money(row.amount)) + '</td><td>' + escape(remarks) + '</td><td>' + action + '</td></tr>';
            }).join('') : '<tr><td colspan="8" class="text-center text-muted py-3">No source records for this selection.</td></tr>');
            const sourceTotal = rows.reduce((total, row) => total + Number(row.quantity || 0) + (mode === 'BEGINNING' ? Number(row.bo_returned_customer_quantity || 0) + Number(row.bo_during_delivery_quantity || 0) : 0), 0);
            $('#lpg-sell-out-source-total').text(quantity(sourceTotal));
            $('#lpg-sell-out-source-add').prop('disabled', false);
        }).fail(function (xhr, status) {
            if (status === 'abort' || version !== sourceVersion) return;
            $('#lpg-sell-out-source-rows').empty();
            showError('#lpg-sell-out-source-error', xhr, 'Unable to load source records. Close and try View again.');
        }).always(function () { if (version === sourceVersion) sourceRequest = null; });
    });
    $('#lpg-sell-out-source-add').on('click', function () {
        if (sourceState) addEntry(sourceState.mode, sourceState.productId, $(this));
    });
    $('#lpg-sell-out-source-rows').on('click', '.lpg-sell-out-source-edit', function () {
        const row = sourceState?.rows[Number($(this).attr('data-row-index'))];
        if (row) editSource(row);
    });
    $(modalElement).on('hidden.bs.modal', function () { ++sourceVersion; if (sourceRequest) sourceRequest.abort(); sourceRequest = null; });

    $('#refresh-lpg-sell-out').on('click', reloadSummary);
    $(document).on('lpg:inventory-changed', function () { clearTimeout(reloadTimer); reloadTimer = setTimeout(reloadSummary, 150); });
    $('#LpgOnLoanTable').on('xhr.dt', function () { clearTimeout(reloadTimer); reloadTimer = setTimeout(reloadSummary, 150); });
    reloadSummary();
});
</script>
