<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });
    const reportId = {{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }};
    const nonContainerProductIds = @json(\App\Services\LpgKitchenProducts::ids());
    const swappingEntriesModal = document.getElementById('LpgSwappingEntriesModal');
    const escapeText = value => $('<div>').text(value == null ? '' : String(value)).html();

    function syncStockStates() {
        ['received', 'replaced', 'borrow', 'return'].forEach(prefix => {
            const productId = Number($('#' + prefix + '_product_idx').val() || 0);
            const select = $('#' + prefix + '_stock_state');
            const relevant = productId > 0 && !$('#' + prefix + '_product_idx').prop('disabled') && !nonContainerProductIds.includes(productId);
            select.closest('.lpg-container-stock-state-field').toggleClass('d-none', !relevant);
            select.prop('disabled', !relevant).prop('required', relevant && Number($('#' + prefix + '_quantity').val() || 0) > 0);
            $('#' + prefix + '_quantity').attr('step', relevant && select.val() === 'EMPTY' ? '1' : '0.01');
        });
    }
    function applyStockStates(kind, record) {
        const defaults = kind === 'swap' ? {received: 'EMPTY', replaced: 'FILLED'} : {borrow: 'FILLED', return: 'EMPTY'};
        Object.keys(defaults).forEach(prefix => $('#' + prefix + '_stock_state').val(record ? (record[prefix + '_stock_state'] || '') : defaults[prefix]));
        syncStockStates();
    }
    $('#received_product_idx, #replaced_product_idx, #borrow_product_idx, #return_product_idx, #received_quantity, #replaced_quantity, #borrow_quantity, #return_quantity, #received_stock_state, #replaced_stock_state, #borrow_stock_state, #return_stock_state').on('input change', syncStockStates);
    const tables = {
        swapping: createContainerTable('#LpgSwappingTable', "{{ route('GetLpgSwappingList') }}", [
            indexColumn(), {data:'client_name', defaultContent:'-', render:$.fn.dataTable.render.text()}, productColumn('received'), quantityColumn('received_quantity'), productColumn('replaced'), quantityColumn('replaced_quantity'), {data:'remarks', defaultContent:'-', render:$.fn.dataTable.render.text()}, actionColumn('Swapping')
        ], 'No swapping records.'),
        onLoan: createContainerTable('#LpgOnLoanTable', "{{ route('GetLpgOnLoanList') }}", [
            indexColumn(), {data:'client_name', defaultContent:'-'}, {data:'dr_number', defaultContent:'-'}, movementColumn('borrow'), movementColumn('return'), {data:'remarks', defaultContent:'-'}, actionColumn('OnLoan')
        ], 'No on-loan records.')
    };

    $('#add-lpg-swapping').on('click', () => openSwapping(null, 'RECEIVED'));
    $('#add-lpg-swapping-replaced').on('click', () => openSwapping(null, 'REPLACED'));
    function syncSwappingMode() {
        const mode = $('#lpg_swapping_entry_mode').val();
        ['received', 'replaced'].forEach(prefix => {
            const active = mode === 'BOTH' || mode === prefix.toUpperCase();
            const section = $('.lpg-swap-side[data-swap-side="' + prefix + '"]').toggleClass('d-none', !active);
            section.find('select, input').prop('disabled', !active);
            $('#' + prefix + '_product_idx, #' + prefix + '_quantity').prop('required', active);
        });
        syncStockStates();
        const title = mode === 'BOTH' ? 'Swapping' : (mode === 'RECEIVED' ? 'Received' : 'Replaced / Issued');
        $('#CashierReportLpgSwappingModalLabel').text((Number($('#cashiers_report_lpg_swapping_id').val()) > 0 ? 'Edit ' : 'Add ') + title);
        window.LpgSwappingReturns.syncMode();
    }
    $('#lpg_swapping_entry_mode').on('change', syncSwappingMode);
    window.LpgSwappingEditor = {
        add: function (productId, mode) {
            const entryMode = mode || (productId ? 'REPLACED' : 'RECEIVED');
            openSwapping(null, entryMode);
            $('#' + (entryMode === 'RECEIVED' ? 'received' : 'replaced') + '_product_idx').val(productId || '').trigger('change');
            if (entryMode === 'REPLACED') window.LpgSwappingReturns.prefill(productId);
        },
        edit: function (id) {
            $.post("{{ route('LpgSwappingInfo') }}", {cashiers_report_lpg_swapping_id: id})
                .done(r => openSwapping(r.data || r))
                .fail(() => { if (typeof showValidationErrorModal === 'function') showValidationErrorModal('Unable to load swapping record.'); });
        }
    };
    $('#add-lpg-on-loan').on('click', () => openOnLoan());
    window.LpgOnLoanEditor = {
        add: function (productId) { openOnLoan(); $('#borrow_product_idx').val(productId || '').trigger('change'); },
        edit: function (id) {
            $.post("{{ route('LpgOnLoanInfo') }}", {cashiers_report_lpg_on_loan_id: id})
                .done(r => openOnLoan(r.data || r))
                .fail(() => { if (typeof showValidationErrorModal === 'function') showValidationErrorModal('Unable to load on-loan record.'); });
        }
    };

    $('#CashierReportLpgSwappingForm').on('submit', function (event) {
        event.preventDefault();
        if (!window.LpgSwappingReturns.beforeSubmit()) return;
        saveContainerForm(this, '#save-lpg-swapping', '#CashierReportLpgSwappingModal', 'swapping', 'cashiers_report_lpg_swapping_id', "{{ route('SaveLpgSwapping') }}", "{{ route('UpdateLpgSwapping') }}", 'Swapping record');
    });
    $('#CashierReportLpgOnLoanForm').on('submit', function (event) {
        event.preventDefault(); saveContainerForm(this, '#save-lpg-on-loan', '#CashierReportLpgOnLoanModal', 'onLoan', 'cashiers_report_lpg_on_loan_id', "{{ route('SaveLpgOnLoan') }}", "{{ route('UpdateLpgOnLoan') }}", 'On-loan record');
    });

    function afterSwappingEntriesClose(callback) {
        if (swappingEntriesModal?.classList.contains('show')) {
            $(swappingEntriesModal).one('hidden.bs.modal', callback);
            hideContainerModal('#LpgSwappingEntriesModal');
        } else callback();
    }
    $(document).on('click', '.ViewLpgSwappingEntries', function () {
        const group = tables.swapping.rows().data().toArray().find(row => row.group_key === this.dataset.groupKey);
        if (!group) return;
        $('#lpg-swapping-entries-selection').text([group.client_name, group.product_name].filter(Boolean).join(' — '));
        $('#lpg-swapping-entry-rows').html(group.entries.map(entry => '<tr><td>' + (entry.source_kind === 'kitchen_damaged' ? 'Damaged #' : 'Swap #') + Number(entry.source_id || entry.cashiers_report_lpg_swapping_id) + '</td><td>' + swappingEntryProduct(entry, 'received') + '</td><td class="text-end">' + (entry.received_product_idx ? formatQty(entry.received_quantity) : '—') + '</td><td>' + swappingEntryProduct(entry, 'replaced') + '</td><td class="text-end">' + (entry.replaced_product_idx ? formatQty(entry.replaced_quantity) : '—') + '</td><td>' + escapeText(entry.remarks || '—') + '</td><td class="text-nowrap">' + swappingRecordActions(entry) + '</td></tr>').join(''));
        $('#lpg-swapping-entries-totals').text('Selected item — Received: ' + formatQty(group.received_quantity) + ' / Replaced: ' + formatQty(group.replaced_quantity));
        showContainerModal('#LpgSwappingEntriesModal');
    });
    $(document).on('click', '.EditLpgSwapping', function () { const id = $(this).data('id'); afterSwappingEntriesClose(() => window.LpgSwappingEditor.edit(id)); });
    $(document).on('click', '.EditLpgSwappingDamaged', function () { const id = Number(this.dataset.id); afterSwappingEntriesClose(() => window.LpgKitchenMovementEditor.edit(id)); });
    $(document).on('click', '.DeleteLpgSwappingDamaged', function () { const id = Number(this.dataset.id); afterSwappingEntriesClose(() => window.LpgKitchenMovementEditor.remove(id)); });
    $(document).on('lpg:kitchen-movement-changed', () => { if (tables.swapping) tables.swapping.ajax.reload(null, false); });
    $(document).on('click', '.EditLpgOnLoan', function () { $.post("{{ route('LpgOnLoanInfo') }}", {cashiers_report_lpg_on_loan_id: $(this).data('id')}).done(r => openOnLoan(r.data || r)); });
    $(document).on('click', '.DeleteLpgSwapping', function () {
        const id = $(this).data('id'), paired = this.dataset.paired === '1';
        afterSwappingEntriesClose(() => {
            $('#lpg_swapping_delete_id').val(id);
            $('#lpg_swapping_delete_error').empty().addClass('d-none');
            $('#lpg_swapping_delete_message').text(paired ? 'Delete this record? It contains both received and replaced items; both movements will be deleted.' : 'Delete this swapping entry?');
            showContainerModal('#CashierReportLpgSwappingDeleteModal');
        });
    });
    $(document).on('click', '.DeleteLpgOnLoan', function () { $('#lpg_on_loan_delete_id').val($(this).data('id')); showContainerModal('#CashierReportLpgOnLoanDeleteModal'); });
    $('#confirm-delete-lpg-swapping').on('click', () => deleteContainerRecord($('#lpg_swapping_delete_id').val(), "{{ route('DeleteLpgSwapping') }}", 'cashiers_report_lpg_swapping_id', '#CashierReportLpgSwappingDeleteModal', tables.swapping, 'Swapping record'));
    $('#confirm-delete-lpg-on-loan').on('click', () => deleteContainerRecord($('#lpg_on_loan_delete_id').val(), "{{ route('DeleteLpgOnLoan') }}", 'cashiers_report_lpg_on_loan_id', '#CashierReportLpgOnLoanDeleteModal', tables.onLoan, 'On-loan record'));

    function createContainerTable(selector, url, columns, emptyMessage) {
        if (!$(selector).length) return null;
        return $(selector).DataTable({processing:true,responsive:true,paging:true,searching:false,info:false,autoWidth:false,ajax:{url:url,type:'POST',data:function (data) { data.CashiersReportId = reportId; data.cashiers_report_id = reportId; if (selector === '#LpgSwappingTable') data.grouped = 1; return data; },dataSrc:r=>Array.isArray(r)?r:(r.data||[])},columns:columns,order:[[1,'asc']],language:{emptyTable:emptyMessage}});
    }
    function indexColumn(){ return {data:null,orderable:false,className:'text-center',render:(d,t,r,m)=>m.row+1}; }
    function productColumn(prefix){ return {data:null,render:(r,t)=>{const value=[r[prefix+'_product_name'],r[prefix+'_product_unit_measurement']].filter(Boolean).join(' ')||'-'; return t === 'display' ? escapeText(value) : value; }}; }
    function quantityColumn(field){ return {data:field,className:'text-end fw-semibold',render:(v,t,r)=>r[field.replace('_quantity','_product_idx')] ? formatQty(v) : '—'}; }
    function movementColumn(prefix){ return {data:null,render:r=>{const capacity = r[prefix+'_product_capacity']; const unit = r[prefix+'_product_capacity_unit']; const description = capacity !== null && capacity !== undefined && capacity !== '' && unit ? Number(capacity).toLocaleString('en-PH', {maximumFractionDigits:3}) + ' ' + unit : r[prefix+'_product_unit_measurement']; const product=[r[prefix+'_product_name'],description].filter(Boolean).join(' ') || '-'; return `${escapeText(product)}<br><small>Qty: ${formatQty(r[prefix+'_quantity'])} &nbsp; Alum: ${formatQty(r[prefix+'_alum_quantity'])}</small>`;}}; }
    function swappingRecordActions(row) {
        if (row.source_kind === 'kitchen_damaged') {
            const id = Number(row.source_id);
            return `<button type="button" class="btn btn-sm btn-primary EditLpgSwappingDamaged" data-id="${id}" title="Edit damaged receipt #${id}"><i class="bi bi-pencil-square"></i></button> <button type="button" class="btn btn-sm btn-danger DeleteLpgSwappingDamaged" data-id="${id}" title="Delete damaged receipt #${id}"><i class="bi bi-trash3-fill"></i></button>`;
        }
        const id = Number(row.cashiers_report_lpg_swapping_id);
        const paired = row.received_product_idx && row.replaced_product_idx ? 1 : 0;
        return `<button type="button" class="btn btn-sm btn-primary EditLpgSwapping" data-id="${id}" title="Edit entry #${id}"><i class="bi bi-pencil-square"></i></button> <button type="button" class="btn btn-sm btn-danger DeleteLpgSwapping" data-id="${id}" data-paired="${paired}" title="Delete entry #${id}"><i class="bi bi-trash3-fill"></i></button>`;
    }
    function swappingEntryProduct(row, prefix) {
        if (!row[prefix + '_product_idx']) return '—';
        const name = [row[prefix + '_product_name'], row[prefix + '_product_unit_measurement']].filter(Boolean).join(' ');
        const state = row[prefix + '_stock_state'];
        return escapeText(name || 'Unavailable product') + (state ? '<br><small class="text-muted">' + escapeText(state) + '</small>' : '');
    }
    function actionColumn(type){ return {data:null,orderable:false,className:'text-center text-nowrap',render:r=>{
        if (type === 'Swapping') {
            if (Number(r.entry_count || 0) > 1) return `<button type="button" class="btn btn-sm btn-primary ViewLpgSwappingEntries" data-group-key="${r.group_key}"><i class="bi bi-eye me-1"></i>View Entries (${Number(r.entry_count)})</button>`;
            return swappingRecordActions(r.entries?.[0] || r);
        }
        return `<button type="button" class="btn btn-sm btn-primary EditLpg${type}" data-id="${r.cashiers_report_lpg_on_loan_id}" title="Edit"><i class="bi bi-pencil-square"></i></button> <button type="button" class="btn btn-sm btn-danger DeleteLpg${type}" data-id="${r.cashiers_report_lpg_on_loan_id}" title="Delete"><i class="bi bi-trash3-fill"></i></button>`;
    }}; }
    function formatQty(v){ return Number(v||0).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2}); }
    function openSwapping(r, mode) {
        const f = $('#CashierReportLpgSwappingForm')[0];
        window.LpgSwappingReturns.open(r);
        f.reset(); f.classList.remove('was-validated'); clearContainerErrors(f);
        $('#cashiers_report_lpg_swapping_id').val(r?.cashiers_report_lpg_swapping_id || 0);
        $('#lpg_swapping_client_idx').val(r?.client_idx || '');
        $('#received_product_idx').val(r?.received_product_idx || '').trigger('change');
        $('#received_quantity').val(r?.received_quantity || '');
        $('#replaced_product_idx').val(r?.replaced_product_idx || '').trigger('change');
        $('#replaced_quantity').val(r?.replaced_quantity || '');
        $('#lpg_swapping_remarks').val(r?.remarks || '');
        const combined = !!(r?.received_product_idx && r?.replaced_product_idx);
        const entryMode = r ? (combined ? 'BOTH' : (r.received_product_idx ? 'RECEIVED' : 'REPLACED')) : (mode || 'RECEIVED');
        $('#lpg_swapping_entry_mode').val(entryMode).prop('disabled', combined);
        $('#lpg_swapping_entry_mode option[value="BOTH"]').prop('hidden', !combined);
        $('#lpg-swapping-combined-note').toggleClass('d-none', !combined);
        applyStockStates('swap', r);
        syncSwappingMode();
        showContainerModal('#CashierReportLpgSwappingModal');
    }
    function openOnLoan(r) { const f=$('#CashierReportLpgOnLoanForm')[0]; f.reset(); f.classList.remove('was-validated'); clearContainerErrors(f); $('#cashiers_report_lpg_on_loan_id').val(r?.cashiers_report_lpg_on_loan_id||0); $('#lpg_on_loan_client_idx').val(r?.client_idx||''); $('#lpg_on_loan_dr_number').val(r?.dr_number||''); $('#borrow_product_idx').val(r?.borrow_product_idx||'').trigger('change'); $('#borrow_quantity').val(r?.borrow_quantity??0); $('#borrow_alum_quantity').val(r?.borrow_alum_quantity??0); $('#return_product_idx').val(r?.return_product_idx||'').trigger('change'); $('#return_quantity').val(r?.return_quantity??0); $('#return_alum_quantity').val(r?.return_alum_quantity??0); $('#lpg_on_loan_remarks').val(r?.remarks||''); $('#CashierReportLpgOnLoanModalLabel').html(`<i class="bi bi-box-arrow-up-right me-2"></i>${r?'Edit':'Add'} On-Loan`); applyStockStates('loan', r); showContainerModal('#CashierReportLpgOnLoanModal'); }
    function saveContainerForm(form, buttonSelector, modalSelector, tableKey, idField, saveUrl, updateUrl, label) {
        if (form.dataset.containerSaving === '1') return;
        clearContainerErrors(form);
        if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
        const button = $(buttonSelector).prop('disabled', true);
        form.dataset.containerSaving = '1';
        $.ajax({url: Number($(form).find('[name="' + idField + '"]').val() || 0) > 0 ? updateUrl : saveUrl, type: 'POST', data: $(form).serialize()})
            .done(r => { hideContainerModal(modalSelector); tables[tableKey].ajax.reload(null, false); if (typeof showSuccessModal === 'function') showSuccessModal(r.message || label + ' saved successfully.'); })
            .fail(xhr => showContainerErrors(form, xhr.responseJSON?.errors || {entry: xhr.responseJSON?.message || 'Unable to save entry.'}))
            .always(() => { delete form.dataset.containerSaving; button.prop('disabled', false); });
    }
    function deleteContainerRecord(id,url,idField,modal,table,label){ if(!id)return; $.post(url,{[idField]:id}).done(r=>{hideContainerModal(modal);table.ajax.reload(null,false);if(typeof showSuccessModal==='function')showSuccessModal(r.message||label+' deleted successfully.');}).fail(xhr=>{
        const text = Object.values(xhr.responseJSON?.errors || {}).flat().join(' ') || xhr.responseJSON?.message || 'Unable to delete record.';
        if (idField === 'cashiers_report_lpg_swapping_id') $('#lpg_swapping_delete_error').text(text).removeClass('d-none');
        else if (typeof showValidationErrorModal === 'function') showValidationErrorModal(text);
    }); }
    function clearContainerErrors(form){ $(form).find('.is-invalid').removeClass('is-invalid'); $(form).find('.invalid-feedback').text(''); $(form).find('.lpg-container-form-error').empty().addClass('d-none'); }
    function showContainerErrors(form,errors){ $(form).find('.lpg-container-form-error').text(Object.values(errors).flat().join(' ')).toggleClass('d-none', !Object.keys(errors).length); Object.keys(errors).forEach(field=>{const input=$(form).find('[name="'+field+'"]'); input.addClass('is-invalid'); input.siblings('.invalid-feedback').text(Array.isArray(errors[field])?errors[field][0]:errors[field]);}); }
    function showContainerModal(selector){ const el=document.querySelector(selector); if(el&&window.bootstrap)bootstrap.Modal.getOrCreateInstance(el).show(); }
    function hideContainerModal(selector){ const el=document.querySelector(selector); if(el&&window.bootstrap)bootstrap.Modal.getOrCreateInstance(el).hide(); }
});
</script>

