
<script type="text/javascript">

	  
/*========================================
PURCHASE ORDER SUMMARY REPORT
========================================*/

$("#PurchaseOrderSummaryGenerate").on("click", function (event) {

    event.preventDefault();

    /*========================
    RESET VALIDATION
    ========================*/
    resetSummaryValidation();

    /*========================
    RESET TABLE
    ========================*/
    $("#PurchaseOrderSummaryTable tbody").empty();

    /*========================
    FORM VALIDATION STYLE
    ========================*/
    $("#PurchaseOrderSummaryForm")
        .addClass("was-validated");

    /*========================
    GET FORM VALUES
    ========================*/
    const start_date = $("input[name=start_date_summary]").val();

    const end_date = $("input[name=end_date_summary]").val();

    const company_header =
        $("#company_header").val();
		
	let supplier_idx =
    $('#supplier_name option[value="' +
        $('#supplier_idx').val() +
    '"]').attr('data-id') || 'All';

    const report_type = 'summary';

    /*========================
    AJAX REQUEST
    ========================*/
    $.ajax({

        url: "/purchase_order_summary_data",

        type: "POST",

        data: {
            start_date,
            end_date,
            company_header,
            supplier_idx,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function () {

            $("#PurchaseOrderSummaryGenerate")
                .prop("disabled", true);

            $("#PurchaseOrderSummaryLoading")
                .show();
        },

        complete: function () {

            $("#PurchaseOrderSummaryGenerate")
                .prop("disabled", false);

            $("#PurchaseOrderSummaryLoading")
                .hide();
        },

        success: function (response) {

            $('#PurchaseOrderSummaryModal')
                .modal('hide');

            const data = response.data || [];

			/*========================
			SUPPLIER COLUMN VISIBILITY
			=========================*/

			let supplierColumn =
				LoadPurchaseOrderSummaryData.column(3);

			if(supplier_idx !== 'All'){

				supplierColumn.visible(false);

			}else{

				supplierColumn.visible(true);
			}

            /*========================
            HANDLE REPORT INFO
            ========================*/
            handleBranchInfo(
                company_header,
                report_type
            );

            handleSupplierInfo(
                supplier_idx,
                report_type
            );

            /*========================
            NO RESULT
            ========================*/
            if (data.length === 0) {

                clearSummaryTotals();

                $("#download_options_summary")
                    .empty();

                $("#PurchaseOrderSummaryTable tbody")
                    .html(`
                        <tr>
                            <td colspan="12"
                                class="text-center">
                                No Result Found
                            </td>
                        </tr>
                    `);

                return;
            }

            /*========================
            LOAD DATATABLE
            ========================*/
            LoadPurchaseOrderSummaryData
                .clear()
                .rows
                .add(data)
                .draw();

            /*========================
            COMPUTE TOTALS
            ========================*/
            const totals = data.reduce((acc, row) => {

                const gross =
                    Number(row.purchase_order_gross_amount) || 0;

                const net =
                    Number(row.purchase_order_net_amount) || 0;

                const less =
                    Number(row.purchase_order_less_percentage) || 0;

                const payable =
                    Number(row.purchase_order_total_payable) || 0;

                acc.gross += gross;

                acc.net += net;

                acc.withholding +=
                    net * (less / 100);

                acc.payable += payable;

                return acc;

            }, {
                gross: 0,
                net: 0,
                withholding: 0,
                payable: 0
            });

            /*========================
            DISPLAY TOTALS
            ========================*/
            $("#total_gross_amount")
                .text(formatAmount(totals.gross));

            $("#total_withholding_tax")
                .text(formatAmount(totals.withholding));

            $("#total_net_amount")
                .text(formatAmount(totals.net));

            $("#total_amount_due")
                .text(formatAmount(totals.payable));

            /*========================
            DATE RANGE
            ========================*/
  
			let start_date_new = new Date(start_date);
			let end_date_new = new Date(end_date);

			$('#date_range_info_summary').text(
				start_date_new.toLocaleDateString("en-PH")
					+ ' - ' +
				end_date_new.toLocaleDateString("en-PH")
			);
					
            /*========================
            DOWNLOAD BUTTONS
            ========================*/
			$("#download_options_summary").html(`

				<div class="dropdown">

					<button class="btn btn-primary rounded-3 shadow-sm dropdown-toggle"
							type="button"
							data-bs-toggle="dropdown"
							aria-expanded="false">

						<i class="bi bi-download me-1"></i>
						Download Report

					</button>

					<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">

						<!-- SUMMARY PDF -->
						<li>

							<button class="dropdown-item"
									type="button"
									onclick="download_daily_purchase_report_pdf()">

								<i class="bi bi-file-earmark-pdf text-danger me-2"></i>
								Summary PDF

							</button>

						</li>

						<!-- CONSOLIDATED PDF -->
						<li>

							<button class="dropdown-item"
									type="button"
									onclick="download_daily_purchase_report_pdf_consolidated()">

								<i class="bi bi-file-earmark-richtext text-primary me-2"></i>
								Consolidated PDF

							</button>

						</li>

					</ul>

				</div>

			`);

        },

        error: function (error) {

            console.log(error);

            const errors =
                error.responseJSON?.errors || {};

            $("#start_dateError")
                .text(errors.start_date || '');

            $("#end_dateError")
                .text(errors.end_date || '');

            $("#InvalidModal")
                .modal('show');
        }

    });

});


/*========================================
DATATABLE
========================================*/

let LoadPurchaseOrderSummaryData =
    $('#PurchaseOrderSummaryTable').DataTable({

        language: {
            emptyTable: "No Result Found",
            infoEmpty: "No entries to show"
        },

        responsive: true,

        paging: true,

        searching: true,

        info: false,

        data: [],

        scrollCollapse: true,

        scrollY: '500px',

        scrollX: true,

        columns: [

            {
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'purchase_order_date'
            },

            {
                data: 'purchase_order_control_number'
            },

            {
                data: 'supplier_name'
            },

            {
                data: 'purchase_order_sales_order_number'
            },

            {
                data: 'purchase_order_official_receipt_no'
            },

            {
                data: 'purchase_order_gross_amount',
                render: renderAmount
            },

            {
                data: 'purchase_order_net_amount',
                render: renderAmount
            },

            {
                data: function (row) {

                    return (
                        Number(row.purchase_order_net_amount || 0)
                        *
                        (
                            Number(row.purchase_order_less_percentage || 0)
                            / 100
                        )
                    );
                },

                render: renderAmount
            },

            {
                data: 'purchase_order_total_payable',
                render: renderAmount
            },

            {
                data: 'purchase_order_delivery_status',
                className: "text-center"
            },

            {
                data: 'purchase_status',
                className: "text-center"
            }

        ]

    });


/*========================================
HELPERS
========================================*/

function renderAmount(data) {

    return $.fn.dataTable.render.number(
        ',',
        '.',
        2,
        ''
    ).display(data);
}

function formatAmount(value) {

    return Number(value).toLocaleString(
        "en-PH",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}

function formatDate(date) {

    return new Date(date)
        .toLocaleDateString("en-PH");
}

function resetSummaryValidation() {

    $('#supplier_idxError').text('');
    $('#start_dateError').text('');
    $('#end_dateError').text('');
}

function clearSummaryTotals() {

    $('#total_gross_amount').text('0.00');
    $('#total_withholding_tax').text('0.00');
    $('#total_net_amount').text('0.00');
    $('#total_amount_due').text('0.00');
}

function handleBranchInfo(company_header, report_type) {

    if (company_header !== 'All') {

        $('#branch_summary_report').show();

        get_branch_details(
            company_header,
            report_type
        );

    } else {

        $('#branch_summary_report').hide();

        get_branch_details(
            1,
            report_type
        );
    }
}

function handleSupplierInfo(supplier_idx, report_type) {

    if (supplier_idx !== 'All') {

        $('#supplier_summary_report').show();

    } else {

        $('#supplier_summary_report').hide();
    }

    get_supplier_details(
        supplier_idx,
        report_type
    );
}
		
	autoAdjustColumns(LoadPurchaseOrderSummaryData);

	function download_daily_purchase_report_pdf(){
			
    const start_date = $("input[name=start_date_summary]").val();

    const end_date = $("input[name=end_date_summary]").val();

    const company_header =
        $("#company_header").val();
		
	let supplier_idx =
    $('#supplier_name option[value="' +
        $('#supplier_idx').val() +
    '"]').attr('data-id') || 'All';
			
		var query = {
			start_date:start_date,
			end_date:end_date,
			company_header:company_header,
			supplier_idx:supplier_idx,
			_token: "{{ csrf_token() }}"
		}

		if(supplier_idx!='All'){
			var url = "{{URL::to('generate_purchase_order_summary_report_per_client_pdf')}}?" + $.param(query)
		}else{
			var url = "{{URL::to('generate_purchase_order_summary_report_pdf')}}?" + $.param(query)
		}
		
		window.open(url);
	  
	}

	function download_daily_purchase_report_pdf_consolidated(){
			
			let start_date 			= $("input[name=start_date_summary]").val();
			let end_date 			= $("input[name=end_date_summary]").val();
			
			const company_header =
				$("#company_header").val();
				
			let supplier_idx =
			$('#supplier_name option[value="' +
				$('#supplier_idx').val() +
			'"]').attr('data-id') || 'All';
			
		var query = {
			start_date:start_date,
			end_date:end_date,
			company_header:company_header,
			supplier_idx:supplier_idx,
			_token: "{{ csrf_token() }}"
		}

		if(supplier_idx!='All'){
			var url = "{{URL::to('generate_purchase_order_summary_report_per_client_consolidated_pdf')}}?" + $.param(query)
		}else{
			var url = "{{URL::to('generate_purchase_order_summary_report_consolidated_pdf')}}?" + $.param(query)
		}
		
		window.open(url);
	  
	}

</script>
