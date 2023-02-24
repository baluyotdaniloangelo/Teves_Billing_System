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
							var id = response[i].id;
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
								"</tr>";
							
							/*Attached the Data on the Table Body*/
							$("#billingstatementreport tbody").append(tr_str);
							
						}			
						
							total_liters_discount = total_liters * less_per_liter;
							total_amount_payable = total_due - total_liters_discount; 					
							
							/*Set Grand Total and Billing Date*/
							let total_due_str = total_due.toLocaleString("en-PH", {minimumFractionDigits: 2});
							
							$('#total_due').text(total_due_str.toLocaleString("en-PH", {minimumFractionDigits: 2}));
							$('#total_payable').text(total_amount_payable.toLocaleString("en-PH", {minimumFractionDigits: 2}));
							
							$('#total_liters_discount').text(total_liters_discount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
							
							$('#total_volume').text(total_liters.toLocaleString("en-PH", {minimumFractionDigits: 2}) + " L");
							
							$('#report_less_per_liter').text(less_per_liter.toLocaleString("en-PH", {minimumFractionDigits: 2}) + " L");
							
							$('#amount_receivables').text(total_amount_payable);
							
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#po_info').text(start_date_new_format + ' - ' +end_date_new_format);	
							$('#billing_date_info').text('<?php echo date('m/d/Y'); ?>');	
							
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
	
	function download_billing_report_pdf(){
		  
			let client_idx 			= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));
			let start_date 		= $("input[name=start_date]").val();
			let end_date 		= $("input[name=end_date]").val();
			let less_per_liter 	= $("input[name=less_per_liter]").val();
		 		  
		var query = {
			client_idx:client_idx,
			start_date:start_date,
			end_date:end_date,
			less_per_liter:less_per_liter,
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
					
					download_billing_report_pdf();
					var url = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query)
					window.open(url);
					
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
</script>