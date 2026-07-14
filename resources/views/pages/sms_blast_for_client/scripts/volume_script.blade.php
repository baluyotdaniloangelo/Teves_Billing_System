
<script type="text/javascript">

let LoadPurchaseOrderProductVolumeSummaryData = null;

/*LOAD REPORT*/
$("#generate_report_volume").click(function(event){

    event.preventDefault();

    /*RESET ERRORS*/
    $('#start_dateError_volume').text('');
    $('#end_dateError_volume').text('');

    let start_date     = $("#start_date_volume").val();
    let end_date       = $("#end_date_volume").val();
    let company_header = $("#company_header_volume").val();
	let supplier_idx 		= $('#supplier_name option[value="' + $('#supplier_idx_volume').val() + '"]').attr('data-id');
	
    $.ajax({

        url: "/generate_purchase_order_volume_summary_report",
        type: "POST",

        data: {
            start_date: start_date,
            end_date: end_date,
            company_header: company_header,
			supplier_idx: supplier_idx,
            _token: "{{ csrf_token() }}"
        },

        success: function(response){

            $('#CreateReportModal_volume').modal('toggle');

            console.log(response);

					var report_type = 'volume';
					
					if((company_header=='All') && (supplier_idx=='All')){
						$('#branch_product_report').hide();
						$('#supplier_product_report').hide();
					}
					
					if(company_header!='All'){
						
						$('#branch_product_report').show();
						get_branch_details(company_header,report_type);
						
					}else{
						
						$('#branch_product_report').hide();
						get_branch_details(1,report_type);
						
					}
					
					if(supplier_idx!='All'){
						
						/*Hide Aupplier Div*/
						$('#supplier_product_report').show();
						get_supplier_details(supplier_idx,report_type);
						
					}else{
						
						
						$('#supplier_product_report').hide();
						get_supplier_details(supplier_idx,report_type);
						
					}

            /*DESTROY OLD TABLE*/
            if ($.fn.DataTable.isDataTable('#POVolumeSummaryTable')) {

                $('#POVolumeSummaryTable')
                    .DataTable()
                    .destroy();

                $('#POVolumeSummaryTable thead').empty();
                $('#POVolumeSummaryTable tbody').empty();
            }

            /*NO RESULT*/
            if(response.data.length === 0){

                $("#POVolumeSummaryTable tbody")
                    .html("<tr><td class='text-center'>No Result Found</td></tr>");

                return;
            }

            /*=========================
              BUILD DYNAMIC COLUMNS
            =========================*/

            let columns = [];
            let headerRow = '<tr>';

            let firstRow = response.data[0];

		Object.keys(firstRow).forEach(function(key){

			/*SKIP INTERNAL COLUMN*/
			if(key === 'DT_RowIndex'){
				return;
			}

			let title = key
				.replaceAll('_', ' ')
				.replace(/\b\w/g, l => l.toUpperCase());

			headerRow += `<th>${title}</th>`;

			columns.push({
				data: key,
				title: title,
				className:
					key === 'product_name'
					? ''
					: 'text-end',

				render: function(data){

					if($.isNumeric(data)){

						return parseFloat(data)
							.toLocaleString(
								undefined,
								{
									minimumFractionDigits: 2,
									maximumFractionDigits: 2
								}
							);
					}

					return data;
				}
			});

		});

					headerRow += '</tr>';

					$('#POProductSummaryTable thead')
						.html(headerRow);

					/*=========================
					  LOAD DATATABLE
					=========================*/

					LoadPurchaseOrderProductVolumeSummaryData =
						$('#POVolumeSummaryTable').DataTable({

							responsive: false,
							paging: false,
							searching: false,
							info: false,

							scrollX: true,
							scrollY: '500px',
							scrollCollapse: true,

							data: response.data,
							columns: columns,

							language: {
								emptyTable: "No Result Found",
								infoEmpty: "No entries to show"
							}
						});

					/*DATE RANGE DISPLAY*/
					let start_date_new = new Date(start_date);
					let end_date_new = new Date(end_date);

					$('#date_range_info_volume').text(
						start_date_new.toLocaleDateString("en-PH")
						+ ' - ' +
						end_date_new.toLocaleDateString("en-PH")
					);

					/*DOWNLOAD BUTTON
					$("#download_options_volume").html(
						'<div class="btn-group">'+
							'<button type="button" '+
									'class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" '+
									'onclick="download_purchase_order_product_report_pdf()">'+
								' Product Volume Summary'+
							'</button>'+
						'</div>'
					);*/

				},

				beforeSend:function(){

					$("#generate_report_volume").prop('disabled', true);
					$('#loading_data_volume').show();

				},

				complete:function(){

					$("#generate_report_volume").prop('disabled', false);
					$('#loading_data_volume').hide();

				},

				error:function(error){

					console.log(error);

					if(error.responseJSON?.errors){

						$('#start_dateError_volume')
							.text(error.responseJSON.errors.start_date);

						$('#end_dateError_volume')
							.text(error.responseJSON.errors.end_date);
					}

					$('#InvalidModal').modal('toggle');

				}

			});

		});

</script>
