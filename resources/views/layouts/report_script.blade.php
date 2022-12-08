

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

			let client_idx 		= $("#client_idx").val();
			let start_date 		= $("input[name=start_date]").val();
			let end_date 		= $("input[name=end_date]").val();
			
			
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
					
						var grand_total_amount = 0;
						
						var len = response.length;
						for(var i=0; i<len; i++){
							var id = response[i].id;
							var drivers_name = response[i].drivers_name;
							var order_date = response[i].order_date;
							var order_po_number = response[i].order_po_number;
							var plate_no = response[i].plate_no;
							var product_name = response[i].product_name;
							var order_quantity = response[i].order_quantity;
							var product_price = response[i].product_price;
							var order_total_amount = response[i].order_total_amount;
							var order_time = response[i].order_time;
							
							grand_total_amount += response[i].order_total_amount;

							var tr_str = "<tr>" +
								"<td align='center'>" + (i+1) + "</td>" +
								"<td align='center'>" + order_date + "</td>" +
								"<td align='center'>" + drivers_name + "</td>" +
								"<td align='center'>" + order_po_number + "</td>" +
								"<td align='center'>" + plate_no + "</td>" +
								"<td align='center'>" + product_name + "</td>" +
								"<td align='center'>" + order_quantity + "</td>" +
								"<td align='center'>" + product_price + "</td>" +
								"<td align='center'>" + order_total_amount + "</td>" +
								"<td align='center'>" + order_time + "</td>" +
								"</tr>";
							
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							
							/*Attached the Data on the Table Body*/
							$("#billingstatementreport tbody").append(tr_str);
							
						}			
							
							/*Set Grand Total and Billing Date*/
							$('#grand_total_amount').text(grand_total_amount);
							$('#billing_date_info').text('<?php echo date('Y-m-d'); ?>');	
							/*download_billing_report();*/
							
							$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_billing_report_pdf()">PDF</button>'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi bi-file-earmark-excel" onclick="download_billing_report_excel()">Excel</button>'+
							'</div>').appendTo('#download_options');
							
							
							
				  }else{
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							/*No Result Found*/
							$('#grand_total_amount').text('');
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
		  
			let client_idx 		= $("#client_idx").val();
			
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
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  
	}
	
	function download_billing_report_excel(){
		  
			let client_idx 		= $("#client_idx").val();
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
		  
			let client_idx 		= $("#client_idx").val();
			let start_date 		= $("input[name=start_date]").val();
			let end_date 		= $("input[name=end_date]").val();
		 		  
		var query = {
			client_idx:client_idx,
			start_date:start_date,
			end_date:end_date,
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_report_pdf')}}?" + $.param(query)
		window.open(url);
	  
	}
</script>
	