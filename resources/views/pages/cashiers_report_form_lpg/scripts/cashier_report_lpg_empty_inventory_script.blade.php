<script>
$(function () {
    const reportId = {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }};
    const quantityFields = @json(array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS));
    const quantityLabels = @json(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS);
    const automaticFields = @json(\App\Services\LpgEmptyInventorySummary::AUTO_FIELDS);
    const additionFields = @json(array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::ADDITIONS));
    const deductionFields = @json(array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::DEDUCTIONS));
    const modeFields = {BEGINNING: ['beginning_quantity'], ADDITIONS: additionFields, DEDUCTIONS: deductionFields};
    const modeNames = {BEGINNING: 'Beginning Inventory', ADDITIONS: 'Additions (+)', DEDUCTIONS: 'Deductions (-)'};
    const states = {};
    let deleteId = 0;
    const number = value => Number(value || 0).toLocaleString('en-PH');
    const headers = {'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json'};
    const escape = value => $('<div>').text(value == null ? '' : String(value)).html().replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    const sourceModal = document.getElementById('LpgEmptyInventorySourcesModal');
    let sourceRequest = null, sourceVersion = 0, sourceState = null;

    function fieldMode(field) {
        return field === 'beginning_quantity' ? 'BEGINNING' : (additionFields.includes(field) ? 'ADDITIONS' : 'DEDUCTIONS');
    }
    function quantityCell(field, value, type, row) {
        if (type !== 'display') return Number(value || 0);
        const productId = Number(row.product_idx);
        const automatic = (row.automatic_fields || []).includes(field);
        return '<div class="text-nowrap"><button type="button" class="btn btn-sm btn-link lpg-empty-column-view" data-quantity-field="' + field + '" data-product-id="' + productId + '" title="View ' + escape(quantityLabels[field]) + ' sources">' + number(value) + '</button>' +
            '<button type="button" class="btn btn-sm btn-light lpg-empty-column-add" data-quantity-field="' + field + '" data-product-id="' + productId + '" aria-label="Add ' + escape(quantityLabels[field]) + ' source for ' + escape(row.product_name) + '"><i class="bi bi-plus" aria-hidden="true"></i></button><small class="d-block text-muted">' + (automatic ? 'Auto' : 'Manual') + '</small></div>';
    }

    const table = $('#LpgEmptyInventoryTable').DataTable({
        processing: true,
        paging: false,
        searching: true,
        ordering: false,
        info: false,
        autoWidth: false,
        ajax: function (data, callback) {
            $('#lpg_empty_table_error').addClass('d-none');
            $.ajax({url: "{{ route('GetLpgEmptyInventoryList') }}", type: 'POST', headers: headers, data: {cashiers_report_id: reportId}})
                .done(response => {
                    const rows = Array.isArray(response) ? response : (response.data || []);
                    const pending = Number(response.pending_sources || 0);
                    $('#lpg-empty-pending-notice').toggleClass('d-none', pending === 0);
                    $('#lpg-empty-pending-message').text(pending + ' source item(s) need classification or empty-receipt details. Auto totals exclude these items until reviewed.');
                    const overlap = rows.reduce((sum, row) => sum + Number(row.duplicate_received_quantity || 0), 0);
                    $('#lpg-empty-overlap-note').text(overlap ? 'Linked receipt overlap: ' + number(overlap) + ' pcs already excluded once from ending stock.' : '');
                    callback({data: rows});
                })
                .fail(xhr => {
                    $('#lpg_empty_table_error').text(xhr.responseJSON?.message || 'Unable to load empty-stock inventory.').removeClass('d-none');
                    callback({data: []});
                });
        },
        columns: [
            {data: 'product_name', defaultContent: '-', render: $.fn.dataTable.render.text()},
            ...quantityFields.map(field => ({data: field, className: 'text-end', render: (value, type, row) => quantityCell(field, value, type, row)})),
            {data: 'ending_quantity', className: 'text-end fw-bold', render: (value, type, row) => type === 'display' ? '<span class="' + (Number(value) < 0 ? 'text-danger' : '') + '" title="Linked receipt overlap: ' + number(row.duplicate_received_quantity) + ' pcs">' + number(value) + '</span>' : Number(value)},
            {data: 'remarks', defaultContent: '-', render: $.fn.dataTable.render.text()},
            {data: null, className: 'text-nowrap text-center', render: function (data, type, row) {
                const id = Number(row.cashiers_report_lpg_empty_inventory_id);
                if (!id) return '';
                return `<button type="button" class="btn btn-sm btn-danger delete-lpg-empty-inventory" data-id="${id}" title="Delete row" aria-label="Delete empty-stock row"><i class="bi bi-trash3" aria-hidden="true"></i></button>`;
            }}
        ],
        language: {emptyTable: 'No empty-stock rows recorded. Add a product to begin.'},
        drawCallback: function () {
            const api = this.api();
            $(api.table().body()).find('.lpg-empty-group-row, .lpg-empty-total-row').remove();
            let group = null, totals = {}, lastNode = null;
            function subtotal() {
                const tr = $('<tr class="lpg-empty-total-row">').append($('<td>').text('Total'));
                quantityFields.concat(['ending_quantity']).forEach(field => tr.append($('<td class="text-end">').text(number(totals[field]))));
                return tr.append($('<td colspan="2">'));
            }
            api.rows({search: 'applied', order: 'applied'}).every(function () {
                const row = this.data();
                const node = this.node();
                if (row.capacity_group !== group) {
                    if (lastNode) $(lastNode).after(subtotal());
                    group = row.capacity_group;
                    totals = {};
                    $(node).before($('<tr class="lpg-empty-group-row">').append($('<td colspan="14">').text(group)));
                }
                quantityFields.concat(['ending_quantity']).forEach(field => totals[field] = (totals[field] || 0) + Number(row[field] || 0));
                lastNode = node;
            });
            if (lastNode) $(lastNode).after(subtotal());
        }
    });

    function showModal(element) { bootstrap.Modal.getOrCreateInstance(element).show(); }
    function hideModal(element) { bootstrap.Modal.getOrCreateInstance(element).hide(); }
    function errorText(xhr, fallback) {
        const errors = xhr.responseJSON?.errors || {};
        return Object.values(errors).flat().join(' ') || xhr.responseJSON?.message || fallback;
    }
    function clearErrors(state) {
        $(state.form).find('.is-invalid').removeClass('is-invalid');
        $(state.form).find('.lpg-empty-form-error').text('').addClass('d-none');
    }
    function showFormError(state, message) {
        $(state.form).find('.lpg-empty-form-error').text(message).removeClass('d-none');
        $(state.modal).find('.modal-body').scrollTop(0);
    }
    function setQuantityAccess(state, enabled) {
        $(state.form).find('.lpg-empty-quantity').prop('disabled', true);
        state.fields.forEach(field => $(state.form).find('[name="' + field + '"]').prop('disabled', !enabled));
        $(state.form).find('textarea').prop('disabled', !enabled);
    }
    function updateEnding(state) {
        const quantities = {...state.saved};
        state.fields.forEach(field => quantities[field] = Number($(state.form).find('[name="' + field + '"]').val() || 0));
        const beginning = Number(quantities.beginning_quantity || 0);
        const additions = additionFields.reduce((sum, field) => sum + Number(quantities[field] || 0), 0);
        const deductions = deductionFields.reduce((sum, field) => sum + Number(quantities[field] || 0), 0);
        const overlap = Number(state.saved.duplicate_received_quantity || 0);
        const ending = beginning + additions - deductions - overlap;
        Object.entries({beginning, additions, deductions, overlap, ending}).forEach(([field, value]) => {
            $(state.form).find('[data-summary-field="' + field + '"]').text(number(value));
        });
        $(state.form).find('[data-summary-field="ending"]').toggleClass('text-danger', ending < 0);
        modeFields[state.mode].forEach(field => state.form.elements.namedItem(field).setCustomValidity(''));
        state.form.elements.namedItem(state.fields[0]).setCustomValidity(ending < 0 ? 'Deductions cannot exceed beginning inventory plus additions.' : '');
    }
    function loadProduct(state) {
        const version = ++state.version;
        if (state.lookup) state.lookup.abort();
        clearErrors(state);
        state.saved = {};
        state.loaded = false;
        state.form.classList.remove('was-validated');
        const form = $(state.form);
        const product = form.find('[name="product_idx"]')[0];
        form.find('[name="cashiers_report_lpg_empty_inventory_id"]').val(0);
        form.find('[name="remarks"]').val('');
        state.fields.forEach(field => form.find('[name="' + field + '"]').val(0));
        form.find('.lpg-empty-capacity-label').text(product.value ? (product.selectedOptions[0]?.dataset.capacity || 'Capacity not set') + ' — count quantities in pieces.' : 'Grouping follows the capacity set on the product.');
        updateEnding(state);
        if (!product.value) {
            setQuantityAccess(state, true);
            state.button.prop('disabled', false).text('Save ' + state.title);
            return;
        }
        setQuantityAccess(state, false);
        state.button.prop('disabled', true).text('Loading saved totals...');
        state.lookup = $.ajax({url: "{{ route('LpgEmptyInventoryInfo') }}", type: 'POST', headers: headers, data: {cashiers_report_id: reportId, product_idx: product.value}})
            .done(response => {
                if (version !== state.version) return;
                const record = response.data;
                state.saved = record || {};
                if (state.fields.some(field => (record?.automatic_fields || []).includes(field))) {
                    showFormError(state, 'This quantity now comes from source transactions. Use View to edit its sale, swapping, or on-loan record.');
                    return;
                }
                form.find('[name="cashiers_report_lpg_empty_inventory_id"]').val(record?.cashiers_report_lpg_empty_inventory_id || 0);
                state.fields.forEach(field => form.find('[name="' + field + '"]').val(record?.[field] ?? 0));
                form.find('[name="remarks"]').val(record?.remarks || '');
                state.loaded = true;
                setQuantityAccess(state, true);
                state.button.prop('disabled', false);
                updateEnding(state);
            })
            .fail((xhr, status) => {
                if (version !== state.version || status === 'abort') return;
                showFormError(state, errorText(xhr, 'Unable to load saved totals. Select the product again to retry.'));
            })
            .always(() => {
                if (version !== state.version) return;
                state.lookup = null;
                state.button.text('Save ' + state.title);
            });
    }
    $('.lpg-empty-entry-form').each(function () {
        const mode = this.dataset.entryMode;
        const modal = this.closest('.modal');
        const state = states[mode] = {mode, fields: modeFields[mode], title: modeNames[mode], form: this, modal, saved: {}, loaded: false, version: 0, lookup: null, saving: false, button: $(modal).find('.lpg-empty-save')};
        $(this).on('input change', '.lpg-empty-quantity', () => updateEnding(state));
        $(this).find('[name="product_idx"]').on('change', () => loadProduct(state));
        $(this).on('submit', function (event) {
            event.preventDefault();
            if (state.saving) return;
            clearErrors(state);
            updateEnding(state);
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                this.reportValidity();
                return;
            }
            if (!state.loaded) { showFormError(state, 'Load the product totals before saving.'); return; }
            const id = Number($(this).find('[name="cashiers_report_lpg_empty_inventory_id"]').val() || 0);
            state.button.prop('disabled', true).text('Saving...');
            const payload = $(this).serialize();
            state.saving = true;
            $(this).find('[name="product_idx"]').prop('disabled', true);
            $.ajax({url: id ? "{{ route('UpdateLpgEmptyInventory') }}" : "{{ route('SaveLpgEmptyInventory') }}", type: 'POST', headers: headers, data: payload})
                .done(response => {
                    state.saving = false;
                    hideModal(modal);
                    table.ajax.reload(null, false);
                    if (typeof showSuccessModal === 'function') showSuccessModal(response.message);
                })
                .fail(xhr => {
                    showFormError(state, errorText(xhr, 'Unable to save empty stock.'));
                    Object.keys(xhr.responseJSON?.errors || {}).forEach(field => $(this).find('[name="' + field + '"]').addClass('is-invalid'));
                })
                .always(() => {
                    state.saving = false;
                    state.button.prop('disabled', false).text('Save ' + state.title);
                    $(this).find('[name="product_idx"]').prop('disabled', false);
                });
        });
        $(modal).on('hide.bs.modal', function (event) { if (state.saving) event.preventDefault(); });
    });
    function openEntry(mode, productId, field) {
        const state = states[mode];
        if (!state || (field && !modeFields[mode].includes(field))) return;
        state.fields = field ? [field] : modeFields[mode];
        state.title = field ? quantityLabels[field] : modeNames[mode];
        state.form.reset();
        state.form.classList.remove('was-validated');
        clearErrors(state);
        $(state.form).find('[name="entry_field"]').val(field || '');
        $(state.form).find('.lpg-empty-quantity-field').each(function () {
            $(this).toggleClass('d-none', !state.fields.includes(this.dataset.quantityField));
        });
        $(state.modal).find('.modal-title').text(state.title);
        $(state.form).find('.lpg-empty-entry-note').text('Enter the ' + state.title.toLowerCase() + ' total for this report. Quantities are in pieces.');
        $(state.form).find('[name="product_idx"]').prop('disabled', false).val(productId || '').trigger('change');
        showModal(state.modal);
    }
    $(document).on('click', '.lpg-empty-column-add', function () {
        const field = this.dataset.quantityField;
        if (quantityFields.includes(field)) addSource(field, Number(this.dataset.productId || 0));
    });
    function fillSaleProduct(productId) {
        const option = $('#lpgArSoProductList option').filter(function () { return Number($(this).attr('data-id')) === Number(productId); }).first();
        $('#ar_so_product').val(option.length ? option.val() : '').trigger('change');
    }
    function addSource(field, productId) {
        if (!automaticFields.includes(field)) { openEntry(fieldMode(field), productId, field); return; }
        if (field === 'refill_sell_out_quantity' || field === 'sold_quantity') {
            resetLpgArSoForm();
            $('#ar_so_type').val('Sales Order').trigger('change');
            $('#ar_so_stock_type').val(field === 'sold_quantity' ? 'EMPTY_CONTAINER' : 'REFILL_SWAP').trigger('change');
            if (field === 'sold_quantity') fillSaleProduct(productId);
            else $('#ar_so_empty_received_product_idx').val(productId || '').trigger('change');
            lpgArSoShowModal('#CashierReportLpgArSoModal');
        } else if (field === 'swap_in_quantity' || field === 'swap_out_quantity') {
            window.LpgSwappingEditor.add(productId, field === 'swap_in_quantity' ? 'RECEIVED' : 'REPLACED');
            const prefix = field === 'swap_in_quantity' ? 'received' : 'replaced';
            $('#' + prefix + '_product_idx').val(productId || '').trigger('change');
            $('#' + prefix + '_stock_state').val('EMPTY').trigger('change');
        } else {
            window.LpgOnLoanEditor.add(field === 'borrow_quantity' ? productId : 0);
            const prefix = field === 'return_quantity' ? 'return' : 'borrow';
            $('#' + prefix + '_product_idx').val(productId || '').trigger('change');
            $('#' + prefix + '_stock_state').val('EMPTY').trigger('change');
        }
    }
    function afterSourceClose(callback) {
        if (sourceModal.classList.contains('show')) {
            $(sourceModal).one('hidden.bs.modal', callback);
            hideModal(sourceModal);
        } else callback();
    }
    function openSources(field, productId, pending) {
        if (!pending && !quantityFields.includes(field)) return;
        const version = ++sourceVersion;
        if (sourceRequest) sourceRequest.abort();
        sourceState = {field, productId, rows: []};
        $('#LpgEmptyInventorySourcesLabel').text(pending ? 'Review Empty-stock Sources' : quantityLabels[field] + ' — Source Records');
        $('#lpg-empty-source-error').addClass('d-none').text('');
        $('#lpg-empty-source-total').text('—');
        $('#lpg-empty-source-add').prop('disabled', true);
        $('#lpg-empty-source-rows').html('<tr><td colspan="6" class="text-center py-3">Loading saved totals...</td></tr>');
        showModal(sourceModal);
        sourceRequest = $.ajax({url: "{{ route('GetLpgEmptyInventorySources') }}", type: 'POST', headers, data: {cashiers_report_id: reportId, field: field || '', product_idx: productId || '', pending: pending ? 1 : 0}})
            .done(response => {
                if (version !== sourceVersion) return;
                let rows = Array.isArray(response) ? response : (response.data || []);
                if (productId) rows = rows.filter(row => Number(row.product_idx) === productId);
                sourceState.rows = rows;
                $('#lpg-empty-source-rows').html(rows.length ? rows.map((row, index) =>
                    '<tr><td>' + escape(row.product_name || 'Unavailable product') + '</td><td>' + escape(({sale: 'Sale', swap: 'Swapping', loan: 'On-loan', manual: 'Manual'})[row.kind] || row.kind) + ' / ' + escape(row.stock_state) + (row.duplicate_swap_id ? '<br><small>Linked Swap #' + Number(row.duplicate_swap_id) + '</small>' : '') + '</td><td class="text-end">' + number(row.quantity) + '</td><td>' + escape(row.remarks || '—') + '</td><td>' + escape([row.reference, row.account].filter(Boolean).join(' / ') || '—') + '</td><td><button type="button" class="btn btn-sm btn-primary lpg-empty-source-edit" data-row-index="' + index + '"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit</button></td></tr>'
                ).join('') : '<tr><td colspan="6" class="text-center text-muted py-3">No saved empty-stock totals for this selection.</td></tr>');
                $('#lpg-empty-source-total').text(number(rows.reduce((sum, row) => sum + Number(row.quantity || 0), 0)));
                $('#lpg-empty-source-add').prop('disabled', !!pending);
            })
            .fail((xhr, status) => {
                if (version !== sourceVersion || status === 'abort') return;
                $('#lpg-empty-source-rows').empty();
                $('#lpg-empty-source-error').text(errorText(xhr, 'Unable to load saved totals. Close and try View again.')).removeClass('d-none');
            })
            .always(() => { if (version === sourceVersion) sourceRequest = null; });
    }
    $(document).on('click', '.lpg-empty-column-view', function () { openSources(this.dataset.quantityField, Number(this.dataset.productId || 0), false); });
    $('#lpg-empty-review-sources').on('click', () => openSources(null, 0, true));
    $('#lpg-empty-source-add').on('click', function () {
        if (!sourceState) return;
        const {field, productId} = sourceState;
        afterSourceClose(() => addSource(field, productId));
    });
    $('#lpg-empty-source-rows').on('click', '.lpg-empty-source-edit', function () {
        const row = sourceState?.rows[Number(this.dataset.rowIndex)];
        if (row) afterSourceClose(() => {
            if (row.kind === 'manual') openEntry(fieldMode(row.field), row.product_idx, row.field);
            else if (row.kind === 'sale') editLpgArSoRecord(row.record_id);
            else if (row.kind === 'swap') window.LpgSwappingEditor.edit(row.record_id);
            else if (row.kind === 'loan') window.LpgOnLoanEditor.edit(row.record_id);
        });
    });
    $(sourceModal).on('hidden.bs.modal', function () { ++sourceVersion; if (sourceRequest) sourceRequest.abort(); sourceRequest = null; });
    $('#refresh-lpg-empty-inventory').on('click', () => table.ajax.reload(null, false));
    $('#LpgEmptyInventoryTable').on('click', '.delete-lpg-empty-inventory', function () {
        deleteId = Number($(this).data('id'));
        const row = table.rows().data().toArray().find(item => Number(item.cashiers_report_lpg_empty_inventory_id) === deleteId);
        $('#lpg_empty_delete_item').text(row?.product_name || 'this product');
        $('#lpg_empty_delete_error').addClass('d-none');
        showModal(document.getElementById('CashierReportLpgEmptyInventoryDeleteModal'));
    });
    $('#confirm-delete-lpg-empty-inventory').on('click', function () {
        if (!deleteId) return;
        const button = $(this).prop('disabled', true);
        $.ajax({url: "{{ route('DeleteLpgEmptyInventory') }}", type: 'POST', headers: headers, data: {cashiers_report_id: reportId, cashiers_report_lpg_empty_inventory_id: deleteId}})
            .done(response => {
                hideModal(document.getElementById('CashierReportLpgEmptyInventoryDeleteModal'));
                table.ajax.reload(null, false);
                if (typeof showSuccessModal === 'function') showSuccessModal(response.message);
            })
            .fail(xhr => $('#lpg_empty_delete_error').text(errorText(xhr, 'Unable to delete empty stock.')).removeClass('d-none'))
            .always(() => button.prop('disabled', false));
    });
    $('#lpg-empty-inventory-tab').on('shown.bs.tab', () => table.columns.adjust());
    let sourceReloadTimer;
    $(document).on('lpg:inventory-changed', () => { clearTimeout(sourceReloadTimer); sourceReloadTimer = setTimeout(() => table.ajax.reload(null, false), 150); });
    $('#LpgSwappingTable, #LpgOnLoanTable').on('xhr.dt', () => { clearTimeout(sourceReloadTimer); sourceReloadTimer = setTimeout(() => table.ajax.reload(null, false), 150); });
});
</script>
