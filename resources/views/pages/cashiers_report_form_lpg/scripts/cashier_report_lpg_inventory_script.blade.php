<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });
    const inventorySelect = document.getElementById('lpg_inventory_product_idx');
    LpgInventoryProductOptionOrder = Array.from(inventorySelect.options);
    $('#CashierReportLpgInventoryModal').on('hidden.bs.modal', function () {
        LpgInventoryProductOptionOrder.forEach(option => inventorySelect.appendChild(option));
        window.LpgProductAutocomplete?.sync(inventorySelect);
    });

    initLpgInventoryTables();
    loadLpgStartingAuditStatus();

    $(document).on('click', '.lpg-inventory-add', function () {
        openLpgInventoryModal($(this).data('inventory-mode'));
    });

    $('#clear-lpg-inventory').on('click', function () {
        openLpgInventoryModal($('#lpg_inventory_mode').val(), null, null, LpgCurrentInventoryProductScope);
    });

    $('#CashierReportLpgInventoryForm').on('submit', function (event) {
        event.preventDefault();
        clearLpgInventoryErrors();

        const mode = $('#lpg_inventory_mode').val();
        const supplierRequired = ['DELIVERY', 'BO_REPLACEMENT', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'].includes(mode);
        const clientRequired = mode === 'BO_FROM_CUSTOMER';
        const auditRequired = mode === 'BEGINNING';
        if (auditRequired && LpgStartingAuditLocked) {
            showLpgInventoryErrors({inventory_mode: ['This Starting Audit is already submitted or approved and cannot be edited.']});
            return;
        }
        $('#lpg_inventory_supplier_idx').prop('required', supplierRequired);
        $('#lpg_inventory_client_idx').prop('required', clientRequired);
        $('#lpg_inventory_bo_returned_customer_quantity, #lpg_inventory_bo_during_delivery_quantity').prop('required', auditRequired);

        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        const recordId = Number($('#cashiers_report_lpg_inventory_id').val() || 0);
        const url = recordId > 0
            ? "{{ route('UpdateLpgInventory') }}"
            : "{{ route('SaveLpgInventory') }}";

        const button = $('#save-lpg-inventory');
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                hideLpgInventoryModal('#CashierReportLpgInventoryModal');
                reloadLpgInventoryTables();
                if (mode === 'BEGINNING') loadLpgStartingAuditStatus();
                if (typeof showSuccessModal === 'function') {
                    showSuccessModal(response.message || 'Inventory saved successfully.');
                }
            },
            error: function (xhr) {
                showLpgInventoryErrors(xhr.responseJSON?.errors || {});
            },
            complete: function () {
                button.prop('disabled', false).html('<i class="bi bi-save-fill me-2"></i>Save Inventory');
            }
        });
    });

    $(document).on('click', '.EditLpgInventory', function () {
        const id = $(this).data('id');
        $.post("{{ route('LpgInventoryInfo') }}", { cashiers_report_lpg_inventory_id: id })
            .done(function (response) {
                const record = response.data || response;
                openLpgInventoryModal(record.inventory_mode, record);
            })
            .fail(function () {
                if (typeof showValidationErrorModal === 'function') {
                    showValidationErrorModal('Unable to load the inventory record.');
                }
            });
    });

    $(document).on('click', '.RequestRevokeLpgInventory', function () {
        $('#lpg_inventory_revoke_id').val($(this).data('id'));
        $('#lpg_inventory_revoke_number').text($(this).data('number'));
        $('#lpg_inventory_revoke_item').text($(this).data('item'));
        $('#lpg_inventory_revoke_reason').val('');
        showLpgInventoryModal('#CashierReportLpgInventoryRevokeModal');
    });

    $('#submit-lpg-inventory-revoke').on('click', function () {
        const button = $(this);
        const reason = $('#lpg_inventory_revoke_reason').val().trim();
        if (!reason) {
            $('#lpg_inventory_revoke_reason').addClass('is-invalid');
            return;
        }
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');
        $.post("{{ route('lpg.inventory.revoke-request') }}", {
            cashiers_report_lpg_inventory_id: $('#lpg_inventory_revoke_id').val(), reason: reason
        }).done(function (response) {
            hideLpgInventoryModal('#CashierReportLpgInventoryRevokeModal');
            reloadLpgInventoryTables();
            if (typeof showSuccessModal === 'function') showSuccessModal(response.message);
        }).fail(function (xhr) {
            if (typeof showValidationErrorModal === 'function') showValidationErrorModal(xhr.responseJSON?.message || 'Unable to submit the revoke request.');
        }).always(function () {
            button.prop('disabled', false).html('<i class="bi bi-send me-2"></i>Request Revoke');
        });
    });
    $('#lpg_inventory_revoke_reason').on('input', function () { $(this).removeClass('is-invalid'); });

    $('#lpg-submit-starting-audit').on('click', function () {
        const button = $(this);
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Submitting…');
        $.post("{{ route('lpg.starting-audits.submit') }}", { report_id: $('#lpg-starting-audit-status').data('report-id') })
            .done(function (response) {
                if (typeof showSuccessModal === 'function') showSuccessModal(response.message);
                loadLpgStartingAuditStatus();
                reloadLpgInventoryTables();
            })
            .fail(function (xhr) {
                if (typeof showValidationErrorModal === 'function') showValidationErrorModal(xhr.responseJSON?.message || 'Unable to submit the Starting Audit.');
                button.prop('disabled', false).html('<i class="bi bi-send me-1"></i>Submit for Approval');
            });
    });
});

let LpgBeginningInventoryTable;
let LpgDeliveryTable;
let LpgBoReplacementTable;
let LpgInventoryProductOptionOrder = [];
let LpgCurrentInventoryProductScope = 'LPG';
let LpgStartingAuditLocked = false;
let LpgStartingAuditStatus = null;
const LpgInventoryProductScopes = {
    LPG: new Set(@json($product_data->whereIn('category_idx', \App\Services\LpgEmptyInventorySummary::categoryIds())->pluck('product_id')->map(fn($id) => (int) $id)->values())),
    KITCHEN: new Set(@json(\App\Services\LpgKitchenProducts::ids())),
    REGULATOR: new Set(@json(\App\Services\LpgRegulatorProducts::ids()))
};

function initLpgInventoryTables() {
    LpgBeginningInventoryTable = createLpgInventoryTable('#LpgBeginningInventoryTable', 'BEGINNING', [
        { data: null, className: 'text-center', orderable: false, render: function (data, type, row, meta) { return meta.row + 1; } },
        { data: 'transaction_no', className: 'text-nowrap fw-semibold' },
        { data: null, render: function (data, type, row) { return lpgInventoryProductLabel(row); } },
        { data: 'quantity', className: 'text-end fw-semibold', render: lpgInventoryQuantity },
        { data: 'bo_returned_customer_quantity', className: 'text-end', render: lpgInventoryQuantity },
        { data: 'bo_during_delivery_quantity', className: 'text-end', render: lpgInventoryQuantity },
        { data: 'audit_date', className: 'text-center', defaultContent: '-' },
        { data: 'remarks', defaultContent: '-' },
        lpgInventoryActionColumn('BEGINNING')
    ]);

    LpgDeliveryTable = createLpgInventoryTable('#LpgDeliveryTable', 'DELIVERY', [
        { data: null, className: 'text-center', orderable: false, render: function (data, type, row, meta) { return meta.row + 1; } },
        { data: 'transaction_no', className: 'text-nowrap fw-semibold' },
        { data: 'supplier_name', defaultContent: '-' },
        { data: 'delivery_reference_no', defaultContent: '-' },
        { data: null, render: function (data, type, row) { return lpgInventoryProductLabel(row); } },
        { data: 'quantity', className: 'text-end fw-semibold', render: lpgInventoryQuantity },
        { data: 'remarks', defaultContent: '-' },
        lpgInventoryActionColumn()
    ]);

    LpgBoReplacementTable = createLpgInventoryTable('#LpgBoReplacementTable', 'BO_REPLACEMENT', [
        { data: null, className: 'text-center', orderable: false, render: function (data, type, row, meta) { return meta.row + 1; } },
        { data: 'transaction_no', className: 'text-nowrap fw-semibold' },
        { data: 'supplier_name', defaultContent: '-' },
        { data: 'delivery_reference_no', defaultContent: '-' },
        { data: null, render: function (data, type, row) { return lpgInventoryProductLabel(row); } },
        { data: 'quantity', className: 'text-end fw-semibold', render: lpgInventoryQuantity },
        { data: 'remarks', defaultContent: '-' },
        lpgInventoryActionColumn()
    ]);
}

function createLpgInventoryTable(selector, mode, columns) {
    if (!$(selector).length) return null;
    if ($.fn.DataTable.isDataTable(selector)) $(selector).DataTable().destroy();

    const table = $(selector).DataTable({
        processing: true,
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('GetLpgInventoryList') }}",
            type: 'POST',
            data: function (data) {
                data.CashiersReportId = {{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }};
                data.inventory_mode = mode;
            },
            dataSrc: function (response) { return Array.isArray(response) ? response : (response.data || []); }
        },
        columns: columns,
        order: [[1, 'asc']],
        language: { emptyTable: inventoryEmptyMessage(mode) }
    });

    if (typeof autoAdjustColumns === 'function') autoAdjustColumns(table);
    return table;
}

function lpgInventoryActionColumn(mode) {
    return {
        data: null,
        orderable: false,
        searchable: false,
        className: 'text-center text-nowrap',
        render: function (data, type, row) {
            const item = lpgInventoryProductLabel(row).replace(/"/g, '&quot;');
            if (mode === 'BEGINNING' && LpgStartingAuditLocked) return '<span class="badge text-bg-secondary">' + (LpgStartingAuditStatus || 'Locked') + '</span>';
            if (row.revoke_request_pending) return '<span class="badge text-bg-warning">Revoke pending</span>';
            const editButton = `<button type="button" class="btn btn-sm btn-primary EditLpgInventory" data-id="${row.cashiers_report_lpg_inventory_id}" title="Edit"><i class="bi bi-pencil-square"></i></button>`;
            if (mode === 'BEGINNING') return editButton;
            return `${editButton} <button type="button" class="btn btn-sm btn-outline-danger RequestRevokeLpgInventory" data-id="${row.cashiers_report_lpg_inventory_id}" data-number="${row.transaction_no}" data-item="${item}" title="Request to revoke"><i class="bi bi-arrow-counterclockwise me-1"></i>Request Revoke</button>`;
        }
    };
}

function lpgInventoryProductLabel(row) {
    return [row.product_name, row.product_unit_measurement].filter(Boolean).join(' ') || '-';
}

function lpgInventoryQuantity(value) {
    return Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function inventoryEmptyMessage(mode) {
    if (mode === 'BEGINNING') return 'No beginning inventory recorded.';
    if (mode === 'BO_REPLACEMENT') return 'No B.O. replacements recorded.';
    return 'No LPG deliveries recorded.';
}

function openLpgInventoryModal(mode, record, productId, productScope) {
    if (mode === 'BEGINNING' && LpgStartingAuditLocked) {
        if (typeof showValidationErrorModal === 'function') showValidationErrorModal('This Starting Audit is already submitted or approved and cannot be edited.');
        return;
    }
    const form = document.getElementById('CashierReportLpgInventoryForm');
    const select = document.getElementById('lpg_inventory_product_idx');
    LpgCurrentInventoryProductScope = productScope || 'LPG';
    const scopeProducts = LpgInventoryProductScopes[LpgCurrentInventoryProductScope] || LpgInventoryProductScopes.LPG;
    LpgInventoryProductOptionOrder.forEach(option => select.appendChild(option));
    LpgInventoryProductOptionOrder.forEach(option => {
        if (option.value && !scopeProducts.has(Number(option.value))) option.remove();
    });
    form.reset();
    form.classList.remove('was-validated');
    clearLpgInventoryErrors();

    const requiresSupplier = ['DELIVERY', 'BO_REPLACEMENT', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'].includes(mode);
    const requiresClient = mode === 'BO_FROM_CUSTOMER';
    const modeLabels = {BEGINNING: 'Starting Audit', DELIVERY: 'Suppliers Delivery', BO_REPLACEMENT: 'B.O. Replacement', BO_FROM_CUSTOMER: 'B.O. From Customer/s', BO_DURING_DELIVERY: 'B.O. During Delivery', BO_RETURNED_TO_SUPPLIER: 'B.O. Returned to Supplier'};
    const modeLabel = modeLabels[mode] || 'Inventory';
    $('#cashiers_report_lpg_inventory_id').val(record?.cashiers_report_lpg_inventory_id || 0);
    $('#lpg_inventory_mode').val(mode);
    $('#lpg_inventory_product_idx').val(record?.product_idx || productId || '').trigger('change');
    $('#lpg_inventory_supplier_idx').val(record?.supplier_idx || '');
    $('#lpg_inventory_client_idx').val(record?.client_idx || '');
    $('#lpg_inventory_delivery_reference_no').val(record?.delivery_reference_no || '');
    $('#lpg_inventory_quantity').val(record?.quantity ?? '');
    $('#lpg_inventory_bo_returned_customer_quantity').val(record?.bo_returned_customer_quantity ?? '');
    $('#lpg_inventory_bo_during_delivery_quantity').val(record?.bo_during_delivery_quantity ?? '');
    $('#lpg_inventory_audit_date').val(record?.audit_date || $('#lpg_inventory_audit_date_display').val() || '');
    $('#lpg_inventory_audit_date_display').val(record?.audit_date || $('#lpg_inventory_audit_date').val() || '');
    $('#lpg_inventory_remarks').val(record?.remarks || '');
    $('.lpg-beginning-audit-field').toggleClass('d-none', mode !== 'BEGINNING');
    $('#CashierReportLpgInventoryForm label[for="lpg_inventory_quantity"]').html('<i class="bi bi-123 me-2 text-success" aria-hidden="true"></i>' + (mode === 'BEGINNING' ? 'Filled Cylinders' : 'Quantity'));
    $('.lpg-supplier-field').toggleClass('d-none', !requiresSupplier);
    $('.lpg-reference-field').toggleClass('d-none', !(requiresSupplier || requiresClient));
    $('.lpg-client-field').toggleClass('d-none', !requiresClient);
    $('#lpg_inventory_supplier_idx').prop('required', requiresSupplier);
    $('#lpg_inventory_client_idx').prop('required', requiresClient);
    $('#lpg_inventory_bo_returned_customer_quantity, #lpg_inventory_bo_during_delivery_quantity').prop('required', mode === 'BEGINNING');
    $('#CashierReportLpgInventoryModalLabel').text(`${record ? 'Edit' : 'Add'} ${modeLabel}`);
    const descriptions = {BEGINNING: 'Count Filled Cylinders and both B.O. balances. This audit sets Beginning Inventory from its date.', DELIVERY: 'Record usable filled stock received from a supplier.', BO_REPLACEMENT: 'Add the replacement cylinder to Filled stock and reduce the B.O. balance.', BO_FROM_CUSTOMER: 'Record B.O. stock returned by a customer.', BO_DURING_DELIVERY: 'Record B.O. stock identified during supplier delivery inspection.', BO_RETURNED_TO_SUPPLIER: 'Record B.O. stock returned to the supplier.'};
    $('#CashierReportLpgInventoryModalDescription').text(descriptions[mode] || 'Record LPG inventory movement.');
    $('#save-lpg-inventory').html(`<i class="bi bi-save-fill me-2"></i>${record ? 'Update' : 'Save'} ${modeLabel}`);
    showLpgInventoryModal('#CashierReportLpgInventoryModal');
}

function loadLpgStartingAuditStatus() {
    const panel = $('#lpg-starting-audit-status');
    if (!panel.length) return;
    $.post("{{ route('lpg.starting-audits.status') }}", { report_id: panel.data('report-id') })
        .done(function (response) {
            const audit = response.audit;
            LpgStartingAuditStatus = audit?.status || 'NOT_STARTED';
            LpgStartingAuditLocked = ['PENDING', 'APPROVED'].includes(LpgStartingAuditStatus);
            $(document).trigger('lpg:starting-audit-status-changed', [LpgStartingAuditStatus]);
            const labels = {NOT_STARTED: 'No starting audit has been encoded yet.', DRAFT: 'Draft — only this report can use these counts until submitted and approved.', PENDING: 'Pending approval — future reports will continue using the latest approved audit.', APPROVED: 'Approved — this audit carries forward from ' + (audit?.audit_date || ''), RETURNED: 'Returned for correction — edit the counts and submit again.'};
            panel.find('.lpg-audit-status-label').text(labels[LpgStartingAuditStatus] || LpgStartingAuditStatus);
            panel.removeClass('alert-info alert-warning alert-success').addClass(LpgStartingAuditStatus === 'APPROVED' ? 'alert-success' : (LpgStartingAuditStatus === 'RETURNED' ? 'alert-warning' : 'alert-info'));
            $('.lpg-inventory-add[data-inventory-mode="BEGINNING"]').prop('disabled', LpgStartingAuditLocked).attr('title', LpgStartingAuditLocked ? 'Submitted audits are read-only until returned for correction.' : '');
            $('#lpg-submit-starting-audit').toggleClass('d-none', !audit || !['DRAFT', 'RETURNED'].includes(LpgStartingAuditStatus)).prop('disabled', false).html('<i class="bi bi-send me-1"></i>' + (LpgStartingAuditStatus === 'RETURNED' ? 'Resubmit for Approval' : 'Submit for Approval'));
            $('#lpg-review-starting-audit').toggleClass('d-none', !audit || !['PENDING', 'APPROVED'].includes(LpgStartingAuditStatus)).attr('href', audit?.audit_id ? '/lpg-starting-audits/' + audit.audit_id : '#');
            const reason = audit?.return_reason;
            $('#lpg-starting-audit-return-reason').toggleClass('d-none', !(LpgStartingAuditStatus === 'RETURNED' && reason)).text(reason ? 'Returned for correction: ' + reason : '');
            if (LpgBeginningInventoryTable) LpgBeginningInventoryTable.ajax.reload(null, false);
        })
        .fail(function () { panel.find('.lpg-audit-status-label').text('Unable to load audit approval status.'); });
}

function reloadLpgInventoryTables() {
    if (LpgBeginningInventoryTable) LpgBeginningInventoryTable.ajax.reload(null, false);
    if (LpgDeliveryTable) LpgDeliveryTable.ajax.reload(null, false);
    if (LpgBoReplacementTable) LpgBoReplacementTable.ajax.reload(null, false);
    $(document).trigger('lpg:inventory-changed');
}

function showLpgInventoryModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(element).show();
}

function hideLpgInventoryModal(selector) {
    const element = document.querySelector(selector);
    if (element && window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(element).hide();
}

function clearLpgInventoryErrors() {
    $('#CashierReportLpgInventoryForm .is-invalid').removeClass('is-invalid');
    $('#CashierReportLpgInventoryForm .invalid-feedback').text('');
}

function showLpgInventoryErrors(errors) {
    Object.keys(errors).forEach(function (field) {
        const input = $('[name="' + field + '"]');
        input.addClass('is-invalid');
        $('#' + field + 'Error').text(Array.isArray(errors[field]) ? errors[field][0] : errors[field]);
    });
}
</script>
