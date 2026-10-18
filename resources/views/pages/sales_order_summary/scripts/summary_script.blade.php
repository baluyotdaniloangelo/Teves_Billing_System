<script src="{{ asset('Datatables/2.0.8/js/dataTables.js') }}"></script>
<script>
$(function () {
    if (!document.getElementById('generate_report_form') || window.salesOrderSummaryReady) return;
    window.salesOrderSummaryReady = true;
    let report = null;
    let detailTable = null;
    const money = value => '₱' + Number(value || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const textRenderer = $.fn.dataTable.render.text();
    const moneyRenderer = (value, type) => type === 'display' ? money(value) : Number(value || 0);

    function showDetails() {
        if (!detailTable) {
            detailTable = $('#sale_sorder_summary_table').DataTable({
                data: report ? report.data : [], paging: true, pageLength: 25, searching: true,
                order: [], autoWidth: false,
                language: {emptyTable: report ? 'No sales orders match these filters.' : 'Generate a report to view sales orders.'},
                columns: [
                    {data: 'DT_RowIndex'}, {data: 'branch_code', render: textRenderer},
                    {data: 'sales_order_date', render: textRenderer},
                    {data: 'sales_order_control_number', render: textRenderer},
                    {data: 'client_name', render: textRenderer},
                    {data: 'sales_order_payment_term', render: textRenderer},
                    {data: 'sales_order_gross_amount', render: moneyRenderer, className: 'text-end'},
                    {data: 'report_withholding_amount', render: moneyRenderer, className: 'text-end'},
                    {data: 'sales_order_net_amount', render: moneyRenderer, className: 'text-end'},
                    {data: 'sales_order_total_due', render: moneyRenderer, className: 'text-end'},
                    {data: 'sales_order_delivery_status', render: textRenderer},
                    {data: 'sales_order_payment_status', render: textRenderer}
                ]
            });
        }
        detailTable.columns.adjust();
    }
    $('#order-details-tab').on('shown.bs.tab', showDetails);

    function renderReport() {
        const totals = report.totals;
        const body = $('#branch_summary_table tbody').empty();
        report.branch_summary.forEach(branch => {
            const row = $('<tr>');
            $('<td>').text(branch.branch_code).appendTo(row);
            $('<td>').text(branch.branch_name).appendTo(row);
            $('<td>').addClass('text-end').text(branch.order_count).appendTo(row);
            ['gross_amount', 'withholding_tax', 'net_amount', 'total_due'].forEach(key => $('<td>').addClass('text-end').text(money(branch[key])).appendTo(row));
            body.append(row);
        });
        if (!report.branch_summary.length) body.append($('<tr>').append($('<td>').attr('colspan', 7).addClass('text-center py-4').text('No branches are available to your account.')));
        ['gross_amount', 'withholding_tax', 'net_amount', 'total_due'].forEach(key => $('#stat_' + key).text(money(totals[key])));
        $('#branch_total_count').text(totals.order_count);
        $('#branch_total_gross, #total_gross_amount').text(money(totals.gross_amount));
        $('#branch_total_tax, #total_withholding_tax').text(money(totals.withholding_tax));
        $('#branch_total_net, #total_net_amount').text(money(totals.net_amount));
        $('#branch_total_due, #total_amount_due').text(money(totals.total_due));
        $('#summary_scope').text(report.branch_label + ' · ' + (report.client ? report.client.client_name : 'All Clients'));
        $('#summary_period').text(report.filters.start_date + ' to ' + report.filters.end_date);
        $('#summary_count').text(report.available_branch_count + ' available branch(es) · ' + totals.order_count + ' sales order(s). Totals include every matching order, regardless of table search or page.');
        if (detailTable) detailTable.clear().rows.add(report.data).draw();
        if ($('#order-details-pane').hasClass('active')) showDetails();
        const allBranches = report.filters.company_header === 'All';
        $('#summary_download').prop('disabled', report.available_branch_count === 0);
        $('#summary_export_note').toggleClass('d-none', !allBranches);
    }

    $('#start_date').on('change', function () {
        $('#end_date').attr('min', this.value);
        if ($('#end_date').val() < this.value) $('#end_date').val(this.value);
    }).trigger('change');

    $('#generate_report_form').on('submit', function (event) {
        event.preventDefault();
        const clientName = $('#client_id').val().trim();
        const client = Array.from(document.querySelectorAll('#client_name option')).find(option => option.value === clientName);
        $('#summary_filter_error, #summary_error').addClass('d-none').text('');
        if (!client) {
            $('#summary_filter_error').removeClass('d-none').text('Choose a valid client from the list, or enter All.');
            return;
        }
        const filters = {
            company_header: $('#company_header').val(), client_idx: client.dataset.id,
            start_date: $('#start_date').val(), end_date: $('#end_date').val()
        };
        $('#generate_report').prop('disabled', true);
        $('#loading_data').removeClass('d-none');
        $.ajax({
            url: "{{ route('sales_order_summary_data') }}", type: 'POST',
            data: Object.assign({_token: "{{ csrf_token() }}"}, filters),
            success: function (response) {
                report = response;
                renderReport();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('CreateReportModal')).hide();
            },
            error: function (xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                const message = errors ? Object.values(errors).flat().join(' ') : 'Unable to generate the report. Please try again.';
                $('#summary_filter_error').removeClass('d-none').text(message);
                if (!$('#CreateReportModal').hasClass('show')) $('#summary_error').removeClass('d-none').text(message);
            },
            complete: function () {
                $('#generate_report').prop('disabled', false);
                $('#loading_data').addClass('d-none');
            }
        });
    });

    $('#summary_download').on('click', function () {
        if (!report || !report.available_branch_count) return;
        if (report.filters.company_header === 'All') {
            $('#summary_export_scope').text($('#summary_scope').text() + ' · ' + $('#summary_period').text());
            $('#summary_export_content').val($('#order-details-pane').hasClass('active') ? 'details' : 'summary');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('SummaryExportModal')).show();
            return;
        }
        const exportRoute = report.filters.client_idx === 'All'
            ? "{{ route('generate_sales_order_summary_report_pdf') }}"
            : "{{ route('generate_sales_order_summary_report_per_client_pdf') }}";
        const url = exportRoute + '?' + $.param(report.filters);
        window.open(url, '_blank', 'noopener');
    });

    $('#summary_export_layout').on('change', function () {
        const separate = this.value === 'per_branch';
        $('#summary_export_content_group').toggleClass('d-none', separate);
        $('#summary_export_description').text(separate
            ? 'One PDF of sales order details. Each branch starts on a new page.'
            : 'One PDF with totals for every selected branch.');
    });
    $('#summary_export_form').on('submit', function (event) {
        event.preventDefault();
        if (!report || !report.available_branch_count) return;
        const query = Object.assign({}, report.filters, {
            layout: $('#summary_export_layout').val(), report_mode: $('#summary_export_content').val()
        });
        window.open("{{ route('generate_sales_order_summary_export_pdf') }}" + '?' + $.param(query), '_blank', 'noopener');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('SummaryExportModal')).hide();
    });
});
</script>
