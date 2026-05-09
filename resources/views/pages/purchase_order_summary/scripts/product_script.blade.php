
<script type="text/javascript">
	/*========================================
	PURCHASE ORDER PRODUCT SUMMARY
	========================================*/

	$("#generate_report_product").on("click", function (event) {

		event.preventDefault();

		/*========================
		RESET VALIDATION
		========================*/
		resetProductValidation();

		/*========================
		RESET TABLE
		========================*/
		$("#POProductSummaryTable tbody").empty();

		/*========================
		FORM VALIDATION STYLE
		========================*/
		$("#PurchaseOrderProductForm")
			.addClass("was-validated");

		/*========================
		GET FORM VALUES
		========================*/
		const start_date =
			$("input[name=start_date_product]").val();

		const end_date =
			$("input[name=end_date_product]").val();

		const company_header =
			$("#company_header_product").val();

		const supplier_idx =
			$('#supplier_name_product_list option[value="' +
				$('#supplier_idx_product').val() +
			'"]').attr('data-id') || 'All';

		const product_idx =
			$('#product_list_product option[value="' +
				$('#product_idx_product').val() +
			'"]').attr('data-id') || 'All';

		const report_type = 'product';

		/*========================
		AJAX REQUEST
		========================*/
		$.ajax({

			url: "/purchase_order_product_summary_data",

			type: "POST",

			data: {
				start_date,
				end_date,
				company_header,
				supplier_idx,
				product_idx,
				_token: "{{ csrf_token() }}"
			},

			beforeSend: function () {

				$("#generate_report_product")
					.prop("disabled", true);

				$("#loading_data_product")
					.show();
			},

			complete: function () {

				$("#generate_report_product")
					.prop("disabled", false);

				$("#loading_data_product")
					.hide();
			},

			success: function (response) {

				$('#PurchaseOrderProductModal')
					.modal('hide');

				const data = response.data || [];

				/*========================
				REPORT INFO
				========================*/
				handleProductBranchInfo(
					company_header,
					report_type
				);

				handleProductSupplierInfo(
					supplier_idx,
					report_type
				);

				/*========================
				SUPPLIER COLUMN VISIBILITY
				========================*/
				let supplierColumn =
					LoadPurchaseOrderProductSummaryData.column(3);

				if (supplier_idx !== 'All') {

					supplierColumn.visible(false);

				} else {

					supplierColumn.visible(true);
				}

				/*========================
				NO RESULT
				========================*/
				if (data.length === 0) {

					$("#download_options_product")
						.empty();

					$("#POProductSummaryTable tbody")
						.html(`
							<tr>
								<td colspan="8"
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
				LoadPurchaseOrderProductSummaryData
					.clear()
					.rows
					.add(data)
					.draw();

				/*========================
				DATE RANGE
				========================*/
				$("#date_range_info_product")
					.text(
						formatDate(start_date)
						+ ' - ' +
						formatDate(end_date)
					);

				/*========================
				DOWNLOAD BUTTON
				========================*/
				$("#download_options_product").html(`
					<div class="btn-group">

						<button type="button"
								class="btn btn-outline-primary btn-sm bi-file-earmark-pdf"
								onclick="download_purchase_order_product_report_pdf()">

							Product Summary

						</button>

					</div>
				`);

			},

			error: function (error) {

				console.log(error);

				const errors =
					error.responseJSON?.errors || {};

				$("#start_dateError_product")
					.text(errors.start_date || '');

				$("#end_dateError_product")
					.text(errors.end_date || '');

				$("#InvalidModal")
					.modal('show');
			}

		});

	});


	/*========================================
	DATATABLE
	========================================*/

	let LoadPurchaseOrderProductSummaryData =
		$('#POProductSummaryTable').DataTable({

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
					data: 'purchase_order_control_number',
					orderable: false
				},

				{
					data: 'supplier_name',
					orderable: false
				},

				{
					data: 'product_name',
					orderable: false
				},

				{
					data: 'product_price',
					render: renderAmount,
					orderable: false
				},

				{
					data: 'quantity_measurement',
					orderable: false,
					searchable: true,
					className: 'text-end'
				},

				{
					data: 'order_total_amount',
					render: renderAmount,
					orderable: false
				}

			]

		});


	/*========================================
	HELPERS
	========================================*/

	function resetProductValidation() {

		$('#supplier_idxError_product').text('');
		$('#start_dateError_product').text('');
		$('#end_dateError_product').text('');
	}

	function handleProductBranchInfo(
		company_header,
		report_type
	) {

		if (company_header !== 'All') {

			$('#branch_product_report').show();

			get_branch_details(
				company_header,
				report_type
			);

		} else {

			$('#branch_product_report').hide();

			get_branch_details(
				1,
				report_type
			);
		}
	}

	function handleProductSupplierInfo(
		supplier_idx,
		report_type
	) {

		if (supplier_idx !== 'All') {

			$('#supplier_product_report').show();

		} else {

			$('#supplier_product_report').hide();
		}

		get_supplier_details(
			supplier_idx,
			report_type
		);
	}

	autoAdjustColumns(LoadPurchaseOrderProductSummaryData);
	
	function download_purchase_order_product_report_pdf(){
			
		const start_date =
			$("input[name=start_date_product]").val();

		const end_date =
			$("input[name=end_date_product]").val();
		
		const company_header =
			$("#company_header_product").val();

		const supplier_idx =
			$('#supplier_name_product_list option[value="' +
				$('#supplier_idx_product').val() +
			'"]').attr('data-id') || 'All';

		const product_idx =
			$('#product_list_product option[value="' +
				$('#product_idx_product').val() +
			'"]').attr('data-id') || 'All';
			
		var query = {
			start_date:start_date,
			end_date:end_date,
			company_header:company_header,
			supplier_idx:supplier_idx,
			product_idx:product_idx,
			_token: "{{ csrf_token() }}"
		}

			var url = "{{URL::to('generate_purchase_order_product_summary_report_pdf')}}?" + $.param(query)

		window.open(url);
	  
	}
			
	LoadSellingPriceList();
	function LoadSellingPriceList() {	
		
		$("#product_list_product span").remove();
		$('<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></span>').appendTo('#product_list_product');

		let branch_idx 				= $("#company_header_product").val();
		let client_idx 				= $('#supplier_name_product_list option[value="' + $('#supplier_idx_product').val() + '"]').attr('data-id');
			
			$.ajax({
				url: "/get_product_list_selling_price",
				type:"POST",
				data:{
					client_idx:client_idx,
					branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				 
				  if(response['clients_price_list']!='') {			  
				  
						var len = response['clients_price_list'].length;

						
							for(var i=0; i<len; i++){
								
								var product_idx = response['clients_price_list'][i].product_idx;
								var product_name = response['clients_price_list'][i].product_name;
								var product_unit_measurement = response['clients_price_list'][i].product_unit_measurement;
								var product_price = response['clients_price_list'][i].product_price;
								
								$('#product_list_product span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
								"<option label='&#8369; "+product_price +" | "+product_name +"' data-price='"+product_price+"' data-id='"+product_idx+"' value='"+product_name+"'>" +
								"</span>");
								
							}							
					
						
				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	} 


</script>
