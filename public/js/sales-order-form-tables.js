(function (window, document, $) {
    'use strict';
    if (!$ || !$.fn.DataTable || window.SalesOrderFormTables) return;

    var selectors = {
        products: '#so-editor-products-table',
        deliveries: '#so-editor-deliveries-table',
        payments: '#so-editor-payments-table'
    };

    function tableFor(name) {
        var root = document.querySelector('.so-order-editor-content[data-sales-order-id]');
        return root ? root.querySelector(selectors[name]) : null;
    }

    window.SalesOrderFormTables = {
        beforeLoad: function (name) {
            var table = tableFor(name);
            if (table && $.fn.DataTable.isDataTable(table)) $(table).DataTable().destroy();
        },
        afterLoad: function (name) {
            var table = tableFor(name);
            if (!table || !table.isConnected) return;
            table.tBodies[0]?.querySelectorAll('tr[style*="display: none"]').forEach(function (row) { row.remove(); });
            if (table.tBodies[0] && table.tBodies[0].rows.length && !$.fn.DataTable.isDataTable(table)) {
                $(table).DataTable({
                    autoWidth: false,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                    order: [],
                    scrollX: true,
                    language: {
                        search: '',
                        searchPlaceholder: 'Search this list…',
                        emptyTable: 'No records to display',
                        lengthMenu: 'Show _MENU_',
                        info: 'Showing _START_ to _END_ of _TOTAL_',
                        infoEmpty: 'No records to display',
                        paginate: { previous: '‹', next: '›' }
                    }
                });
            }
            window.SalesOrderFormSummary?.schedule();
        }
    };
    $(document).on('shown.bs.tab', '#so-editor-myTab button', function () {
        var root = document.querySelector('.so-order-editor-content[data-sales-order-id]');
        if (!root) return;
        root.querySelectorAll('[data-so-table]').forEach(function (table) {
            if ($.fn.DataTable.isDataTable(table)) $(table).DataTable().columns.adjust();
        });
    });
})(window, document, window.jQuery);
