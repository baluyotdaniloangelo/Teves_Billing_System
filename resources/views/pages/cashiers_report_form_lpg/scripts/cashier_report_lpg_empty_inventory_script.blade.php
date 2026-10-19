<script>
$(function () {
    const reportId = {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }};
    const quantityFields = @json(array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS));
    const additionFields = @json(array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::ADDITIONS));
    const deductionFields = @json(array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::DEDUCTIONS));
    const form = document.getElementById('CashierReportLpgEmptyInventoryForm');
    const modal = document.getElementById('CashierReportLpgEmptyInventoryModal');
    let deleteId = 0;
    const number = value => Number(value || 0).toLocaleString('en-PH');
    const headers = {'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json'};

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
                .done(rows => callback({data: Array.isArray(rows) ? rows : (rows.data || [])}))
                .fail(xhr => {
                    $('#lpg_empty_table_error').text(xhr.responseJSON?.message || 'Unable to load empty-stock inventory.').removeClass('d-none');
                    callback({data: []});
                });
        },
        columns: [
            {data: 'product_name', defaultContent: '-', render: $.fn.dataTable.render.text()},
            ...quantityFields.map(field => ({data: field, className: 'text-end', render: number})),
            {data: 'ending_quantity', className: 'text-end fw-bold', render: number},
            {data: 'remarks', defaultContent: '-', render: $.fn.dataTable.render.text()},
            {data: null, className: 'text-nowrap text-center', render: function (data, type, row) {
                const id = Number(row.cashiers_report_lpg_empty_inventory_id);
                return `<button type="button" class="btn btn-sm btn-primary edit-lpg-empty-inventory" data-id="${id}" title="Edit" aria-label="Edit empty stock"><i class="bi bi-pencil-square" aria-hidden="true"></i></button> <button type="button" class="btn btn-sm btn-danger delete-lpg-empty-inventory" data-id="${id}" title="Delete" aria-label="Delete empty stock"><i class="bi bi-trash3" aria-hidden="true"></i></button>`;
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
    function clearErrors() {
        $(form).find('.is-invalid').removeClass('is-invalid');
        $('#lpg_empty_form_error').text('').addClass('d-none');
    }
    function updateEnding() {
        const value = field => Number($(form).find('[name="' + field + '"]').val() || 0);
        const ending = value('beginning_quantity') + additionFields.reduce((sum, field) => sum + value(field), 0) - deductionFields.reduce((sum, field) => sum + value(field), 0);
        $('#lpg_empty_ending_quantity').text(number(ending)).toggleClass('text-danger', ending < 0);
        document.getElementById('lpg_empty_beginning_quantity').setCustomValidity(ending < 0 ? 'Deductions cannot exceed beginning inventory plus additions.' : '');
    }
    function openEntry(record) {
        form.reset();
        form.classList.remove('was-validated');
        clearErrors();
        $(form).find('[name="cashiers_report_lpg_empty_inventory_id"]').val(record?.cashiers_report_lpg_empty_inventory_id || 0);
        $('#lpg_empty_product_idx').val(record?.product_idx || '');
        quantityFields.forEach(field => $(form).find('[name="' + field + '"]').val(record?.[field] ?? 0));
        $('#lpg_empty_remarks').val(record?.remarks || '');
        $('#LpgEmptyInventoryModalLabel').text(record ? 'Edit Empty Stock' : 'Add Empty Stock');
        $('#save-lpg-empty-inventory').text(record ? 'Update Empty Stock' : 'Save Empty Stock');
        $('#lpg_empty_product_idx').trigger('change');
        updateEnding();
        showModal(modal);
    }
    $('#add-lpg-empty-inventory').on('click', () => openEntry());
    $(form).on('input change', '.lpg-empty-quantity', updateEnding);
    $('#lpg_empty_product_idx').on('change', function () {
        const option = this.selectedOptions[0];
        $('#lpg_empty_capacity_label').text(this.value ? (option?.dataset.capacity || 'Capacity not set') + ' — count quantities in pieces.' : 'Grouping follows the capacity set on the product.');
    });
    $(form).on('submit', function (event) {
        event.preventDefault();
        clearErrors();
        updateEnding();
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            form.reportValidity();
            return;
        }
        const id = Number($(form).find('[name="cashiers_report_lpg_empty_inventory_id"]').val() || 0);
        const button = $('#save-lpg-empty-inventory');
        const original = button.html();
        button.prop('disabled', true).text('Saving...');
        $.ajax({url: id ? "{{ route('UpdateLpgEmptyInventory') }}" : "{{ route('SaveLpgEmptyInventory') }}", type: 'POST', headers: headers, data: $(form).serialize()})
            .done(response => {
                hideModal(modal);
                table.ajax.reload(null, false);
                if (typeof showSuccessModal === 'function') showSuccessModal(response.message);
            })
            .fail(xhr => {
                $('#lpg_empty_form_error').text(errorText(xhr, 'Unable to save empty stock.')).removeClass('d-none');
                Object.keys(xhr.responseJSON?.errors || {}).forEach(field => $(form).find('[name="' + field + '"]').addClass('is-invalid'));
                $(modal).find('.modal-body').scrollTop(0);
            })
            .always(() => button.prop('disabled', false).html(original));
    });
    $('#LpgEmptyInventoryTable').on('click', '.edit-lpg-empty-inventory', function () {
        $.ajax({url: "{{ route('LpgEmptyInventoryInfo') }}", type: 'POST', headers: headers, data: {cashiers_report_id: reportId, cashiers_report_lpg_empty_inventory_id: $(this).data('id')}})
            .done(response => openEntry(response.data))
            .fail(xhr => $('#lpg_empty_table_error').text(errorText(xhr, 'Unable to load empty stock.')).removeClass('d-none'));
    });
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
});
</script>
