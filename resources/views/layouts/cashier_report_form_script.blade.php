   <script type="text/javascript">
	LoadProductRowForUpdate();
	
	<!--Save New Client->
	$("#update-cashiers-report").click(function(event){
			
			event.preventDefault();
			
			/*Reset Warnings*/
				$('#teves_branchError').text('');
				$('#forecourt_attendantError').text('');
				$('#report_dateError').text('');

			document.getElementById('CashierReportformNew').className = "g-3 needs-validation was-validated";
			
			let CashiersReportId 			= {{ $CashiersReportId }};
			
			let teves_branch 				= $("#teves_branch").val();
			let forecourt_attendant 		= $("input[name=forecourt_attendant]").val();
			let report_date 				= $("input[name=report_date]").val();
			let shift 						= $("input[name=shift]").val();
			
			  $.ajax({
				url: "/update_cashier_report_post",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  teves_branch:teves_branch,
				  forecourt_attendant:forecourt_attendant,
				  report_date:report_date,
				  shift:shift,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#teves_branchError').text('');
					$('#forecourt_attendantError').text('');
					$('#report_dateError').text('');
					
					//AddProductReport();
					LoadProductRowForUpdate();
					//document.getElementById("CashierReportformNew").reset();				
					//document.getElementById('CashierReportformNew').className = "g-3 needs-validation";
					
					//cashier_report_id = response.cashiers_report_id;
					//var query = {
					//	cashier_report_id:cashier_report_id,
					//	_token: "{{ csrf_token() }}"
					//}
					
					/*Open Cashier's Report*/
					//var url = "{{URL::to('cashiers_report_form')}}";
					//window.location.href = url+'/'+cashier_report_id;
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.teves_branch=="The client name has already been taken."){
							  
				  $('#teves_branchError').html("<b>"+ teves_branch +"</b> has already been taken.");
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				  document.getElementById('teves_branch').className = "form-control is-invalid";
				  $('#teves_branch').val("");
				  
				}else{
				  $('#teves_branchError').text(error.responseJSON.errors.teves_branch);
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				}
				
				  $('#forecourt_attendantError').text(error.responseJSON.errors.forecourt_attendant);
				  document.getElementById('forecourt_attendantError').className = "invalid-feedback";	
				  
				  $('#report_dateError').text(error.responseJSON.errors.report_date);
				  document.getElementById('report_dateError').className = "invalid-feedback";			

				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);
				  
				}
			   });		
	  });


	function TotalAmount(){
		
		let product_price 			= $("#product_name option[value='" + $('#product_idx').val() + "']").attr('data-price');
		let product_manual_price 	= $("#product_manual_price").val();
		
		var beginning_reading 		= $("input[name=beginning_reading]").val();
		var closing_reading 		= $("input[name=closing_reading]").val();
		var calibration 			= $("input[name=calibration]").val();
		
		let order_quantity 			= (closing_reading - beginning_reading) - calibration;
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}
		
	}
	/*Call Function for Product Cahier's Report*/
	$("#save-billing-transaction").click(function(event){
		
			event.preventDefault();
			
			$('#product_idxError').text('');
			$('#beginning_readingError').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
		
			var product_idx 			= $("#product_name option[value='" + $('#product_idx').val() + "']").attr('data-id');
			var beginning_reading 		= $("input[name=beginning_reading]").val();
			var closing_reading 		= $("input[name=closing_reading]").val();
			var calibration 			= $("input[name=calibration]").val();
			var product_manual_price 	= $("input[name=product_manual_price]").val();
			
			document.getElementById('BillingformNew').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "/save_product_chashiers_report",
					type:"POST",
					data:{
					  CashiersReportId:CashiersReportId,
					  product_idx:product_idx,
					  beginning_reading:beginning_reading,
					  closing_reading:closing_reading,
					  calibration:calibration,
					  product_manual_price:product_manual_price, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						
						$('#product_idxError').text('');					
						$('#beginning_readingError').text('');		
						
					  }
					},
					error: function(error) {
					 console.log(error);
						/*alert(error);*/
							$('#product_idxError').text(error.responseJSON.errors.product_idx);
							document.getElementById('product_idxError').className = "invalid-feedback";
							
							$('#beginning_readingError').text(error.responseJSON.errors.beginning_reading);
							document.getElementById('beginning_readingError').className = "invalid-feedback";
					}
				   });		
		 });

	function AddProductRow() {
		
		var x = document.getElementById("table_product_body_data").rows.length;
		/*Limit to 5 rows*/
		
		if(x > 50){
		   return;
		}else{
		
			$('#table_product_body_data tr:last').after("<tr>"+
			"<td class='product_td' align='center'>"+
			"<select class='form-control form-select product_idx' name='product_idx' id='product_idx' required>"+
				"<option selected='' disabled='' value=''>Choose...</option>"+
					<?php foreach ($product_data as $product_data_cols){ ?>
						"<option value='<?=$product_data_cols->product_id;?>'>"+
						"<?=$product_data_cols->product_name;?> | <span style='font-family: DejaVu Sans; sans-serif;'>&#8369;</span>&nbsp;<?=$product_data_cols->product_price;?></option>"+
					<?php } ?>
			"</select></td>"+
			"<td class='beginning_reading_td' align='center'><input type='number' class='form-control beginning_reading' id='beginning_reading' name='beginning_reading' ></td>"+
			"<td class='closing_reading_td' align='center'><input type='number' class='form-control closing_reading' id='closing_reading' name='closing_reading' ></td>"+
			"<td class='calibration_td' align='center'><input type='number' class='form-control calibration' id='calibration' name='calibration' ></td>"+
			"<td class='manual_price_td' align='center'><input type='text' class='form-control product_manual_price' placeholder='0.00' aria-label='' name='product_manual_price' id='product_manual_price' value=''></td>"+
			"<td><div onclick='deleteRow(this)' data-id='0' id='component_id'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP1'></a></div></div></td></tr>");
		
		}	
	}

	function LoadProductRowForUpdate() {
		
		//event.preventDefault();
		$("#table_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_body_data');
		let CashiersReportId 			= {{ $CashiersReportId }};
		
			  $.ajax({
				url: "{{ route('GetCashiersProductP1') }}",
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
						
							var cashiers_report_p1_id = response[i].cashiers_report_p1_id;
							
							var product_idx = response[i].product_idx;
							var product_price = response[i].product_price;
							
							var beginning_reading = response[i].beginning_reading;
							var closing_reading = response[i].closing_reading;
							
							var calibration = response[i].calibration;
							
							$('#table_product_body_data tr:last').after("<tr>"+
			"<td class='product_td' align='center'>"+
			"<select class='form-control form-select product_idx' name='product_idx' id='product_idx' required>"+
				"<option selected='' disabled='' value=''>Choose...</option>"+
										<?php foreach ($product_data as $product_data_cols){ ?>
											"<option value='<?=$product_data_cols->product_id;?>'"+
											((product_idx == <?=$product_data_cols->product_id;?>) ? 'selected' : '') +
											">"+
											"<?=$product_data_cols->product_name;?> | <span style='font-family: DejaVu Sans; sans-serif;'>&#8369;</span>&nbsp;<?=$product_data_cols->product_price;?></option>"+
										<?php } ?>
								"</select></td>"+
			"<td class='beginning_reading_td' align='center'><input type='number' class='form-control beginning_reading' id='beginning_reading' name='beginning_reading' value='"+beginning_reading+"'></td>"+
			"<td class='closing_reading_td' align='center'><input type='number' class='form-control closing_reading' id='closing_reading' name='closing_reading' value='"+closing_reading+"'></td>"+
			"<td class='calibration_td' align='center'><input type='number' class='form-control calibration' id='calibration' name='calibration' value='"+calibration+"'></td>"+
			"<td class='manual_price_td' align='center'><input type='text' class='form-control product_manual_price' placeholder='0.00' aria-label='' name='product_manual_price' id='product_manual_price' value='"+product_price+"'></td>"+
			"<td><div onclick='deleteCahsiersReportProductRow(this)' data-id='"+cashiers_report_p1_id+"' id='component_id'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP1'></a></div></div></td></tr>");
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

	function deleteCahsiersReportProductRow(btn) {
			
		var component_id= $(btn).data("id");			
		
		var row = btn.parentNode.parentNode;
		row.parentNode.removeChild(row);
		
		if(component_id!=0){
			/*Delete the Selected Item*/
			
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP1') }}",
				type:"POST",
				data:{
				  component_id:component_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
				
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });		
		}
		
	}

</script>