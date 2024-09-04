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

			let client_idx 			= $('#client_name option[value="' + $('#client_id').val() + '"]').attr('data-id');
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			
			  $.ajax({
				url: "/generate_soa_summary",
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
					
						//var total_due = 0;
						//var total_liters = 0;
						var receivable_current_balance = 0;
						
						var len = response.length;
						for(var i=0; i<len; i++){
							
							var billing_id = response[i].billing_id;
							var billing_date = response[i].billing_date;
							var control_number = response[i].control_number;
							var receivable_description = response[i].receivable_description;
							var receivable_amount = response[i].receivable_amount;
							var receivable_remaining_balance = response[i].receivable_remaining_balance;
							
							//_current_balance += order_quantity;
							
							 receivable_current_balance += 0 + response[i].receivable_remaining_balance;
							
							
							
						//	total_due += response[i].order_total_amount;
							
							var tr_str = "<tr>" +
								"<td align='center'>" + (i+1) + "</td>" +
								"<td align='left'>" + billing_date + "</td>" +
								"<td align='left'>" + control_number + "</td>" +
								"<td align='left'>" + receivable_description +"</td>" +
								"<td align='right'>" + receivable_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"<td align='right'>" + receivable_remaining_balance.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"<td align='right'>" + receivable_current_balance.toLocaleString("en-PH", {minimumFractionDigits: 2}) + "</td>" +
								"</tr>";
							
							/*Attached the Data on the Table Body*/
							$("#billingstatementreport tbody").append(tr_str);
							
						}			
						
							
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#po_info').text(start_date_new_format + ' - ' +end_date_new_format);	
							$('#billing_date_info').text('<?php echo strtoupper(date('M/d/Y')); ?>');	
							
								$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_soa_summary_report_pdf()"> PDF</button>'+
							'</div>');
							
				  }else{
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							/*No Result Found*/
							$('#total_due').text('');
							$("#billingstatementreport tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
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
		  
			let client_idx 			= $('#client_name option[value="' + $('#client_id').val() + '"]').attr('data-id');
			
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

	function download_soa_summary_report_pdf(receivable_id){
			
			let client_idx 		= $('#client_name option[value="' + $('#client_id').val() + '"]').attr('data-id');
			let start_date 		= $("input[name=start_date]").val();
			let end_date 		= $("input[name=end_date]").val();

			let company_header 					= $("#company_header").val();	  
			
		var query = {
			company_header:company_header,
			client_idx:client_idx,
			start_date:start_date,
			end_date:end_date,
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_soa_summary_pdf')}}?" + $.param(query)
		window.open(url);
	  
	}
	    

</script>
