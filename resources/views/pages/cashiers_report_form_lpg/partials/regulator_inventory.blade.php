<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h6 class="fw-bold mb-1">New Regulators</h6>
            <small class="text-muted">Use the + buttons to add a source record for that regulator. Set Sold is automatic and informational; Swap shows received minus issued.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-lpg-regulators"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Refresh</button>
    </div>
    <div class="alert alert-danger d-none" id="lpg-regulator-error" role="alert"></div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="LpgRegulatorInventoryTable" style="width:100%;min-width:1750px;">
            <thead class="text-center">
                <tr>
                    <th>Regulators</th>
                    <th>Beginning</th>
                    <th>Set Sold</th>
                    <th>Delivered</th>
                    <th>Swap</th>
                    <th>On-Loan</th>
                    <th>B.O. Returned by Customer</th>
                    <th>B.O. During Delivery</th>
                    <th>Returned to Supplier</th>
                    <th>B.O. Replacement from Supplier</th>
                    <th>Total Sold</th>
                    <th>Unit Price</th>
                    <th>Total Sales in Peso</th>
                    <th>Theoretical Ending Inventory</th>
                    <th>Actual Ending Inventory</th>
                    <th>Variance</th>
                </tr>
            </thead>
            <tbody><tr><td colspan="16" class="text-center text-muted py-3">Loading regulator totals...</td></tr></tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const $ = window.jQuery;
    if (!$) return;
    const reportId = {{ (int) ($CashiersReportId ?? ($cashiers_report_id ?? 0)) }};
    const headers = {'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json'};
    const qty = value => Number(value || 0).toLocaleString('en-PH', {maximumFractionDigits: 2});
    const money = value => Number(value || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const escape = value => $('<div>').text(value == null ? '' : String(value)).html();
    const regulatorIds = new Set(@json(\App\Services\LpgRegulatorProducts::ids()));
    let request = null, version = 0, beginningAuditLocked = true, auditStatusResolved = false;

    function actionButton(productId, mode, title) {
        return '<button type="button" class="btn btn-sm btn-light lpg-regulator-add py-0 px-1 ms-1" data-product-id="' + Number(productId) + '" data-mode="' + mode + '" title="' + title + '" aria-label="' + title + '"><i class="bi bi-plus" aria-hidden="true"></i></button>';
    }
    function sourceCell(row, mode, value) {
        return '<td class="text-end text-nowrap">' + qty(value) + ' ' + actionButton(row.product_idx, mode, 'Add ' + mode.replaceAll('_', ' ').toLowerCase()) + '</td>';
    }
    function withRegulatorOptions(modalSelector, selectors, callback) {
        const optionLists = selectors.map(selector => ({selector, options: $(selector + ' option').toArray()}));
        optionLists.forEach(list => list.options.forEach(option => {
            const id = Number(option.getAttribute('data-id') || option.value || 0);
            if (id && !regulatorIds.has(id)) $(option).detach();
        }));
        $(modalSelector).one('hidden.bs.modal', () => optionLists.forEach(list => list.options.forEach(option => $(list.selector).append(option))));
        callback();
    }
    function addRegulatorSale(productId) {
        const modalSelector = '#CashierReportLpgArSoModal';
        const productInput = $('#ar_so_product');
        const previousCategoryFilter = productInput.attr('data-product-category-filter');
        productInput.removeAttr('data-product-category-filter');
        $(modalSelector).one('hidden.bs.modal', () => {
            if (previousCategoryFilter !== undefined) productInput.attr('data-product-category-filter', previousCategoryFilter);
        });
        withRegulatorOptions(modalSelector, ['#lpgArSoProductList'], () => {
            const option = $('#lpgArSoProductList option').filter((_, element) => Number($(element).attr('data-id')) === productId).first();
            if (!option.length) return;
            resetLpgArSoForm();
            $('#ar_so_type').val('Sales Order').trigger('change');
            $('#ar_so_product').val(option.val()).trigger('change');
            lpgArSoShowModal(modalSelector);
        });
    }
    function addMovement(mode, productId) {
        if (['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'BO_FROM_CUSTOMER', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'].includes(mode)) {
            openLpgInventoryModal(mode, null, productId, 'REGULATOR');
        } else if (mode === 'SWAP_IN' || mode === 'SWAP_OUT') {
            const field = mode === 'SWAP_IN' ? 'received_product_idx' : 'replaced_product_idx';
            withRegulatorOptions('#CashierReportLpgSwappingModal', ['#' + field], () => {
                window.LpgSwappingEditor.add(productId, mode === 'SWAP_IN' ? 'RECEIVED' : 'REPLACED');
                $('#' + field).val(productId).trigger('change');
            });
        } else if (mode === 'ON_LOAN') {
            withRegulatorOptions('#CashierReportLpgOnLoanModal', ['#borrow_product_idx', '#return_product_idx'], () => window.LpgOnLoanEditor.add(productId));
        } else if (mode === 'SALE') addRegulatorSale(productId);
    }
    function applyBeginningAuditLock() {
        const button = $('.lpg-regulator-add[data-mode="BEGINNING"]');
        const disabled = !auditStatusResolved || beginningAuditLocked;
        button.prop('disabled', disabled).attr('title', disabled
            ? 'Starting Audit is locked or its status is not confirmed. Refresh or return it for correction before editing.'
            : 'Add Starting Audit count');
    }
    function refreshBeginningAuditLock() {
        $.ajax({
            url: "{{ route('lpg.starting-audits.status') }}",
            type: 'POST',
            headers,
            data: {report_id: reportId}
        }).done(response => {
            beginningAuditLocked = ['PENDING', 'APPROVED'].includes(response.audit?.status);
            auditStatusResolved = true;
            applyBeginningAuditLock();
        }).fail(() => {
            auditStatusResolved = false;
            applyBeginningAuditLock();
        });
    }

    function reload() {
        const current = ++version;
        if (request) request.abort();
        $('#refresh-lpg-regulators').prop('disabled', true);
        $('#lpg-regulator-error').addClass('d-none');
        request = $.ajax({url: "{{ route('GetLpgRegulatorSummary') }}", type: 'POST', headers, data: {cashiers_report_id: reportId}})
            .done(response => {
                if (current !== version) return;
                const rows = response.data || [];
                $('#LpgRegulatorInventoryTable tbody').html(rows.length ? rows.map(row => '<tr>'
                    + '<td>' + escape(row.product_name || 'Unavailable product') + '</td>'
                    + sourceCell(row, 'BEGINNING', row.beginning_quantity)
                    + '<td class="text-end" title="Informational quantity from sold set components">' + qty(row.set_sold_quantity) + '</td>'
                    + sourceCell(row, 'DELIVERY', row.delivery_quantity)
                    + '<td class="text-end text-nowrap" title="Swap received minus swap issued">' + qty(row.swap_quantity) + ' ' + actionButton(row.product_idx, 'SWAP_IN', 'Add Received') + actionButton(row.product_idx, 'SWAP_OUT', 'Add Issued') + '</td>'
                    + '<td class="text-end text-nowrap">' + qty(row.on_loan_quantity) + ' ' + actionButton(row.product_idx, 'ON_LOAN', 'Add On-Loan') + '</td>'
                    + sourceCell(row, 'BO_FROM_CUSTOMER', row.bo_customer_quantity)
                    + sourceCell(row, 'BO_DURING_DELIVERY', row.bo_delivery_quantity)
                    + sourceCell(row, 'BO_RETURNED_TO_SUPPLIER', row.returned_supplier_quantity)
                    + sourceCell(row, 'BO_REPLACEMENT', row.bo_replacement_quantity)
                    + '<td class="text-end text-nowrap">' + qty(row.total_sold_quantity) + ' ' + actionButton(row.product_idx, 'SALE', 'Add Sale') + '</td>'
                    + '<td class="text-end">₱ ' + money(row.unit_price) + '</td>'
                    + '<td class="text-end">₱ ' + money(row.total_sales) + '</td>'
                    + '<td class="text-end fw-bold">' + qty(row.theoretical_ending_quantity) + '</td>'
                    + '<td class="text-end fw-bold">' + qty(row.actual_ending_quantity) + '</td>'
                    + '<td class="text-end">' + qty(row.variance_quantity) + '</td></tr>').join('')
                    : '<tr><td colspan="16" class="text-center text-muted py-3">No regulator products configured.</td></tr>');
                applyBeginningAuditLock();
            }).fail((xhr, status) => {
                if (current !== version || status === 'abort') return;
                $('#LpgRegulatorInventoryTable tbody').html('<tr><td colspan="16" class="text-center py-3">Regulator totals could not be loaded.</td></tr>');
                $('#lpg-regulator-error').text(xhr.responseJSON?.message || 'Unable to load regulator inventory.').removeClass('d-none');
            }).always(() => {
                if (current === version) { request = null; $('#refresh-lpg-regulators').prop('disabled', false); }
            });
    }

    $('#refresh-lpg-regulators').on('click', reload);
    $('#refresh-lpg-regulators').on('click', refreshBeginningAuditLock);
    $(document).on('lpg:starting-audit-status-changed', function (_, status) {
        beginningAuditLocked = ['PENDING', 'APPROVED'].includes(status);
        auditStatusResolved = true;
        applyBeginningAuditLock();
    });
    $(document).on('click', '.lpg-regulator-add', function () { addMovement(this.dataset.mode, Number(this.dataset.productId || 0)); });
    $(document).on('lpg:inventory-changed lpg:kitchen-movement-changed', function () {
        reload();
        refreshBeginningAuditLock();
    });
    $('#LpgSwappingTable, #LpgOnLoanTable, #LpgBeginningInventoryTable, #LpgDeliveryTable, #LpgBoReplacementTable').on('xhr.dt', reload);
    reload();
    refreshBeginningAuditLock();
});
</script>
