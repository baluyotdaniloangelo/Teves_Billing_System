   <!-- Page level plugins -->
   <script src="{{asset('Datatables/2.0.8/js/dataTables.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/responsive.dataTables.js')}}"></script>
   
   <script src="{{asset('template/assets/vendor/chart.js/chart.min.js')}}"></script>   
   
<script type="text/javascript">

	setMaxonEndDate();
	
	function setMaxonEndDate(){
	
		let start_date 			= $("input[name=start_date]").val();
		
		var myDate = new Date(start_date);
		var result1 = myDate.setMonth(myDate.getMonth()+12);
		
		const date_new = new Date(result1);
		
		const max_date = document.getElementById('end_date');
		
		document.getElementById("end_date").min = start_date;
		document.getElementById("end_date").max = date_new.toISOString("en-US").substring(0, 10);
		
		document.getElementById("end_date").value = start_date;
		
	}
	
	function CheckEndDateValidity(){
		
		let start_date 			= $("input[name=start_date]").val();
		let end_date 			= $("input[name=end_date]").val();
		
		let end_date_max 		= document.getElementById("end_date").max;
		
		const x = new Date(start_date);
		const y = new Date(end_date);
		
		const edt = new Date(end_date_max);
		
			if(x > y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date").value = start_date;
				
			}
			else if(edt < y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date").value = start_date;
					
			}else{
					$('#end_dateError').html('');
					document.getElementById('end_dateError').className = "valid-feedback";
			}
	
	}

	setMaxonEndDate_P();
	
	function setMaxonEndDate_P(){
	
		let start_date 			= $("input[name=start_date_P]").val();
		
		var myDate = new Date(start_date);
		var result1 = myDate.setMonth(myDate.getMonth()+12);
		
		const date_new = new Date(result1);
		
		const max_date = document.getElementById('end_date_P');
		
		document.getElementById("end_date_P").min = start_date;
		document.getElementById("end_date_P").max = date_new.toISOString("en-US").substring(0, 10);
		
		document.getElementById("end_date_P").value = start_date;
		
	}
	
	function CheckEndDateValidity_P(){
		
		let start_date 			= $("input[name=start_date_P]").val();
		let end_date 			= $("input[name=end_date_P]").val();
		
		let end_date_max 		= document.getElementById("end_date_P").max;
		
		const x = new Date(start_date);
		const y = new Date(end_date);
		
		const edt = new Date(end_date_max);
		
			if(x > y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date_P").value = start_date;
				
			}
			else if(edt < y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date_P").value = start_date;
					
			}else{
					$('#end_dateError_P').html('');
					document.getElementById('end_dateError_P').className = "valid-feedback";
			}
	
	}
	
	<!--Load Table-->
	$("#generate_report").click(function(event){
		
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#supplier_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#sale_sorder_summary_table tbody").html("");					
					
			document.getElementById('generate_report_form').className = "g-3 needs-validation was-validated";

			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();	
			let supplier_idx 		= $('#supplier_name option[value="' + $('#supplier_idx').val() + '"]').attr('data-id');
			
			/*Call Function to Get the Grand Total Ammount, PO Range*/  
			
			  $.ajax({
				url: "/purchase_order_summary_data",
				type:"POST",
				data:{
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,
				  supplier_idx:supplier_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Close Form*/
				$('#CreateReportModal').modal('toggle');
				
					if(company_header!='All'){
					
						get_branch_details(company_header);
						
					}else{
						
						get_branch_details(1);
						
					}
							
				  console.log(response);
				  if(response!='') {
					
					$('#supplier_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');	
				
						
						var total_gross_amount = 0;
						var total_withholding_tax = 0;
						var total_net_amount = 0;
						var total_amount_due  = 0;
						
						var len = response['data'].length;
						
						for(var i=0; i<len; i++){

				
							total_gross_amount 		+= response['data'][i].purchase_order_gross_amount;
							total_withholding_tax 	+= response['data'][i].purchase_order_net_amount*response['data'][i].purchase_order_less_percentage/100;
							total_net_amount 		+= response['data'][i].purchase_order_net_amount;
							total_amount_due 		+= response['data'][i].purchase_order_total_payable;

							
						}	
							
						LoadPurchaseOrderSummaryData.clear().draw();
						LoadPurchaseOrderSummaryData.rows.add(response.data).draw();	
							
							/**/
							$('#total_gross_amount').text(total_gross_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_withholding_tax').text(total_withholding_tax.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_net_amount').text(total_net_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_amount_due').text(total_amount_due.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#date_range_info').text(start_date_new_format + ' - ' +end_date_new_format);	
							
							$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_daily_purchase_report_pdf()"> Summary</button>'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_daily_purchase_report_pdf_consolidated()"> Consolidated</button>'+
							'</div>');
							
				  }else{
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							/*No Result Found*/
							$('#total_sales').text('');
							$('#total_short_over').text('');

							$('#branch_name_report').text('');
							$('#date_range_info').text('');
							
							$("#sale_sorder_summary_table tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
							$("#download_options").html(''); 
				
					}
				},
				beforeSend:function()
				{
					
					/*Disable Submit Button*/
					document.getElementById("generate_report").disabled = true;
					/*Show Status*/
					$('#loading_data').show();
					
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("generate_report").disabled = false;
					/*Hide Status*/
					$('#loading_data').hide();
					
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#start_dateError').text(error.responseJSON.errors.start_date);
				  document.getElementById('start_dateError').className = "invalid-feedback";		

				  $('#end_dateError').text(error.responseJSON.errors.end_date);
				  document.getElementById('end_dateError').className = "invalid-feedback";		
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });

	});  

	/*Load to Datatables*/	
	let LoadPurchaseOrderSummaryData = $('#sale_sorder_summary_table').DataTable({
				"language": {
						"emptyTable": "No Result Found",
						"infoEmpty": "No entries to show"
			    }, 
				responsive: true,
				paging: false,
				searching: false,
				info: false,
				data: [],
				scrollCollapse: true,
				scrollY: '500px',
				scrollx: true,
				"columns": [
				{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
				{data: 'purchase_order_date'},
				{data: 'purchase_order_control_number'},
				{data: 'supplier_name'},
				{data: 'purchase_order_sales_order_number'},
				{data: 'purchase_order_official_receipt_no'},
				{data: 'purchase_order_gross_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '' )},  
				{data: 'purchase_order_net_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
				{data: ({purchase_order_net_amount,purchase_order_less_percentage}) => (Number(purchase_order_net_amount)*Number(purchase_order_less_percentage/100)), render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },	
				{data: 'purchase_order_total_payable', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },		
				{data: 'purchase_order_delivery_status', className: "text-center",},  
				{data: 'purchase_status',  className: "text-center",}  
				],
				
		});
		
	autoAdjustColumns(LoadPurchaseOrderSummaryData);

		 /*Adjust Table Column*/
		 function autoAdjustColumns(table) {
			 var container = table.table().container();
			 var resizeObserver = new ResizeObserver(function () {
				 table.columns.adjust();
			 });
			 resizeObserver.observe(container);
		 }


	<!--Load Table-->
	$("#generate_report_P").click(function(event){
		
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#supplier_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#sale_order_product_summary_table tbody").html("");					
					
			document.getElementById('generate_report_form').className = "g-3 needs-validation was-validated";

			let start_date 			= $("input[name=start_date_P]").val();
			let end_date 			= $("input[name=end_date_P]").val();
			let company_header 		= $("#company_header_P").val();	
			let supplier_idx 		= $('#supplier_name_P option[value="' + $('#supplier_idx_P').val() + '"]').attr('data-id');
			let product_idx 		= $('#product_list option[value="' + $('#product_idx').val() + '"]').attr('data-id');
			
			/*Call Function to Get the Grand Total Ammount, PO Range*/  
			
			  $.ajax({
				url: "/purchase_order_product_summary_data",
				type:"POST",
				data:{
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,
				  supplier_idx:supplier_idx,
				  product_idx:product_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Close Form*/
				$('#CreateReportModal_P').modal('toggle');
				
					if(company_header!='All'){
					
						get_branch_details(company_header);
						
					}else{
						
						get_branch_details(1);
						
					}
							
				  console.log(response);
				  if(response!='') {
					
					$('#supplier_idxError_P').text('');
					$('#start_dateError_P').text('');
					$('#end_dateError_P').text('');	
				
						
						var total_gross_amount = 0;
						var total_withholding_tax = 0;
						var total_net_amount = 0;
						var total_amount_due  = 0;
						
						var len = response['data'].length;
						/*
						for(var i=0; i<len; i++){

				
							total_gross_amount 		+= response['data'][i].purchase_order_gross_amount;
							total_withholding_tax 	+= response['data'][i].purchase_order_net_amount*response['data'][i].purchase_order_less_percentage/100;
							total_net_amount 		+= response['data'][i].purchase_order_net_amount;
							total_amount_due 		+= response['data'][i].purchase_order_total_payable;

							
						}	
						*/	
						LoadPurchaseOrderProductSummaryData.clear().draw();
						LoadPurchaseOrderProductSummaryData.rows.add(response.data).draw();	
								
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#date_range_info_P').text(start_date_new_format + ' - ' +end_date_new_format);	
							
							$("#download_options_P").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_purchase_order_product_report_pdf()"> Product Summary</button>'+
							'</div>');
							
				  }else{
				  
							/*Close Form*/
							$('#CreateReportModal_P').modal('toggle');
							/*No Result Found*/
							
							$("#sale_order_product_summary_table tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
							$("#download_options_P").html(''); 
				
					}
				},
				beforeSend:function()
				{
					
					/*Disable Submit Button*/
					document.getElementById("generate_report_P").disabled = true;
					/*Show Status*/
					$('#loading_data').show();
					
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("generate_report_P").disabled = false;
					/*Hide Status*/
					$('#loading_data').hide();
					
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#start_dateError_P').text(error.responseJSON.errors.start_date);
				  document.getElementById('start_dateError').className = "invalid-feedback";		

				  $('#end_dateError_P').text(error.responseJSON.errors.end_date);
				  document.getElementById('end_dateError').className = "invalid-feedback";		
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });

	});  

		/*Load to Datatables*/	
		let LoadPurchaseOrderProductSummaryData = $('#sale_order_product_summary_table').DataTable({
				"language": {
						"emptyTable": "No Result Found",
						"infoEmpty": "No entries to show"
			    }, 
				responsive: true,
				paging: false,
				searching: false,
				info: false,
				data: [],
				scrollCollapse: true,
				scrollY: '500px',
				scrollx: true,
				"columns": [
				{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
				{data: 'purchase_order_date'},
				{data: 'purchase_order_control_number', orderable: false},
				{data: 'supplier_name', orderable: false},
				{data: 'product_name', orderable: false},
				{data: 'product_price', render: $.fn.dataTable.render.number( ',', '.', 2, '' ), orderable: false},  
				{data: 'quantity_measurement', name: 'quantity_measurement', orderable: false, searchable: true, className: 'text-right'},
				{data: 'order_total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '' ), orderable: false},  
				],
				
		});
		
	autoAdjustColumns(LoadPurchaseOrderProductSummaryData);

		 /*Adjust Table Column*/
		 function autoAdjustColumns(table) {
			 var container = table.table().container();
			 var resizeObserver = new ResizeObserver(function () {
				 table.columns.adjust();
			 });
			 resizeObserver.observe(container);
		 }


	function download_daily_purchase_report_pdf(){
			
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();
			let supplier_idx 		= $('#supplier_name option[value="' + $('#supplier_idx').val() + '"]').attr('data-id');
			
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
			
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();
			let supplier_idx 		= $('#supplier_name option[value="' + $('#supplier_idx').val() + '"]').attr('data-id');
			
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
	
	function download_purchase_order_product_report_pdf(){
			
			let start_date 			= $("input[name=start_date_P]").val();
			let end_date 			= $("input[name=end_date_P]").val();
			let company_header 		= $("#company_header_P").val();
			let supplier_idx 		= $('#supplier_name_P option[value="' + $('#supplier_idx_P').val() + '"]').attr('data-id');
			let product_idx 		= $('#product_list option[value="' + $('#product_idx').val() + '"]').attr('data-id');
			
		var query = {
			start_date:start_date,
			end_date:end_date,
			company_header:company_header,
			supplier_idx:supplier_idx,
			product_idx:product_idx,
			_token: "{{ csrf_token() }}"
		}

		//if(supplier_idx!='All'){
			var url = "{{URL::to('generate_purchase_order_product_summary_report_pdf')}}?" + $.param(query)
		//}else{
		//	var url = "{{URL::to('generate_purchase_order_product_summary_report_per_client_pdf')}}?" + $.param(query)
		//}
		
		window.open(url);
	  
	}
	

	LoadSellingPriceList();
	function LoadSellingPriceList() {	
		
		$("#product_list span").remove();
		$('<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></span>').appendTo('#product_list');

		let branch_idx 				= $("#branch_id").val();
		let client_idx 				= $('#so_client_name option[value="' + $('#so_client_idx').val() + '"]').attr('data-id');
			
			$.ajax({
				url: "/get_product_list_selling_price",
				type:"POST",
				data:{
					client_idx:client_idx,
					branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response['clients_price_list']);
				  if(response['clients_price_list']!='') {			  
				  
						var len = response['clients_price_list'].length;

						
							for(var i=0; i<len; i++){
								
								var product_idx = response['clients_price_list'][i].product_idx;
								var product_name = response['clients_price_list'][i].product_name;
								var product_unit_measurement = response['clients_price_list'][i].product_unit_measurement;
								var product_price = response['clients_price_list'][i].product_price;
								
								$('#product_list span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
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

	<!--Select Branch For Update-->
	function get_branch_details(company_header){
			
			event.preventDefault();
			let branchID 		= company_header;
			
			  $.ajax({
				url: "{{ route('BranchInfo') }}",
				type:"POST",
				data:{
				  branchID:branchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#branch_name_report').text(response.branch_name);
					$('#branch_code_report').text(response.branch_code);
					$('#branch_address_report').text(response.branch_address);
					$('#branch_tin_report').text(response.branch_tin);						
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  }
	    
</script>
