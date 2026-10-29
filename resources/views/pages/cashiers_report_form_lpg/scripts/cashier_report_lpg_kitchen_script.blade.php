<script>
$(function () {
    const reportId = {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }};
    const kitchenProductIds = @json(\App\Services\LpgKitchenProducts::ids());
    const modes = {BEGINNING:'Beginning Inventory', DELIVERY:'Delivery', BO_REPLACEMENT:'B.O. Replacement', RETURN:'Return', DAMAGED:'Damaged', SWAP:'Swap', ON_LOAN:'On-loan', CONSIGNMENT:'Consignment', RAFFLE:'Raffle / Product Expenses', WALK_IN:'Walk-in', TOTAL_SOLD:'Total Sold'};
    const inventoryModes = ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT'];
    const sourceModal = document.getElementById('LpgKitchenSourcesModal');
    const movementModal = document.getElementById('LpgKitchenMovementModal');
    const deleteModal = document.getElementById('LpgKitchenDeleteModal');
    const headers = {'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json'};
    let summaryRequest = null, sourceRequest = null, summaryVersion = 0, sourceVersion = 0;
    let sourceState = null, deleteId = 0, movementSaving = false, deleteSaving = false, reloadTimer = null;
    const escape = value => $('<div>').text(value == null ? '' : String(value)).html().replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    const quantity = value => Number(value || 0).toLocaleString('en-PH', {maximumFractionDigits: 2});
    const money = value => Number(value || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const label = row => [row.product_name || 'Unavailable product', row.product_unit_measurement].filter(Boolean).join(' ');
    const show = element => bootstrap.Modal.getOrCreateInstance(element).show();
    const hide = element => bootstrap.Modal.getOrCreateInstance(element).hide();

    function error(selector, xhr, fallback) {
        $(selector).text(Object.values(xhr.responseJSON?.errors || {}).flat().join(' ') || xhr.responseJSON?.message || fallback).removeClass('d-none');
    }
    function sourceCell(row, mode, value) {
        const productId = Number(row.product_idx);
        return '<td class="text-end text-nowrap"><button type="button" class="btn btn-sm btn-link lpg-kitchen-view" data-source-mode="' + mode + '" data-product-id="' + productId + '" title="View source records">' + (value == null ? 'Not set' : quantity(value)) + '</button>' +
            '<button type="button" class="btn btn-sm btn-light lpg-kitchen-add" data-source-mode="' + mode + '" data-product-id="' + productId + '" aria-label="Add ' + escape(modes[mode]) + ' for ' + escape(label(row)) + '"><i class="bi bi-plus" aria-hidden="true"></i></button></td>';
    }
    function renderTemplateRows(selector, rows, emptyMessage) {
        let group = null;
        const individual = selector === '#LpgKitchenIndividualTable';
        const columnCount = individual ? 9 : 8;
        $(selector + ' tbody').html(rows.length ? rows.map(row => {
            let html = '';
            if (row.main_product_name !== group) {
                group = row.main_product_name;
                html += '<tr><td colspan="' + columnCount + '" class="fw-bold">' + escape(group || 'Kitchen Products & Accessories') + '</td></tr>';
            }
            const capacity = row.product_capacity ? ' ' + Number(row.product_capacity) + ' ' + (row.product_capacity_unit || '') : '';
            const components = (row.components || []).map(component => {
                const componentCapacity = component.product_capacity ? ' ' + Number(component.product_capacity) + ' ' + (component.product_capacity_unit || '') : '';
                return Number(component.quantity || 0).toLocaleString('en-PH', {maximumFractionDigits: 3}) + ' × ' + escape((component.product_name || 'Unavailable product') + componentCapacity) + (component.is_active ? '' : ' (inactive)');
            }).join(', ');
            const item = escape((row.product_name || 'Unavailable product') + capacity) + (components ? '<div class="small text-muted">' + components + '</div>' : '');
            const ending = row.template_ending_quantity == null ? '—' : quantity(row.template_ending_quantity);
            const actual = row.template_actual_quantity == null ? '—' : quantity(row.template_actual_quantity);
            const beginningCell = row.report_only ? '<td class="text-center">—</td>' : sourceCell(row, 'BEGINNING', row.beginning_count ? row.beginning_quantity : null);
            const deliveryCell = row.report_only ? '<td class="text-center">—</td>' : sourceCell(row, 'DELIVERY', row.delivery_quantity);
            const soldFromSet = Number(row.sold_per_set_quantity || 0);
            const soldFromSetCell = individual ? '<td class="text-end">' + soldFromSet.toLocaleString('en-PH', {maximumFractionDigits: 5}) + '</td>' : '';
            const mainRow = '<tr><td>' + item + '</td>' + beginningCell
                + deliveryCell + soldFromSetCell + '<td class="text-end fw-semibold">' + (row.report_only ? '' : quantity(row.total_sold_quantity)) + '</td>'
                + '<td class="text-end">' + (row.report_only ? '' : (row.mixed_prices ? 'Mixed' : (row.unit_price == null ? '—' : money(row.unit_price)))) + '</td>'
                + '<td class="text-end">' + (row.report_only ? '' : money(row.total_sales)) + '</td><td class="text-end fw-bold">' + ending + '</td><td class="text-end fw-bold">' + actual + '</td></tr>';
            return html + mainRow;
        }).join('') : '<tr><td colspan="' + columnCount + '" class="text-center text-muted py-3">' + escape(emptyMessage) + '</td></tr>');
    }
    function reload() {
        clearTimeout(reloadTimer);
        const version = ++summaryVersion;
        if (summaryRequest) summaryRequest.abort();
        $('#refresh-lpg-kitchen').prop('disabled', true);
        $('#lpg-kitchen-error').addClass('d-none');
        summaryRequest = $.ajax({url:"{{ route('GetLpgKitchenSummary') }}", type:'POST', headers, data:{cashiers_report_id:reportId}})
            .done(response => {
                if (version !== summaryVersion) return;
                let group = null;
                const rows = response.data || [];
                $('#lpg-kitchen-set-snapshot-warning').toggleClass('d-none', !response.unknown_set_sale_snapshots);
                renderTemplateRows('#LpgKitchenSetsTable', rows.filter(row => row.is_set), 'No sets configured. Add components to a kitchen product in Product Details.');
                renderTemplateRows('#LpgKitchenIndividualTable', rows.filter(row => !row.is_set), 'No individual kitchen products configured.');
                const legacyRows = rows.filter(row => !row.report_only);
                $('#LpgKitchenTable tbody').html(legacyRows.length ? legacyRows.map(row => {
                    let html = '';
                    if (row.main_product_name !== group) {
                        group = row.main_product_name;
                        html += '<tr><td colspan="15" class="fw-bold">' + escape(group) + '</td></tr>';
                    }
                    html += '<tr><td>' + escape(label(row)) + '</td>' + sourceCell(row, 'BEGINNING', row.beginning_count ? row.beginning_quantity : null);
                    ['DELIVERY','DAMAGED','BO_REPLACEMENT','SWAP','ON_LOAN','RAFFLE','WALK_IN'].forEach(mode => html += sourceCell(row, mode, row[mode.toLowerCase() + '_quantity']));
                    html += '<td class="text-end fw-semibold" title="Sales Order quantity plus Cash Sales quantity">' + quantity(row.total_sold_quantity) + '</td>';
                    return html + '<td class="text-end">' + (row.mixed_prices ? 'Mixed' : (row.unit_price == null ? '—' : money(row.unit_price))) + '</td><td class="text-end" title="Net after discounts: ' + money(row.net_sales) + '">' + money(row.total_sales) + '</td><td class="text-end fw-bold ' + (row.ending_quantity < 0 ? 'text-danger' : '') + '">' + (row.ending_quantity == null ? 'Set beginning stock' : quantity(row.ending_quantity)) + '</td><td class="text-end">' + (row.actual_quantity == null ? '—' : quantity(row.actual_quantity)) + '</td><td class="text-end">' + (row.variance_quantity == null ? '—' : quantity(row.variance_quantity)) + '</td></tr>';
                }).join('') : '<tr><td colspan="15" class="text-center text-muted py-4">No kitchen stock records yet. Use Add to select a kitchen product.</td></tr>');
            }).fail((xhr, status) => {
                if (version !== summaryVersion || status === 'abort') return;
                $('#LpgKitchenTable tbody').html('<tr><td colspan="15" class="text-center py-4">Kitchen stock could not be loaded.</td></tr>');
                $('#LpgKitchenSetsTable tbody').html('<tr><td colspan="8" class="text-center py-3">Kitchen products could not be loaded.</td></tr>');
                $('#LpgKitchenIndividualTable tbody').html('<tr><td colspan="9" class="text-center py-3">Kitchen products could not be loaded.</td></tr>');
                error('#lpg-kitchen-error', xhr, 'Unable to load kitchen stock.');
            }).always(() => { if (version === summaryVersion) { summaryRequest = null; $('#refresh-lpg-kitchen').prop('disabled', false); } });
    }
    function fetchSources(mode, productId) {
        return $.ajax({url:"{{ route('GetLpgKitchenSources') }}", type:'POST', headers, data:{cashiers_report_id:reportId, mode, product_idx:productId || ''}});
    }
    function afterSourceClose(callback) {
        if (sourceModal.classList.contains('show')) { $(sourceModal).one('hidden.bs.modal', callback); hide(sourceModal); }
        else callback();
    }
    function kitchenOptions(modalSelector, optionSelector, callback) {
        const options = $(optionSelector + ' option').toArray();
        options.forEach(option => {
            const id = Number(option.getAttribute('data-id') || option.value || 0);
            if (id && !kitchenProductIds.map(Number).includes(id)) $(option).detach();
        });
        $(modalSelector).one('hidden.bs.modal', function () {
            const container = document.querySelector(optionSelector);
            options.forEach(option => container.appendChild(option));
        });
        callback();
    }
    function fillProduct(input, datalist, productId) {
        const option = $(datalist + ' option').filter(function () { return Number($(this).attr('data-id')) === Number(productId); }).first();
        $(input).val(option.length ? option.val() : '').trigger('change');
    }
    function edit(row) {
        if (row.kind === 'inventory') {
            return $.ajax({url:"{{ route('LpgInventoryInfo') }}", type:'POST', headers, data:{cashiers_report_lpg_inventory_id:row.record_id}})
                .done(response => afterSourceClose(() => openLpgInventoryModal(response.data.inventory_mode, response.data, null, 'KITCHEN')))
                .fail(xhr => error(sourceModal.classList.contains('show') ? '#lpg-kitchen-source-error' : '#lpg-kitchen-error', xhr, 'Unable to load inventory record.'));
        } else if (row.kind === 'kitchen') {
            return $.ajax({url:"{{ route('LpgKitchenMovementInfo') }}", type:'POST', headers, data:{cashiers_report_id:reportId, cashiers_report_lpg_kitchen_movement_id:row.record_id}})
                .done(response => afterSourceClose(() => openMovement(response.data.movement_type, row.product_idx, response.data)))
                .fail(xhr => error('#lpg-kitchen-source-error', xhr, 'Unable to load stock movement.'));
        } else afterSourceClose(() => {
            if (row.kind === 'sales') editLpgArSoRecord(row.record_id);
            else if (row.kind === 'expense') editLpgExpenseRecord(row.record_id);
            else if (row.kind === 'loan') window.LpgOnLoanEditor.edit(row.record_id);
            else if (row.kind === 'swap') window.LpgSwappingEditor.edit(row.record_id);
        });
    }
    function add(mode, productId, button) {
        if (mode === 'BEGINNING' && productId) {
            button.prop('disabled', true);
            fetchSources(mode, productId).then(response => {
                if (response.data[0]) return edit(response.data[0]);
                afterSourceClose(() => openLpgInventoryModal(mode, null, productId, 'KITCHEN'));
            }).fail(xhr => error('#lpg-kitchen-error', xhr, 'Unable to load beginning stock.')).always(() => button.prop('disabled', false));
            return;
        }
        afterSourceClose(() => {
            if (inventoryModes.includes(mode)) openLpgInventoryModal(mode, null, productId, 'KITCHEN');
            else if (['DAMAGED','CONSIGNMENT'].includes(mode)) openMovement(mode, productId);
            else if (['WALK_IN','TOTAL_SOLD','RETURN'].includes(mode)) kitchenOptions('#CashierReportLpgArSoModal', '#lpgArSoProductList', () => {
                resetLpgArSoForm();
                $('#ar_so_type').val(mode === 'WALK_IN' ? 'Cash Sales' : (mode === 'RETURN' ? 'Return' : 'Sales Order')).trigger('change');
                fillProduct('#ar_so_product', '#lpgArSoProductList', productId);
                lpgArSoShowModal('#CashierReportLpgArSoModal');
            });
            else if (mode === 'RAFFLE') kitchenOptions('#CashierReportLpgExpensesModal', '#lpgExpenseProductList', () => {
                resetLpgExpenseForm(); showLpgExpenseModal('#CashierReportLpgExpensesModal'); fillProduct('#lpg_expense_item', '#lpgExpenseProductList', productId);
            });
            else if (mode === 'ON_LOAN') kitchenOptions('#CashierReportLpgOnLoanModal', '#borrow_product_idx', () => window.LpgOnLoanEditor.add(productId));
            else if (mode === 'SWAP') kitchenOptions('#CashierReportLpgSwappingModal', '#replaced_product_idx', () => window.LpgSwappingEditor.add(productId, 'REPLACED'));
        });
    }
    $(document).on('click', '.lpg-kitchen-add', function () {
        if (modes[this.dataset.sourceMode]) add(this.dataset.sourceMode, Number(this.dataset.productId || 0), $(this));
    });
    $(document).on('click', '.lpg-kitchen-view', function () {
        const mode = this.dataset.sourceMode, productId = Number(this.dataset.productId || 0);
        if (!modes[mode]) return;
        const version = ++sourceVersion;
        if (sourceRequest) sourceRequest.abort();
        sourceState = {mode, productId, rows:[]};
        $('#LpgKitchenSourcesTitle').text(modes[mode] + ' — Source Records');
        $('#lpg-kitchen-source-selection').text(productId ? 'Selected kitchen product' : 'All kitchen products in this report');
        $('#lpg-kitchen-source-error').addClass('d-none');
        $('#lpg-kitchen-source-add').prop('disabled', true);
        $('#lpg-kitchen-source-total').text('—');
        $('#lpg-kitchen-source-rows').html('<tr><td colspan="8" class="text-center py-3">Loading records...</td></tr>');
        show(sourceModal);
        sourceRequest = fetchSources(mode, productId).done(response => {
            if (version !== sourceVersion) return;
            const rows = response.data || [];
            sourceState.rows = rows;
            if (productId && rows.length) $('#lpg-kitchen-source-selection').text(label(rows[0]));
            $('#lpg-kitchen-source-rows').html(rows.length ? rows.map((row, index) => '<tr><td>' + escape(label(row)) + '</td><td>' + escape(row.account || '—') + '</td><td>' + escape(row.reference || row.transaction_type || '—') + '</td><td class="text-end">' + quantity(row.quantity) + '</td><td class="text-end">' + (row.unit_price == null ? '—' : money(row.unit_price)) + '</td><td class="text-end">' + (row.amount == null ? '—' : money(row.amount)) + '</td><td>' + escape(row.remarks || '—') + '</td><td class="text-nowrap"><button type="button" class="btn btn-sm btn-primary lpg-kitchen-source-edit" data-row-index="' + index + '" title="Edit"><i class="bi bi-pencil-square" aria-hidden="true"></i></button> ' + (row.kind === 'kitchen' ? '<button type="button" class="btn btn-sm btn-danger lpg-kitchen-source-delete" data-row-index="' + index + '" title="Delete"><i class="bi bi-trash3" aria-hidden="true"></i></button>' : '') + '</td></tr>').join('') : '<tr><td colspan="8" class="text-center text-muted py-3">No source records for this selection.</td></tr>');
            $('#lpg-kitchen-source-total').text(quantity(rows.reduce((sum, row) => sum + Number(row.quantity || 0), 0)));
            $('#lpg-kitchen-source-add').prop('disabled', false);
        }).fail((xhr, status) => {
            if (version !== sourceVersion || status === 'abort') return;
            $('#lpg-kitchen-source-rows').empty(); error('#lpg-kitchen-source-error', xhr, 'Unable to load source records.');
        }).always(() => { if (version === sourceVersion) sourceRequest = null; });
    });
    $('#lpg-kitchen-source-add').on('click', function () { if (sourceState) add(sourceState.mode, sourceState.productId, $(this)); });
    $('#lpg-kitchen-source-rows').on('click', '.lpg-kitchen-source-edit', function () {
        const row = sourceState?.rows[Number(this.dataset.rowIndex)]; if (row) edit(row);
    }).on('click', '.lpg-kitchen-source-delete', function () {
        const row = sourceState?.rows[Number(this.dataset.rowIndex)];
        if (!row || row.kind !== 'kitchen') return;
        deleteId = row.record_id;
        $('#lpg-kitchen-delete-item').text(modes[row.mode] + ': ' + label(row) + ' (' + quantity(row.quantity) + ')');
        $('#lpg-kitchen-delete-error').addClass('d-none');
        afterSourceClose(() => show(deleteModal));
    });
    $(sourceModal).on('hidden.bs.modal', () => { ++sourceVersion; if (sourceRequest) sourceRequest.abort(); sourceRequest = null; });

    function openMovement(mode, productId, row) {
        const form = document.getElementById('LpgKitchenMovementForm');
        form.reset(); form.classList.remove('was-validated');
        $('#lpg-kitchen-movement-error').addClass('d-none');
        $('#lpg_kitchen_movement_id').val(row?.cashiers_report_lpg_kitchen_movement_id || 0);
        $('#lpg_kitchen_movement_type').val(mode);
        $('#lpg_kitchen_product_idx').val(row?.product_idx || productId || '').trigger('change');
        $('#lpg_kitchen_client_idx').val(row?.client_idx || '').prop('required', true).prop('disabled', false);
        $('#lpg-kitchen-client-field').removeClass('d-none');
        $('#lpg-kitchen-client-label').text(mode === 'DAMAGED' ? 'Received From / Client or Account' : 'Consignee / Client');
        $('#lpg-kitchen-client-error').text(mode === 'DAMAGED' ? 'Select the client / account the damaged item came from.' : 'Select the client receiving the consignment.');
        $('#lpg_kitchen_reference_no').val(row?.reference_no || '');
        $('#lpg_kitchen_quantity').val(row?.quantity ?? '');
        $('#lpg_kitchen_remarks').val(row?.remarks || '');
        $('#LpgKitchenMovementTitle').text((row ? 'Edit ' : 'Add ') + modes[mode]);
        $('#lpg-kitchen-movement-effect').text(mode === 'DAMAGED'
            ? 'This quantity is added to kitchen stock and does not count as a sale.'
            : 'This quantity is deducted from kitchen stock and does not count as a sale.');
        show(movementModal);
    }
    function loadMovementForAction(id, callback) {
        $.ajax({url:"{{ route('LpgKitchenMovementInfo') }}", type:'POST', headers, data:{cashiers_report_id:reportId, cashiers_report_lpg_kitchen_movement_id:id}})
            .done(response => callback(response.data))
            .fail(xhr => {
                const message = xhr.responseJSON?.message || 'Unable to load the damaged receipt.';
                if (typeof showValidationErrorModal === 'function') showValidationErrorModal(message);
                else error('#lpg-kitchen-error', xhr, message);
            });
    }
    window.LpgKitchenMovementEditor = {
        edit: id => loadMovementForAction(id, row => openMovement(row.movement_type, row.product_idx, row)),
        remove: id => loadMovementForAction(id, row => {
            deleteId = row.cashiers_report_lpg_kitchen_movement_id;
            const productName = $('#lpg_kitchen_product_idx option').filter(function () { return Number(this.value) === Number(row.product_idx); }).text() || 'Item #' + row.product_idx;
            $('#lpg-kitchen-delete-item').text(modes[row.movement_type] + ': ' + productName + ' (' + quantity(row.quantity) + ')');
            $('#lpg-kitchen-delete-error').addClass('d-none');
            show(deleteModal);
        })
    };
    $('#LpgKitchenMovementForm').on('submit', function (event) {
        event.preventDefault(); if (movementSaving) return;
        this.classList.add('was-validated'); if (!this.checkValidity()) { this.reportValidity(); return; }
        const payload = $(this).serialize();
        movementSaving = true; $(this).find(':input').prop('disabled', true);
        $('#lpg-kitchen-movement-error').addClass('d-none');
        $.ajax({url:"{{ route('SaveLpgKitchenMovement') }}", type:'POST', headers, data:payload})
            .done(() => { movementSaving = false; hide(movementModal); $(document).trigger('lpg:inventory-changed').trigger('lpg:kitchen-movement-changed'); })
            .fail(xhr => error('#lpg-kitchen-movement-error', xhr, 'Unable to save stock movement.'))
            .always(() => { movementSaving = false; $(this).find(':input').prop('disabled', false); });
    });
    $(movementModal).on('hide.bs.modal', event => { if (movementSaving) event.preventDefault(); });
    $('#delete-lpg-kitchen-movement').on('click', function () {
        if (deleteSaving || !deleteId) return;
        deleteSaving = true; $(this).prop('disabled', true);
        $.ajax({url:"{{ route('DeleteLpgKitchenMovement') }}", type:'POST', headers, data:{cashiers_report_id:reportId, cashiers_report_lpg_kitchen_movement_id:deleteId}})
            .done(() => { deleteSaving = false; hide(deleteModal); $(document).trigger('lpg:inventory-changed').trigger('lpg:kitchen-movement-changed'); })
            .fail(xhr => error('#lpg-kitchen-delete-error', xhr, 'Unable to delete stock movement.'))
            .always(() => { deleteSaving = false; $(this).prop('disabled', false); });
    });
    $(deleteModal).on('hide.bs.modal', event => { if (deleteSaving) event.preventDefault(); });
    $('#refresh-lpg-kitchen').on('click', reload);
    $(document).on('lpg:inventory-changed', () => { clearTimeout(reloadTimer); reloadTimer = setTimeout(reload, 150); });
    $('#LpgSwappingTable, #LpgOnLoanTable').on('xhr.dt', () => { clearTimeout(reloadTimer); reloadTimer = setTimeout(reload, 150); });
    reload();
});
</script>
