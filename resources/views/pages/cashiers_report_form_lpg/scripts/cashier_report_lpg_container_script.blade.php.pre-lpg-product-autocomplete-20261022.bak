<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });
    const reportId = {{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }};
    const tables = {
        swapping: createContainerTable('#LpgSwappingTable', "{{ route('GetLpgSwappingList') }}", [
            indexColumn(), {data:'client_name', defaultContent:'-'}, productColumn('received'), quantityColumn('received_quantity'), productColumn('replaced'), quantityColumn('replaced_quantity'), {data:'remarks', defaultContent:'-'}, actionColumn('Swapping')
        ], 'No swapping records.'),
        onLoan: createContainerTable('#LpgOnLoanTable', "{{ route('GetLpgOnLoanList') }}", [
            indexColumn(), {data:'client_name', defaultContent:'-'}, {data:'dr_number', defaultContent:'-'}, movementColumn('borrow'), movementColumn('return'), {data:'remarks', defaultContent:'-'}, actionColumn('OnLoan')
        ], 'No on-loan records.')
    };

    $('#add-lpg-swapping').on('click', () => openSwapping());
    $('#add-lpg-on-loan').on('click', () => openOnLoan());

    $('#CashierReportLpgSwappingForm').on('submit', function (event) {
        event.preventDefault(); saveContainerForm(this, '#save-lpg-swapping', '#CashierReportLpgSwappingModal', 'swapping', 'cashiers_report_lpg_swapping_id', "{{ route('SaveLpgSwapping') }}", "{{ route('UpdateLpgSwapping') }}", 'Swapping record');
    });
    $('#CashierReportLpgOnLoanForm').on('submit', function (event) {
        event.preventDefault(); saveContainerForm(this, '#save-lpg-on-loan', '#CashierReportLpgOnLoanModal', 'onLoan', 'cashiers_report_lpg_on_loan_id', "{{ route('SaveLpgOnLoan') }}", "{{ route('UpdateLpgOnLoan') }}", 'On-loan record');
    });

    $(document).on('click', '.EditLpgSwapping', function () { $.post("{{ route('LpgSwappingInfo') }}", {cashiers_report_lpg_swapping_id: $(this).data('id')}).done(r => openSwapping(r.data || r)); });
    $(document).on('click', '.EditLpgOnLoan', function () { $.post("{{ route('LpgOnLoanInfo') }}", {cashiers_report_lpg_on_loan_id: $(this).data('id')}).done(r => openOnLoan(r.data || r)); });
    $(document).on('click', '.DeleteLpgSwapping', function () { $('#lpg_swapping_delete_id').val($(this).data('id')); showContainerModal('#CashierReportLpgSwappingDeleteModal'); });
    $(document).on('click', '.DeleteLpgOnLoan', function () { $('#lpg_on_loan_delete_id').val($(this).data('id')); showContainerModal('#CashierReportLpgOnLoanDeleteModal'); });
    $('#confirm-delete-lpg-swapping').on('click', () => deleteContainerRecord($('#lpg_swapping_delete_id').val(), "{{ route('DeleteLpgSwapping') }}", 'cashiers_report_lpg_swapping_id', '#CashierReportLpgSwappingDeleteModal', tables.swapping, 'Swapping record'));
    $('#confirm-delete-lpg-on-loan').on('click', () => deleteContainerRecord($('#lpg_on_loan_delete_id').val(), "{{ route('DeleteLpgOnLoan') }}", 'cashiers_report_lpg_on_loan_id', '#CashierReportLpgOnLoanDeleteModal', tables.onLoan, 'On-loan record'));

    function createContainerTable(selector, url, columns, emptyMessage) {
        if (!$(selector).length) return null;
        return $(selector).DataTable({processing:true,responsive:true,paging:true,searching:false,info:false,autoWidth:false,ajax:{url:url,type:'POST',data:function (data) { data.CashiersReportId = reportId; data.cashiers_report_id = reportId; return data; },dataSrc:r=>Array.isArray(r)?r:(r.data||[])},columns:columns,order:[[1,'asc']],language:{emptyTable:emptyMessage}});
    }
    function indexColumn(){ return {data:null,orderable:false,className:'text-center',render:(d,t,r,m)=>m.row+1}; }
    function productColumn(prefix){ return {data:null,render:r=>[r[prefix+'_product_name'],r[prefix+'_product_unit_measurement']].filter(Boolean).join(' ')||'-'}; }
    function quantityColumn(field){ return {data:field,className:'text-end fw-semibold',render:v=>formatQty(v)}; }
    function movementColumn(prefix){ return {data:null,render:r=>{const product=[r[prefix+'_product_name'],r[prefix+'_product_unit_measurement']].filter(Boolean).join(' ') || '-'; return `${product}<br><small>Qty: ${formatQty(r[prefix+'_quantity'])} &nbsp; Alum: ${formatQty(r[prefix+'_alum_quantity'])}</small>`;}}; }
    function actionColumn(type){ return {data:null,orderable:false,className:'text-center text-nowrap',render:r=>`<button type="button" class="btn btn-sm btn-primary EditLpg${type}" data-id="${r[type === 'Swapping' ? 'cashiers_report_lpg_swapping_id' : 'cashiers_report_lpg_on_loan_id']}" title="Edit"><i class="bi bi-pencil-square"></i></button> <button type="button" class="btn btn-sm btn-danger DeleteLpg${type}" data-id="${r[type === 'Swapping' ? 'cashiers_report_lpg_swapping_id' : 'cashiers_report_lpg_on_loan_id']}" title="Delete"><i class="bi bi-trash3-fill"></i></button>`}; }
    function formatQty(v){ return Number(v||0).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2}); }
    function openSwapping(r) { const f=$('#CashierReportLpgSwappingForm')[0]; f.reset(); f.classList.remove('was-validated'); clearContainerErrors(f); $('#cashiers_report_lpg_swapping_id').val(r?.cashiers_report_lpg_swapping_id||0); $('#lpg_swapping_client_idx').val(r?.client_idx||''); $('#received_product_idx').val(r?.received_product_idx||''); $('#received_quantity').val(r?.received_quantity||''); $('#replaced_product_idx').val(r?.replaced_product_idx||''); $('#replaced_quantity').val(r?.replaced_quantity||''); $('#lpg_swapping_remarks').val(r?.remarks||''); $('#CashierReportLpgSwappingModalLabel').html(`<i class="bi bi-arrow-left-right me-2"></i>${r?'Edit':'Add'} Swapping`); showContainerModal('#CashierReportLpgSwappingModal'); }
    function openOnLoan(r) { const f=$('#CashierReportLpgOnLoanForm')[0]; f.reset(); f.classList.remove('was-validated'); clearContainerErrors(f); $('#cashiers_report_lpg_on_loan_id').val(r?.cashiers_report_lpg_on_loan_id||0); $('#lpg_on_loan_client_idx').val(r?.client_idx||''); $('#lpg_on_loan_dr_number').val(r?.dr_number||''); $('#borrow_product_idx').val(r?.borrow_product_idx||''); $('#borrow_quantity').val(r?.borrow_quantity??0); $('#borrow_alum_quantity').val(r?.borrow_alum_quantity??0); $('#return_product_idx').val(r?.return_product_idx||''); $('#return_quantity').val(r?.return_quantity??0); $('#return_alum_quantity').val(r?.return_alum_quantity??0); $('#lpg_on_loan_remarks').val(r?.remarks||''); $('#CashierReportLpgOnLoanModalLabel').html(`<i class="bi bi-box-arrow-up-right me-2"></i>${r?'Edit':'Add'} On-Loan`); showContainerModal('#CashierReportLpgOnLoanModal'); }
    function saveContainerForm(form, buttonSelector, modalSelector, tableKey, idField, saveUrl, updateUrl, label) { clearContainerErrors(form); if (!form.checkValidity()) { form.classList.add('was-validated'); return; } const button=$(buttonSelector); button.prop('disabled',true); $.ajax({url:Number($('[name="'+idField+'"]').val()||0)>0?updateUrl:saveUrl,type:'POST',data:$(form).serialize()}).done(r=>{hideContainerModal(modalSelector); tables[tableKey].ajax.reload(null,false); if(typeof showSuccessModal==='function')showSuccessModal(r.message||label+' saved successfully.');}).fail(xhr=>showContainerErrors(form,xhr.responseJSON?.errors||{})).always(()=>button.prop('disabled',false)); }
    function deleteContainerRecord(id,url,idField,modal,table,label){ if(!id)return; $.post(url,{[idField]:id}).done(r=>{hideContainerModal(modal);table.ajax.reload(null,false);if(typeof showSuccessModal==='function')showSuccessModal(r.message||label+' deleted successfully.');}).fail(xhr=>{if(typeof showValidationErrorModal==='function')showValidationErrorModal(xhr.responseJSON?.message||'Unable to delete record.');}); }
    function clearContainerErrors(form){ $(form).find('.is-invalid').removeClass('is-invalid'); $(form).find('.invalid-feedback').text(''); }
    function showContainerErrors(form,errors){ Object.keys(errors).forEach(field=>{const input=$(form).find('[name="'+field+'"]'); input.addClass('is-invalid'); input.siblings('.invalid-feedback').text(Array.isArray(errors[field])?errors[field][0]:errors[field]);}); }
    function showContainerModal(selector){ const el=document.querySelector(selector); if(el&&window.bootstrap)bootstrap.Modal.getOrCreateInstance(el).show(); }
    function hideContainerModal(selector){ const el=document.querySelector(selector); if(el&&window.bootstrap)bootstrap.Modal.getOrCreateInstance(el).hide(); }
});
</script>

