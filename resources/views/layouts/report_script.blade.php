<script type="text/javascript">

	<!--Load Table-->
	$("#generate_report").click(function(event){
		
			event.preventDefault();
	
					/*Reset Warnings*/
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#billingstatementreport tbody").html("");					
					
			document.getElementById('generate_report_form').className = "g-3 needs-validation was-validated";

			let client_idx 			= $("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id');
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let less_per_liter 		= $("input[name=less_per_liter]").val();
			
			let withholding_tax_percentage 	= $("input[name=withholding_tax_percentage]").val() / 100;
			let net_value_percentage 		= $("input[name=net_value_percentage]").val();
			let vat_value_percentage 		= $("input[name=vat_value_percentage]").val() / 100;
			
			/*Call Function to Get the Grand Total Ammount, PO Range*/  
			
			  $.ajax({
				url: "/generate_report",
				type:"POST",
				//dataType: 'JSON',
				data:{
				  client_idx:client_idx,
				  start_date:start_date,
				  end_date:end_date,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Close Form*/
				$('#CreateReportModal').modal('toggle');
				
				/*Call Function to Get the Client Name and Address*/
				get_client_details();
							
				  console.log(response);
				  if(response!='') {
					
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');	
					
						var total_due = 0;
						var total_liters = 0;
						var total_liters_discount = 0;
						
						var len = response.length;
						for(var i=0; i<len; i++){
							var billing_id = response[i].billing_id;
							var drivers_name = response[i].drivers_name;
							var order_date = response[i].order_date;
							var order_po_number = response[i].order_po_number;
							var plate_no = response[i].plate_no;
							var product_name = response[i].product_name;
							var product_unit_measurement = response[i].product_unit_measurement;
							var order_quantity = response[i].order_quantity;
							var product_price = response[i].product_price;
							var order_total_amount = response[i].order_total_amount;
							var order_time = response[i].order_time;
							
							total_due += response[i].order_total_amount;
							
							if(product_unit_measurement=='L'){
								total_liters += order_quantity;
							}else{
								total_liters += 0;
							}
							
							var tr_str = "<tr>" +
								"<td align='center'>" + (i+1) + "</td>" +
								"<td align='center'>" + order_date + "</td>" +
								"<td align='center'>" + order_time + "</td>" +
								"<td align='center'>" + drivers_name + "</td>" +
								"<td align='center'>" + order_po_number + "</td>" +
								"<td align='center'>" + plate_no + "</td>" +
								"<td align='center'>" + product_name + "</td>" +
								"<td align='center'>" + order_quantity.toLocaleString("en-PH", {minimumFractionDigits: 2}) + " " + product_unit_measurement +"</td>" +
								"<td align='center'>" + product_price.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"<td align='center'>" + order_total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"<td align='center' id='editBill' data-id="+billing_id+"><a href='#' class='btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit'></a></td>" +
								"<td align='center' id='deleteBill' data-id="+billing_id+"><a class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete'></a></td>" +
								"</tr>";
							
							/*Attached the Data on the Table Body*/
							$("#billingstatementreport tbody").append(tr_str);
							
						}			
						
							total_liters_discount = total_liters * less_per_liter;
															
							vatable_sales = total_due / net_value_percentage;
							$('#vatable_sales').text(vatable_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							vat_amount = vatable_sales * vat_value_percentage;
							$('#vat_amount').text(vat_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							with_holding_tax = vatable_sales * withholding_tax_percentage;
							$('#with_holding_tax').text(with_holding_tax.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							/*Set Grand Total and Billing Date*/
							let total_due_str = total_due.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							total_amount_payable = total_due - (total_liters_discount + with_holding_tax); 				
													
							$('#total_due').text(total_due_str.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_payable').text(total_amount_payable.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							$('#total_liters_discount').text(total_liters_discount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							$('#total_volume').text(total_liters.toLocaleString("en-PH", {maximumFractionDigits: 2}) + " L");
							
							$('#report_less_per_liter').text(less_per_liter.toLocaleString("en-PH", {maximumFractionDigits: 2}) + " L");
							
							$('#amount_receivables').text(total_amount_payable.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#po_info').text(start_date_new_format + ' - ' +end_date_new_format);	
							$('#billing_date_info').text('<?php echo strtoupper(date('M/d/Y')); ?>');	
							
							$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_billing_report_pdf()"> PDF</button>'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi bi-file-earmark-excel" onclick="download_billing_report_excel()"> Excel</button>'+
							'</div>');
							
							$("#save_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi bi-save" onclick="ReceivableformOpen()"> Save as Receivables</button>'+
							'</div>');

				  }else{
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							/*No Result Found*/
							$('#total_due').text('');
							$("#billingstatementreport tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idxError').className = "invalid-feedback";
				  			  
				  $('#start_dateError').text(error.responseJSON.errors.start_date);
				  document.getElementById('start_dateError').className = "invalid-feedback";		

				  $('#end_dateError').text(error.responseJSON.errors.end_date);
				  document.getElementById('end_dateError').className = "invalid-feedback";		
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
	  });

	function reload_report(){
		
			event.preventDefault();
	
					/*Reset Warnings*/
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#billingstatementreport tbody").html("");					
					
			document.getElementById('generate_report_form').className = "g-3 needs-validation was-validated";

			let client_idx 			= $("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id');
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let less_per_liter 		= $("input[name=less_per_liter]").val();
			
			let withholding_tax_percentage 	= $("input[name=withholding_tax_percentage]").val() / 100;
			let net_value_percentage 		= $("input[name=net_value_percentage]").val();
			let vat_value_percentage 		= $("input[name=vat_value_percentage]").val() / 100;
			/*Call Function to Get the Grand Total Ammount, PO Range*/  
			
			  $.ajax({
				url: "/generate_report",
				type:"POST",
				//dataType: 'JSON',
				data:{
				  client_idx:client_idx,
				  start_date:start_date,
				  end_date:end_date,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Call Function to Get the Client Name and Address*/
				get_client_details();
							
				  console.log(response);
				  if(response!='') {
					
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');	
					
						var total_due = 0;
						var total_liters = 0;
						var total_liters_discount = 0;
						
						var len = response.length;
						for(var i=0; i<len; i++){
							var billing_id = response[i].billing_id;
							var drivers_name = response[i].drivers_name;
							var order_date = response[i].order_date;
							var order_po_number = response[i].order_po_number;
							var plate_no = response[i].plate_no;
							var product_name = response[i].product_name;
							var product_unit_measurement = response[i].product_unit_measurement;
							var order_quantity = response[i].order_quantity;
							var product_price = response[i].product_price;
							var order_total_amount = response[i].order_total_amount;
							var order_time = response[i].order_time;
							
							total_due += response[i].order_total_amount;
							
							if(product_unit_measurement=='L'){
								total_liters += order_quantity;
							}else{
								total_liters += 0;
							}
							
							var tr_str = "<tr>" +
								"<td align='center'>" + (i+1) + "</td>" +
								"<td align='center'>" + order_date + "</td>" +
								"<td align='center'>" + order_time + "</td>" +
								"<td align='center'>" + drivers_name + "</td>" +
								"<td align='center'>" + order_po_number + "</td>" +
								"<td align='center'>" + plate_no + "</td>" +
								"<td align='center'>" + product_name + "</td>" +
								"<td align='center'>" + order_quantity.toLocaleString("en-PH", {minimumFractionDigits: 2}) + " " + product_unit_measurement +"</td>" +
								"<td align='center'>" + product_price.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"<td align='center'>" + order_total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"<td align='center' id='editBill' data-id="+billing_id+"><a href='#' class='btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit'></a></td>" +
								"<td align='center' id='deleteBill' data-id="+billing_id+"><a class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete'></a></td>" +
								"</tr>";
							
							/*Attached the Data on the Table Body*/
							$("#billingstatementreport tbody").append(tr_str);
							
						}			
						
							total_liters_discount = total_liters * less_per_liter;
															
							vatable_sales = total_due / net_value_percentage;
							$('#vatable_sales').text(vatable_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							vat_amount = vatable_sales * vat_value_percentage;
							$('#vat_amount').text(vat_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							with_holding_tax = vatable_sales * withholding_tax_percentage;
							$('#with_holding_tax').text(with_holding_tax.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							/*Set Grand Total and Billing Date*/
							let total_due_str = total_due.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							total_amount_payable = total_due - (total_liters_discount + with_holding_tax); 				
													
							$('#total_due').text(total_due_str.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_payable').text(total_amount_payable.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							$('#total_liters_discount').text(total_liters_discount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							$('#total_volume').text(total_liters.toLocaleString("en-PH", {maximumFractionDigits: 2}) + " L");
							
							$('#report_less_per_liter').text(less_per_liter.toLocaleString("en-PH", {maximumFractionDigits: 2}) + " L");
							
							$('#amount_receivables').text(total_amount_payable.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#po_info').text(start_date_new_format + ' - ' +end_date_new_format);	
							$('#billing_date_info').text('<?php echo strtoupper(date('M/d/Y')); ?>');	
							
							$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_billing_report_pdf()"> PDF</button>'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi bi-file-earmark-excel" onclick="download_billing_report_excel()"> Excel</button>'+
							'</div>');
							
							$("#save_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi bi-save" onclick="ReceivableformOpen()"> Save as Receivables</button>'+
							'</div>');

				  }else{
							/*No Result Found*/
							$('#total_due').text('');
							$("#billingstatementreport tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idxError').className = "invalid-feedback";
				  			  
				  $('#start_dateError').text(error.responseJSON.errors.start_date);
				  document.getElementById('start_dateError').className = "invalid-feedback";		

				  $('#end_dateError').text(error.responseJSON.errors.end_date);
				  document.getElementById('end_dateError').className = "invalid-feedback";		
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
	  };
	    
	function get_client_details(){
		  
			let client_idx 			= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));
			
			  $.ajax({
				url: "/client_info",
				type:"POST",
				data:{
				  clientID:client_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {		
					
					/*Set Details*/
					$('#client_name_report').text(response.client_name);
					$('#client_address_report').text(response.client_address);
					
					/*Set Details for Receivables*/
					$('#client_name_receivables').text(response.client_name);
					$('#client_address_receivables').text(response.client_address);
					$('#client_tin_receivables').text(response.client_tin);
					
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  
	}
	
	function download_billing_report_excel(){
		  
			let client_idx 			= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));
			let start_date 		= $("input[name=start_date]").val();
			let end_date 		= $("input[name=end_date]").val();
		 		  
		var query = {
			client_idx:client_idx,
			start_date:start_date,
			end_date:end_date,
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_report_excel')}}?" + $.param(query)
		window.open(url);
	  
	}
	
	function download_billing_report_pdf(receivable_id){
			
			let client_idx 			= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));
			let start_date 		= $("input[name=start_date]").val();
			let end_date 		= $("input[name=end_date]").val();
			let less_per_liter 	= $("input[name=less_per_liter]").val() / 100;
			
			let withholding_tax_percentage 	= $("input[name=withholding_tax_percentage]").val()/100;
			let net_value_percentage 		= $("input[name=net_value_percentage]").val();
			let vat_value_percentage 		= $("input[name=vat_value_percentage]").val()/100;
		 	/*Added May 6, 2023*/
			let company_header 					= $("#company_header").val();	  
		var query = {
			receivable_id:receivable_id,
			client_idx:client_idx,
			start_date:start_date,
			end_date:end_date,
			less_per_liter:less_per_liter,
			company_header:company_header,
			withholding_tax_percentage:withholding_tax_percentage,
			net_value_percentage:net_value_percentage,
			vat_value_percentage:vat_value_percentage,
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_report_pdf')}}?" + $.param(query)
		window.open(url);
	  
	}
	
	/*Call Receivable Modal*/
	function ReceivableformOpen(){
			$('#CreateReceivablesModal').modal('toggle');
	}
	
	<!--Save New receivables->
	$("#save-receivables").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#tin_numberError').text('');
					$('#or_numberError').text('');
					$('#payment_termError').text('');
					$('#receivable_descriptionError').text('');

			document.getElementById('ReceivableformNew').className = "g-3 needs-validation was-validated";

			let client_idx 			= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));
			let start_date 				= $("input[name=start_date]").val();
			let end_date 				= $("input[name=end_date]").val();
			
			
			let or_number 				= $("input[name=or_number]").val();			
			let payment_term 			= $("input[name=payment_term]").val();
			let receivable_description 	= $("#receivable_description").val();
			let receivable_status 		= $("#receivable_status").val();
			
			
			let less_per_liter 		= $("input[name=less_per_liter]").val();
			
			/*Added May 6, 2023*/
			let company_header 					= $("#company_header").val();
			/*Added June 4, 2023*/
			let withholding_tax_percentage 	= $("input[name=withholding_tax_percentage]").val()/100;
			let net_value_percentage 		= $("input[name=net_value_percentage]").val();
			let vat_value_percentage 		= $("input[name=vat_value_percentage]").val()/100;
			
			$.ajax({
				url: "/create_receivables_post",
				type:"POST",
				data:{
				  client_idx:client_idx,
				  start_date:start_date,
				  end_date:end_date,
				  or_number:or_number,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  receivable_status:receivable_status,
				  less_per_liter:less_per_liter,
				  company_header:company_header,
				  withholding_tax_percentage:withholding_tax_percentage,
				  net_value_percentage:net_value_percentage,
				  vat_value_percentage:vat_value_percentage,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					/*Reset Warnings*/
					$('#tin_numberError').text('');
					$('#or_numberError').text('');
					$('#payment_termError').text('');
					$('#receivable_descriptionError').text('');
					
					var query = {
						receivable_id:response.receivable_id,
						_token: "{{ csrf_token() }}"
					}
					
					/*Reload Details or link for PDF*/
					
					download_billing_report_pdf(response.receivable_id);
					var url = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query)
					window.open(url);
					
					$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_billing_report_pdf('+response.receivable_id+')"> PDF</button>'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi bi-file-earmark-excel" onclick="download_billing_report_excel()"> Excel</button>'+
							'</div>');
					
					/*Close Form*/
				  }
				},
				error: function(error) {
				 console.log(error);	
										
				$('#or_numberError').text(error.responseJSON.errors.product_price);
				document.getElementById('or_numberError').className = "invalid-feedback";	
				
				$('#payment_termError').text(error.responseJSON.errors.product_price);
				document.getElementById('payment_termError').className = "invalid-feedback";	
				
				$('#receivable_descriptionError').text(error.responseJSON.errors.product_price);
				document.getElementById('receivable_descriptionError').className = "invalid-feedback";					
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
	  });
	    
	$('body').on('click','#editBill',function(){
			
			event.preventDefault();
			let billID = $(this).data('id');
			
			  $.ajax({
				url: "/bill_info",
				type:"POST",
				data:{
				  billID:billID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-billing-transaction").value = billID;
					
					/*Set Details*/
					document.getElementById("update_order_date").value = response[0].order_date;
					document.getElementById("update_order_time").value = response[0].order_time;
					document.getElementById("update_order_po_number").value = response[0].order_po_number;
					document.getElementById("update_client_idx").value = response[0].client_name;
					
					document.getElementById("update_plate_no").value = response[0].plate_no;
					document.getElementById("update_product_idx").value = response[0].product_name;
					document.getElementById("update_product_manual_price").value = response[0].product_price;
					document.getElementById("update_drivers_name").value = response[0].drivers_name;
					document.getElementById("update_order_quantity").value = response[0].order_quantity;
					
					var total_amount = response[0].order_total_amount;
					$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					
					$('#UpdateBillingModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });


	$("#update-billing-transaction").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#order_dateError').text('');
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
					$('#client_idxError').text('');
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#update_product_manual_priceError').text('');
					$('#order_quantityError').text('');

			document.getElementById('BillingformEdit').className = "g-3 needs-validation was-validated";
			
			let billID 							= document.getElementById("update-billing-transaction").value;
			let order_date 						= $("input[name=update_order_date]").val();
			let order_time 						= $("input[name=update_order_time]").val();
			let order_po_number 				= $("input[name=update_order_po_number]").val();			
			let client_idx 						= $("#update_client_name option[value='" + $('#update_client_idx').val() + "']").attr('data-id');
			let plate_no 						= $("input[name=update_plate_no]").val();
			let drivers_name 					= $("input[name=update_drivers_name]").val();
			let product_idx 					= $("#update_product_name option[value='" + $('#update_product_idx').val() + "']").attr('data-id');
			let update_product_manual_price 	= $("#update_product_manual_price").val();
			let order_quantity 					= $("input[name=update_order_quantity]").val();
			
			/*Client and Product Name*/
			let client_name 					= $("input[name=update_client_name]").val();
			let product_name 					= $("input[name=update_product_name]").val();
			
			$.ajax({
				url: "/update_bill_post",
				type:"POST",
				data:{
				  billID:billID,
				  order_date:order_date,
				  order_time:order_time,
				  order_po_number:order_po_number,
				  client_idx:client_idx,
				  plate_no:plate_no,
				  drivers_name:drivers_name,
				  product_idx:product_idx,
				  product_manual_price:update_product_manual_price,
				  order_quantity:order_quantity,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#update_order_dateError').text('');					
					$('#update_order_timeError').text('');
					$('#update_order_po_numberError').text('');
					$('#update_client_idxError').text('');
					
					$('#update_plate_noError').text('');
					$('#update_drivers_nameError').text('');
					$('#update_product_idxError').text('');
					$('#update_product_manual_priceError').text('');
					$('#update_order_quantityError').text('');
					$('#UpdateBillingModal').modal('toggle');
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					reload_report();
					
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#update_order_dateError').text(error.responseJSON.errors.order_date);
				  document.getElementById('update_order_dateError').className = "invalid-feedback";
				  			  
				  $('#update_order_timeError').text(error.responseJSON.errors.order_time);
				  document.getElementById('update_order_timeError').className = "invalid-feedback";		

				  $('#update_order_po_numberError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('update_order_po_numberError').className = "invalid-feedback";		
				
					if(error.responseJSON.errors.client_idx=='Client is Required'){
						
							if(client_name==''){
								$('#update_client_idxError').html(error.responseJSON.errors.client_idx);
							}else{
								$('#update_client_idxError').html("Incorrect Client Name <b>" + client_name + "</b>");
							}
						
							document.getElementById("update_client_idx").value = "";
							document.getElementById('update_client_idxError').className = "invalid-feedback";
							
					}
					
				  $('#update_plate_noError').text(error.responseJSON.errors.plate_no);
				  document.getElementById('update_plate_noError').className = "invalid-feedback";				
				 
				  $('#update_drivers_nameError').text(error.responseJSON.errors.drivers_name);
				  document.getElementById('update_drivers_nameError').className = "invalid-feedback";				
				  
				  $('#update_product_idxError').text(error.responseJSON.errors.product_idx);
				  document.getElementById('update_product_idxError').className = "invalid-feedback";

			      	if(error.responseJSON.errors.product_idx=='Product is Required'){
						
							if(product_name==''){
								$('#update_product_idxError').html(error.responseJSON.errors.product_idx);
							}else{
								$('#update_product_idxError').html("Incorrect Product Name <b>" + product_name + "</b>");
							}
							
							document.getElementById("update_product_idx").value = "";
							document.getElementById('update_product_idxError').className = "invalid-feedback";
			
					}

 				  $('#update_order_quantityError').text(error.responseJSON.errors.order_quantity);
				  document.getElementById('update_order_quantityError').className = "invalid-feedback";
		
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });
	  
	<!--Bill Deletion Confirmation-->
	$('body').on('click','#deleteBill',function(){
			
			event.preventDefault();
			let billID = $(this).data('id');

			  $.ajax({
				url: "/bill_info",
				type:"POST",
				data:{
				  billID:billID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteBillConfirmed").value = billID;
					
					/*Set Details*/
					$('#bill_delete_order_date').text(response[0].order_date);
					$('#bill_delete_order_time').text(response[0].order_time);
					$('#bill_delete_order_po_number').text(response[0].order_po_number);
					$('#bill_delete_client_name').text(response[0].client_name);
					$('#bill_delete_plate_no').text(response[0].plate_no);
					$('#bill_delete_product_name').text(response[0].product_name);
					$('#bill_delete_drivers_name').text(response[0].drivers_name);
					$('#bill_delete_order_quantity').text(response[0].order_quantity);					
					$('#bill_delete_order_total_amount').text(response[0].order_total_amount);

					$('#BillDeleteModal').modal('toggle');									  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--Site Confirmed For Deletion-->
	  $('body').on('click','#deleteBillConfirmed',function(){
			
			event.preventDefault();

			let billID = document.getElementById("deleteBillConfirmed").value;
			
			  $.ajax({
				url: "/delete_bill_confirmed",
				type:"POST",
				data:{
				  billID:billID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Bill Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
	
					reload_report();
					
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });		
	  });
 
</script>
