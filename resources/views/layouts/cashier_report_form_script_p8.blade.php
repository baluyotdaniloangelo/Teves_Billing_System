   <script type="text/javascript">
	
	/*Call Function for Product Cahier's Report*/
	$("#save-CRPH8").click(function(event){
		
			event.preventDefault();
			
				
				$('#credit_debit_payment_amountError').text('');
				$('#ending_dipstick_inventoryError').text('');
				$('#limitless_payment_amountError').text('');
			
			let CashiersReportId 				= {{ $CashiersReportId }};			
		
			var online_payment_amount 				= $("input[name=online_payment_amount]").val();
			var limitless_payment_amount 			= $("input[name=limitless_payment_amount]").val();
			var credit_debit_payment_amount			= $("input[name=credit_debit_payment_amount]").val();
			var gcash_payment_amount 				= $("input[name=gcash_payment_amount]").val();
			
			document.getElementById('CRPH8_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH8') }}",
					type:"POST",
					data:{
					  CRPH8_ID:0,
					  CashiersReportId:CashiersReportId,
					  limitless_payment_amount:limitless_payment_amount,
					  online_payment_amount:online_payment_amount,
					  credit_debit_payment_amount:credit_debit_payment_amount,
					  gcash_payment_amount:gcash_payment_amount,
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
							
						;		
						$('#gcash_payment_amountError').text('');
						$('#limitless_payment_amountError').text('');
						$('#credit_debit_payment_amountError').text('');
						
						/*Clear Form*/
						document.getElementById("online_payment_amount").value = '0';
						document.getElementById("online_payment_amount").value = '0';
						document.getElementById("limitless_payment_amount").value = '0';
						document.getElementById("credit_debit_payment_amount").value = '0';
						document.getElementById("gcash_payment_amount").value = '0';
						
						LoadCashiersReportPH8();
						document.getElementById('CRPH8_form').className = "g-3 needs-validation";
					  }
					},
					error: function(error) {
					 console.log(error);
							
						
							$('#gcash_payment_amountError').text(error.responseJSON.errors.gcash_payment_amount);
							document.getElementById('gcash_payment_amountError').className = "invalid-feedback";
							
							
							$('#limitless_payment_amountError').text(error.responseJSON.errors.limitless_payment_amount);
							document.getElementById('limitless_payment_amountError').className = "invalid-feedback";
							
							$('#credit_debit_payment_amountError').text(error.responseJSON.errors.credit_debit_payment_amount);
							document.getElementById('credit_debit_payment_amountError').className = "invalid-feedback";
							
					}
				   });		
		 });


	$('body').on('click','#CHPH8_Edit',function(){		
	
			event.preventDefault();
			let CRPH8_ID = $(this).data('id');	
			$.ajax({
				url: "{{ route('CRP8_info') }}",
				type:"POST",
				data:{
				  CRPH8_ID:CRPH8_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  
				  if(response) {				
				
					document.getElementById("update-CRPH8").value = CRPH8_ID;				
					
					/*Set Details		*/		
					document.getElementById("update_online_payment_amount").value 		= response[0].online_payment_amount;
					document.getElementById("update_limitless_payment_amount").value 		= response[0].limitless_payment_amount;
					document.getElementById("update_credit_debit_payment_amount").value 	= response[0].credit_debit_payment_amount;
					document.getElementById("update_gcash_payment_amount").value 			= response[0].gcash_payment_amount;
					
					$('#Update_CRPH8_Modal').modal('toggle');
					
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});


	/*Call Function for Product Cahier's Report*/
	$("#update-CRPH8").click(function(event){
		
			event.preventDefault();
						
				$('#update_gcash_payment_amountError').text('');	
				$('#update_credit_debit_payment_amountError').text('');
				$('#update_limitless_payment_amountError').text('');
			
			let CashiersReportId 				= {{ $CashiersReportId }};			
			
			let CRPH8_ID = document.getElementById("update-CRPH8").value;
		
			var online_payment_amount 			= $("input[name=update_online_payment_amount]").val();
			var limitless_payment_amount 		= $("input[name=update_limitless_payment_amount]").val();
			var credit_debit_payment_amount		= $("input[name=update_credit_debit_payment_amount]").val();
			var gcash_payment_amount 			= $("input[name=update_gcash_payment_amount]").val();
			
			document.getElementById('update_CRPH8_form').className = "g-3 needs-validation was-validated";

				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH8') }}",
					type:"POST",
					data:{
					  CRPH8_ID:CRPH8_ID,
					  online_payment_amount:online_payment_amount,
					  limitless_payment_amount:limitless_payment_amount,
					  credit_debit_payment_amount:credit_debit_payment_amount,
					  gcash_payment_amount:gcash_payment_amount,
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
							
						$('#update_gcash_payment_amountError').text('');	
						$('#update_credit_debit_payment_amountError').text('');
						$('#update_limitless_payment_amountError').text('');
						
						/*Clear Form*/
						
						document.getElementById("update_limitless_payment_amount").value = '';
						document.getElementById("update_credit_debit_payment_amount").value = '';
						document.getElementById("update_gcash_payment_amount").value = '';
						
						LoadCashiersReportPH8();
						$('#Update_CRPH8_Modal').modal('toggle');
						
					  }
					},
					error: function(error) {
					 console.log(error);
					 
							$('#update_gcash_payment_amountError').text(error.responseJSON.errors.gcash_payment_amount);
							document.getElementById('update_gcash_payment_amountError').className = "invalid-feedback";
							
							$('#update_limitless_payment_amountError').text(error.responseJSON.errors.limitless_payment_amount);
							document.getElementById('update_limitless_payment_amountError').className = "invalid-feedback";
							
							$('#update_credit_debit_payment_amountError').text(error.responseJSON.errors.credit_debit_payment_amount);
							document.getElementById('update_credit_debit_payment_amountError').className = "invalid-feedback";							
							
					}
				   });		
		 });

	LoadCashiersReportPH8(); 

	function LoadCashiersReportPH8() {		
	
		$("#table_cash_payment_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_cash_payment_body_data');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersP8') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){

							var cashiers_report_p8_id = response[i].cashiers_report_p8_id;
						
							var online_payment_amount = response[i].online_payment_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var limitless_payment_amount = response[i].limitless_payment_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var credit_debit_payment_amount = response[i].credit_debit_payment_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var gcash_payment_amount = response[i].gcash_payment_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_cash_payment_body_data tr:last').after("<tr>"+
							
							//"<td class='manual_price_td' align='center'>"+i+"</td>"+
							
							"<td class='manual_price_td' align='center'>"+limitless_payment_amount+"</td>"+
							"<td class='manual_price_td' align='center'>"+credit_debit_payment_amount+"</td>"+
							"<td class='manual_price_td' align='center'>"+gcash_payment_amount+"</td>"+
							"<td class='manual_price_td' align='center'>"+online_payment_amount+"</td>"+
							
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH8_Edit' data-id='"+cashiers_report_p8_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='CHPH8_Delete'  data-id='"+cashiers_report_p8_id+"'></a></div></td>"+
							"</tr>");		
							
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

	$('body').on('click','#CHPH8_Delete',function(){		
	
			event.preventDefault();
			let CRPH8_ID = $(this).data('id');	
			
			$.ajax({
				url: "{{ route('CRP8_info') }}",
				type:"POST",
				data:{
				  CRPH8_ID:CRPH8_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  
				  if(response) {				
				
					document.getElementById("deleteCRPH8Confirmed").value = CRPH8_ID;	
					
					/*Set Details*/					
					
					$('#delete_online_payment_amount').text(response[0].online_payment_amount);
					$('#delete_limitless_payment_amount').text(response[0].limitless_payment_amount);
					$('#delete_credit_debit_payment_amount').text(response[0].credit_debit_payment_amount);
					$('#delete_gcash_payment_amount').text(response[0].gcash_payment_amount);
					
					$('#CRPH8DeleteModal').modal('toggle');	
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});

	$('body').on('click','#deleteCRPH8Confirmed',function(){
			
		let CRPH8_ID = document.getElementById("deleteCRPH8Confirmed").value;
		
		if(CRPH8_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP8') }}",
				type:"POST",
				data:{
				  CRPH8_ID:CRPH8_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					LoadCashiersReportPH8();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});
</script>
